<?php
class Caja {
    // slug del formulario => nombre exacto que guarda Ventas
    const METODOS = [
        'efectivo'      => 'Efectivo',
        'transferencia' => 'Transferencia',
        'credito'       => 'Tarjeta Crédito',
        'debito'        => 'Tarjeta Débito'
    ];
    const MOVIMIENTOS = ['ingreso' => 'Ingreso', 'egreso' => 'Egreso'];

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Devuelve la caja abierta (solo puede haber una) o false.
    // $bloquear = true toma un lock compartido: usarlo DENTRO de una transacción
    // para que una venta en curso y el cierre de caja no se pisen.
    public function obtenerAbierta($bloquear = false) {
        $sql = "SELECT * FROM caja WHERE estado = 'abierta' LIMIT 1";
        if ($bloquear) {
            $sql .= " LOCK IN SHARE MODE";
        }
        return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    // OBTENER una caja por ID (con el email de quien abrió y de quien cerró)
    public function obtener($id) {
        $sql = "SELECT c.*, ua.email AS email_apertura, uc.email AS email_cierre
                FROM caja c
                JOIN usuario ua ON c.usuario_apertura_id = ua.id_usuario
                LEFT JOIN usuario uc ON c.usuario_cierre_id = uc.id_usuario
                WHERE c.id_caja = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Bloquea la fila de la caja (usar dentro de una transacción, al cerrar)
    public function bloquearPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM caja WHERE id_caja = :id FOR UPDATE");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ABRIR caja. Devuelve el ID nuevo o false
    public function abrir($monto_inicial, $usuario_id, $observacion) {
        $sql = "INSERT INTO caja (estado, fecha_apertura, monto_inicial, observacion_apertura, usuario_apertura_id)
                VALUES ('abierta', NOW(), :monto, :obs, :usuario)";
        $stmt = $this->conn->prepare($sql);
        $ok = $stmt->execute([
            'monto'   => $monto_inicial,
            'obs'     => $observacion !== '' ? $observacion : null,
            'usuario' => $usuario_id
        ]);
        return $ok ? $this->conn->lastInsertId() : false;
    }

    // CERRAR caja: guarda el resultado del arqueo
    public function cerrar($id, $d) {
        $sql = "UPDATE caja SET
                    estado             = 'cerrada',
                    fecha_cierre       = NOW(),
                    total_ventas       = :total_ventas,
                    cantidad_ventas    = :cantidad_ventas,
                    monto_esperado     = :esperado,
                    monto_declarado    = :declarado,
                    diferencia         = :diferencia,
                    observacion_cierre = :obs,
                    usuario_cierre_id  = :usuario
                WHERE id_caja = :id AND estado = 'abierta'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'total_ventas'    => $d['total_ventas'],
            'cantidad_ventas' => $d['cantidad_ventas'],
            'esperado'        => $d['monto_esperado'],
            'declarado'       => $d['monto_declarado'],
            'diferencia'      => $d['diferencia'],
            'obs'             => $d['observacion'] !== '' ? $d['observacion'] : null,
            'usuario'         => $d['usuario_id'],
            'id'              => $id
        ]);
        return $stmt->rowCount() === 1;
    }

    // RESUMEN de lo vendido en una caja: por método de pago, total, efectivo y canceladas
    public function resumen($id_caja) {
        $sql = "SELECT metodo_de_pago, COUNT(*) AS cantidad, COALESCE(SUM(total), 0) AS total
                FROM venta
                WHERE caja_id = :id AND estado = 'completada'
                GROUP BY metodo_de_pago";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id_caja]);
        $filas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Métodos fijos: se muestran siempre, aunque no tengan ventas
        $metodos = array_values(self::METODOS);
        $porMetodo = [];
        foreach ($metodos as $nombre) {
            $porMetodo[$nombre] = ['metodo_de_pago' => $nombre, 'cantidad' => 0, 'total' => 0];
        }

        // Se completan con lo vendido (si hubiera un método distinto, se agrega al final)
        foreach ($filas as $f) {
            $nombre = $f['metodo_de_pago'] ?? 'Sin definir';
            $porMetodo[$nombre] = [
                'metodo_de_pago' => $nombre,
                'cantidad'       => (int)$f['cantidad'],
                'total'          => (float)$f['total']
            ];
        }
        $porMetodo = array_values($porMetodo);

        $total = 0; $cantidad = 0; $efectivo = 0;
        foreach ($porMetodo as $f) {
            $total    += (float)$f['total'];
            $cantidad += (int)$f['cantidad'];
            if ($f['metodo_de_pago'] === 'Efectivo') {
                $efectivo += (float)$f['total'];
            }
        }

        $sqlCanc = "SELECT COUNT(*) AS cantidad, COALESCE(SUM(total), 0) AS monto
                    FROM venta WHERE caja_id = :id AND estado = 'cancelada'";
        $stmtCanc = $this->conn->prepare($sqlCanc);
        $stmtCanc->execute(['id' => $id_caja]);
        $canc = $stmtCanc->fetch(PDO::FETCH_ASSOC);

        $mov = $this->totalesMovimientos($id_caja);

        return [
            'por_metodo'       => $porMetodo,
            'total'            => round($total, 2),
            'cantidad'         => $cantidad,
            'efectivo'         => round($efectivo, 2),
            'canceladas_cant'  => (int)$canc['cantidad'],
            'canceladas_monto' => (float)$canc['monto'],
            'ingresos'         => $mov['ingresos'],
            'egresos'          => $mov['egresos'],
        ];
    }

    // LISTAR todas las ventas de una caja (para el detalle)
    public function listarVentas($id_caja) {
        $sql = "SELECT id_venta, fecha, total, metodo_de_pago, estado
                FROM venta WHERE caja_id = :id ORDER BY fecha ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id_caja]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // HISTORIAL de cajas con paginación
    public function listar($limite = 10, $offset = 0) {
        $sql = "SELECT c.*, ua.email AS email_apertura, uc.email AS email_cierre
                FROM caja c
                JOIN usuario ua ON c.usuario_apertura_id = ua.id_usuario
                LEFT JOIN usuario uc ON c.usuario_cierre_id = uc.id_usuario
                ORDER BY c.fecha_apertura DESC
                LIMIT :limite OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contar() {
        return (int)$this->conn->query("SELECT COUNT(*) FROM caja")->fetchColumn();
    }

    // ¿La caja ya fue cerrada? (se usa para bloquear la cancelación de ventas)
    public function estaCerrada($id_caja) {
        $stmt = $this->conn->prepare("SELECT estado FROM caja WHERE id_caja = :id");
        $stmt->execute(['id' => $id_caja]);
        return $stmt->fetchColumn() === 'cerrada';
    }

    // Esperado por método: el efectivo incluye el monto inicial de la caja
    public function esperados($montoInicial, $resumen) {
        $porNombre = array_column($resumen['por_metodo'], 'total', 'metodo_de_pago');
        $esperados = [];
        foreach (self::METODOS as $nombre) {
            $esperados[$nombre] = round((float)($porNombre[$nombre] ?? 0), 2);
        }
        $esperados['Efectivo'] = $this->efectivoEsperado($montoInicial, $resumen);
        return $esperados;
    }

    // Guarda el arqueo de cada método (llamar dentro de la transacción del cierre)
    public function guardarArqueo($id_caja, $filas) {
        $sql = "INSERT INTO caja_arqueo
                    (caja_id, metodo_de_pago, cantidad_ventas, monto_esperado, monto_declarado, diferencia)
                VALUES (:caja, :metodo, :cant, :esperado, :declarado, :diferencia)";
        $stmt = $this->conn->prepare($sql);
        foreach ($filas as $f) {
            $stmt->execute([
                'caja'       => $id_caja,
                'metodo'     => $f['metodo'],
                'cant'       => $f['cantidad'],
                'esperado'   => $f['esperado'],
                'declarado'  => $f['declarado'],
                'diferencia' => $f['diferencia']
            ]);
        }
    }

    public function obtenerArqueo($id_caja) {
        $stmt = $this->conn->prepare("SELECT * FROM caja_arqueo WHERE caja_id = :id ORDER BY id_caja_arqueo");
        $stmt->execute(['id' => $id_caja]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Efectivo contado en el último cierre (null si nunca se cerró una caja).
    // Es el monto con el que debe arrancar la caja siguiente.
    public function efectivoUltimoCierre() {
        $sql = "SELECT COALESCE(a.monto_declarado, c.monto_declarado)
                FROM caja c
                LEFT JOIN caja_arqueo a
                    ON a.caja_id = c.id_caja AND a.metodo_de_pago = 'Efectivo'
                WHERE c.estado = 'cerrada'
                ORDER BY c.fecha_cierre DESC, c.id_caja DESC
                LIMIT 1";
        $v = $this->conn->query($sql)->fetchColumn();
        return $v === false ? null : round((float)$v, 2);
    }

    // Registra un ingreso o egreso de efectivo (llamar dentro de una transacción)
    public function registrarMovimiento($caja_id, $tipo, $monto, $motivo, $usuario_id) {
        $sql = "INSERT INTO caja_movimiento (caja_id, tipo, monto, motivo, usuario_id, fecha)
                VALUES (:caja, :tipo, :monto, :motivo, :usuario, NOW())";
        $stmt = $this->conn->prepare($sql);
        $ok = $stmt->execute([
            'caja'    => $caja_id,
            'tipo'    => $tipo,
            'monto'   => $monto,
            'motivo'  => $motivo,
            'usuario' => $usuario_id
        ]);
        return $ok ? $this->conn->lastInsertId() : false;
    }

    public function listarMovimientos($caja_id) {
        $sql = "SELECT m.*, u.email AS email_usuario
                FROM caja_movimiento m
                JOIN usuario u ON m.usuario_id = u.id_usuario
                WHERE m.caja_id = :id
                ORDER BY m.fecha ASC, m.id_caja_movimiento ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $caja_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function totalesMovimientos($caja_id) {
        $stmt = $this->conn->prepare(
            "SELECT tipo, COALESCE(SUM(monto), 0) AS total
            FROM caja_movimiento WHERE caja_id = :id GROUP BY tipo");
        $stmt->execute(['id' => $caja_id]);
        $t = ['ingresos' => 0.0, 'egresos' => 0.0];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $f) {
            if ($f['tipo'] === 'ingreso') $t['ingresos'] = (float)$f['total'];
            if ($f['tipo'] === 'egreso')  $t['egresos']  = (float)$f['total'];
        }
        return $t;
    }

    // Efectivo que debería haber en la caja ahora mismo
    public function efectivoEsperado($montoInicial, $resumen) {
        return round((float)$montoInicial + $resumen['efectivo']
                + $resumen['ingresos'] - $resumen['egresos'], 2);
    }
}