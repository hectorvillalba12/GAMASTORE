<?php
require_once __DIR__ . '/caja.php';

class CajaController {

    private $caja;
    private $db;

    public function __construct() {
        $this->db   = (new Database())->connect();
        $this->caja = new Caja($this->db);
    }

    private function usuarioActualId() {
        return $_SESSION['usuario']['id_usuario'] ?? null;
    }

    // Convierte el monto del formulario (input number, punto decimal) a float.
    // Devuelve null si no es válido.
    private function parsearMonto($valor) {
        $v = str_replace(',', '.', trim((string)$valor));
        if (!preg_match('/^\d{1,8}(\.\d{1,2})?$/', $v)) {
            return null;
        }
        return round((float)$v, 2);
    }

    // PANTALLA principal: estado de la caja + historial
    public function index() {
        Auth::verificarModulo('caja');

        $actual   = null;
        $resumen  = null;
        $esperado = 0;

        $abierta = $this->caja->obtenerAbierta();
        if ($abierta) {
            $actual   = $this->caja->obtener($abierta['id_caja']);
            $resumen  = $this->caja->resumen($abierta['id_caja']);
            $esperado = $this->caja->efectivoEsperado($actual['monto_inicial'], $resumen);
        }

        // PAGINACIÓN del historial
        $porPagina    = 10;
        $totalCajas   = $this->caja->contar();
        $totalPaginas = max(1, (int)ceil($totalCajas / $porPagina));

        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        if ($pagina < 1) $pagina = 1;
        if ($pagina > $totalPaginas) $pagina = $totalPaginas;

        $offset = ($pagina - 1) * $porPagina;
        $desde  = $totalCajas > 0 ? $offset + 1 : 0;
        $hasta  = min($offset + $porPagina, $totalCajas);

        $cajas = $this->caja->listar($porPagina, $offset);

        require __DIR__ . '/views/index.php';
    }

    // FORM de apertura
    public function abrir() {
        Auth::verificarModulo('caja');

        if ($this->caja->obtenerAbierta()) {
            header("Location: index.php?action=caja&error=ya_abierta");
            exit();
        }

        $montoArrastrado = $this->caja->efectivoUltimoCierre(); // null si es la primera caja

        require __DIR__ . '/views/abrir.php';
    }

    // GUARDAR apertura
    public function abrirGuardar() {
        Auth::verificarModulo('caja');

        // Si hubo una caja anterior, se abre con el efectivo contado en su cierre.
        // Se decide en el servidor: no se confía en lo que mande el navegador.
        $arrastrado = $this->caja->efectivoUltimoCierre();
        if ($arrastrado !== null) {
            $monto = $arrastrado;
        } else {
            $monto = $this->parsearMonto($_POST['monto_inicial'] ?? '');
            if ($monto === null) {
                header("Location: index.php?action=caja_abrir&error=monto_invalido");
                exit();
            }
        }
        $observacion = mb_substr(trim($_POST['observacion'] ?? ''), 0, 255);

        if ($this->caja->obtenerAbierta()) {
            header("Location: index.php?action=caja&error=ya_abierta");
            exit();
        }

        try {
            $id = $this->caja->abrir($monto, $this->usuarioActualId(), $observacion);
        } catch (PDOException $e) {
            // El índice único frenó una apertura simultánea
            header("Location: index.php?action=caja&error=ya_abierta");
            exit();
        }

        if (!$id) {
            header("Location: index.php?action=caja_abrir&error=error_al_guardar");
            exit();
        }

        Auditoria::registrar('caja', $id, 'apertura', null, [
            'monto_inicial' => $monto,
            'arrastrado'    => $arrastrado !== null,
            'observacion'   => $observacion
        ]);

        header("Location: index.php?action=caja&ok=abierta");
        exit();
    }

    // FORM de cierre (arqueo)
    public function cerrar() {
        Auth::verificarModulo('caja');

        $abierta = $this->caja->obtenerAbierta();
        if (!$abierta) {
            header("Location: index.php?action=caja&error=sin_caja_abierta");
            exit();
        }

        $actual    = $this->caja->obtener($abierta['id_caja']);
        $resumen   = $this->caja->resumen($abierta['id_caja']);
        $esperados = $this->caja->esperados($actual['monto_inicial'], $resumen);
        $esperado  = $esperados['Efectivo'];   // se usa en las tarjetas de arriba

        require __DIR__ . '/views/cerrar.php';
    }

    // GUARDAR cierre
    public function cerrarGuardar() {
        Auth::verificarModulo('caja');

        $id  = (int)($_POST['id_caja'] ?? 0);
        $obs = mb_substr(trim($_POST['observacion'] ?? ''), 0, 255);

        // Un monto declarado por cada método de pago
        $postDeclarado = $_POST['declarado'] ?? [];
        $declarados = [];
        foreach (Caja::METODOS as $slug => $nombre) {
            $monto = $this->parsearMonto($postDeclarado[$slug] ?? '');
            if ($monto === null) {
                header("Location: index.php?action=caja_cerrar&error=monto_invalido");
                exit();
            }
            $declarados[$nombre] = $monto;
        }

        $this->db->beginTransaction();

        try {
            // Bloquea la caja: espera a que terminen las ventas en curso y frena las nuevas
            $caja = $this->caja->bloquearPorId($id);
            if (!$caja || $caja['estado'] !== 'abierta') {
                throw new Exception('no_abierta');
            }

            // Los esperados se recalculan en el servidor, nunca se confía en el navegador
            $resumen   = $this->caja->resumen($id);
            $esperados = $this->caja->esperados($caja['monto_inicial'], $resumen);
            $cantidades = array_column($resumen['por_metodo'], 'cantidad', 'metodo_de_pago');

            $filas = [];
            $totalEsperado = 0;
            $totalDeclarado = 0;
            $hayDiferencia = false;

            foreach ($esperados as $nombre => $esp) {
                $dec = $declarados[$nombre];
                $dif = round($dec - $esp, 2);
                if ($dif != 0) {
                    $hayDiferencia = true;
                }
                $filas[] = [
                    'metodo'     => $nombre,
                    'cantidad'   => (int)($cantidades[$nombre] ?? 0),
                    'esperado'   => $esp,
                    'declarado'  => $dec,
                    'diferencia' => $dif
                ];
                $totalEsperado  += $esp;
                $totalDeclarado += $dec;
            }

            $totalEsperado   = round($totalEsperado, 2);
            $totalDeclarado  = round($totalDeclarado, 2);
            $diferenciaTotal = round($totalDeclarado - $totalEsperado, 2);

            // Si algún método tiene diferencia, la observación es obligatoria
            if ($hayDiferencia && $obs === '') {
                throw new Exception('observacion_requerida');
            }

            $ok = $this->caja->cerrar($id, [
                'total_ventas'    => $resumen['total'],
                'cantidad_ventas' => $resumen['cantidad'],
                'monto_esperado'  => $totalEsperado,
                'monto_declarado' => $totalDeclarado,
                'diferencia'      => $diferenciaTotal,
                'observacion'     => $obs,
                'usuario_id'      => $this->usuarioActualId()
            ]);
            if (!$ok) {
                throw new Exception('no_abierta');
            }

            $this->caja->guardarArqueo($id, $filas);

            $this->db->commit();

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $codigo  = in_array($e->getMessage(), ['no_abierta', 'observacion_requerida'], true)
                       ? $e->getMessage() : 'error_al_cerrar';
            $destino = ($codigo === 'no_abierta') ? 'caja' : 'caja_cerrar';
            header("Location: index.php?action={$destino}&error={$codigo}");
            exit();
        }

        Auditoria::registrar('caja', $id, 'cierre',
            ['estado' => 'abierta', 'monto_inicial' => $caja['monto_inicial']],
            [
                'estado'          => 'cerrada',
                'total_ventas'    => $resumen['total'],
                'monto_esperado'  => $totalEsperado,
                'monto_declarado' => $totalDeclarado,
                'diferencia'      => $diferenciaTotal,
                'por_metodo'      => $filas,
                'observacion'     => $obs
            ]
        );

        header("Location: index.php?action=caja_ver&id={$id}&ok=cerrada");
        exit();
    }

    // FORM de ingreso / egreso de efectivo
    public function movimiento() {
        Auth::verificarModulo('caja');

        $abierta = $this->caja->obtenerAbierta();
        if (!$abierta) {
            header("Location: index.php?action=caja&error=sin_caja_abierta");
            exit();
        }

        $tipo = $_GET['tipo'] ?? 'ingreso';
        if (!isset(Caja::MOVIMIENTOS[$tipo])) {
            $tipo = 'ingreso';
        }

        $actual     = $this->caja->obtener($abierta['id_caja']);
        $resumen    = $this->caja->resumen($abierta['id_caja']);
        $disponible = $this->caja->efectivoEsperado($actual['monto_inicial'], $resumen);

        require __DIR__ . '/views/movimiento.php';
    }

    // GUARDAR ingreso / egreso
    public function movimientoGuardar() {
        Auth::verificarModulo('caja');

        $tipo = $_POST['tipo'] ?? '';
        if (!isset(Caja::MOVIMIENTOS[$tipo])) {
            header("Location: index.php?action=caja&error=movimiento_invalido");
            exit();
        }

        $id     = (int)($_POST['id_caja'] ?? 0);
        $monto  = $this->parsearMonto($_POST['monto'] ?? '');
        $motivo = mb_substr(trim($_POST['motivo'] ?? ''), 0, 150);

        if ($monto === null || $monto <= 0) {
            header("Location: index.php?action=caja_movimiento&tipo={$tipo}&error=monto_invalido");
            exit();
        }
        if ($motivo === '') {
            header("Location: index.php?action=caja_movimiento&tipo={$tipo}&error=motivo_requerido");
            exit();
        }

        $this->db->beginTransaction();

        try {
            // Bloquea la caja: no puede cerrarse mientras se registra el movimiento
            $caja = $this->caja->bloquearPorId($id);
            if (!$caja || $caja['estado'] !== 'abierta') {
                throw new Exception('no_abierta');
            }

            // Un egreso no puede dejar el efectivo en negativo
            if ($tipo === 'egreso') {
                $resumen    = $this->caja->resumen($id);
                $disponible = $this->caja->efectivoEsperado($caja['monto_inicial'], $resumen);
                if (round($monto - $disponible, 2) > 0) {
                    throw new Exception('saldo_insuficiente');
                }
            }

            $idMov = $this->caja->registrarMovimiento($id, $tipo, $monto, $motivo, $this->usuarioActualId());
            if (!$idMov) {
                throw new Exception('error_al_guardar');
            }

            $this->db->commit();

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            $codigo = in_array($e->getMessage(), ['no_abierta', 'saldo_insuficiente'], true)
                      ? $e->getMessage() : 'error_al_guardar';
            $destino = ($codigo === 'no_abierta')
                       ? 'caja'
                       : "caja_movimiento&tipo={$tipo}";
            header("Location: index.php?action={$destino}&error={$codigo}");
            exit();
        }

        Auditoria::registrar('caja_movimiento', $idMov, $tipo, null, [
            'caja_id' => $id,
            'monto'   => $monto,
            'motivo'  => $motivo
        ]);

        header("Location: index.php?action=caja&ok={$tipo}");
        exit();
    }

    // DETALLE / arqueo de una caja (abierta o cerrada)
    public function show() {
        Auth::verificarModulo('caja');

        $id   = (int)($_GET['id'] ?? 0);
        $caja = $this->caja->obtener($id);

        if (!$caja) {
            header("Location: index.php?action=caja");
            exit();
        }

        $resumen        = $this->caja->resumen($id);
        $ventas         = $this->caja->listarVentas($id);
        $arqueo         = $this->caja->obtenerArqueo($id);
        $esperadoActual = $this->caja->efectivoEsperado($caja['monto_inicial'], $resumen);
        $movimientos    = $this->caja->listarMovimientos($id);

        require __DIR__ . '/views/show.php';
    }
}