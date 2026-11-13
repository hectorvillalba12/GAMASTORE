<?php
require_once __DIR__ . '/inventario.php';
require_once __DIR__ . '/../proveedor/proveedor.php';
class InventarioController {

    private $inventario;
    private $proveedor;

    public function __construct() {
        $db = (new Database())->connect();
        $this->inventario = new Inventario($db);
        $this->proveedor = new Proveedor($db);
    }

    // Helper para obtener el id del usuario logueado (queda registrado en cada movimiento)
    private function usuarioActualId() {
        return $_SESSION['usuario']['id_usuario'] ?? null;
    }

    public function index() {
        Auth::verificarModulo('inventario');

        // FILTROS
        $filtroBuscar = trim($_GET['buscar'] ?? '');
        $filtroTalle  = $_GET['talle'] ?? '';
        $filtroEstado = $_GET['estado'] ?? '';
        if (!in_array($filtroEstado, ['bajo', 'normal', 'sobre'], true)) {
            $filtroEstado = '';
        }

        $db     = (new Database())->connect();
        $talles = $db->query("SELECT * FROM talle ORDER BY talles_disponibles ASC")->fetchAll(PDO::FETCH_ASSOC);

        // PAGINACIÓN
        $porPagina        = 10;
        $totalInventarios = $this->inventario->contar($filtroBuscar, $filtroTalle, $filtroEstado);
        $totalPaginas     = max(1, (int)ceil($totalInventarios / $porPagina));

        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        if ($pagina < 1) $pagina = 1;
        if ($pagina > $totalPaginas) $pagina = $totalPaginas;

        $offset = ($pagina - 1) * $porPagina;

        $desde = $totalInventarios > 0 ? $offset + 1 : 0;
        $hasta = min($offset + $porPagina, $totalInventarios);

        $hayFiltros = ($filtroBuscar !== '' || !empty($filtroTalle) || $filtroEstado !== '');

        $inventarios          = $this->inventario->listar($porPagina, $offset, $filtroBuscar, $filtroTalle, $filtroEstado);
        $inventariosInactivos = $this->inventario->listarInactivos();
        $stockBajo            = $this->inventario->contarStockBajo();

        require __DIR__ . '/views/index.php';
    }

    public function create() {
        Auth::verificarModulo('inventario');
        $productos = $this->inventario->productosDisponibles();
        require __DIR__ . '/views/create.php';
    }

    public function store() {
        Auth::verificarModulo('inventario');

        $data = [
            'producto_id_producto' => $_POST['producto_id_producto'] ?? '',
            'ubicacion'             => trim($_POST['ubicacion'] ?? ''),
            'stock_actual'          => (int)($_POST['stock_actual'] ?? 0),
            'stock_minimo'          => (int)($_POST['stock_minimo'] ?? 0),
            'stock_maximo'          => (int)($_POST['stock_maximo'] ?? 0),
        ];

        if (empty($data['producto_id_producto'])) {
            header("Location: index.php?action=inventario_crear&error=producto_requerido");
            exit();
        }

        // Un producto solo puede tener UNA fila de inventario activa
        if ($this->inventario->productoTieneFilaActiva($data['producto_id_producto'], 0)) {
            header("Location: index.php?action=inventario_crear&error=producto_duplicado");
            exit();
        }
    }

    public function edit() {
        Auth::verificarModulo('inventario');
        $id   = $_GET['id'];
        $item = $this->inventario->obtener($id);

        if (!$item) {
            header("Location: index.php?action=inventario");
            exit();
        }

        require __DIR__ . '/views/edit.php';
    }

    public function update() {
        Auth::verificarModulo('inventario');

        $data = [
            'id_inventario'         => $_POST['id_inventario'],
            'producto_id_producto'  => $_POST['producto_id_producto'],
            'ubicacion'             => trim($_POST['ubicacion'] ?? ''),
            'stock_actual'          => max(0, (int)($_POST['stock_actual'] ?? 0)),
            'stock_minimo'          => max(0, (int)($_POST['stock_minimo'] ?? 0)),
            'stock_maximo'          => max(0, (int)($_POST['stock_maximo'] ?? 0)),
        ];

        // Validación: el máximo no puede ser menor al mínimo (ya la vista mostraba este error, faltaba generarlo)
        if ($data['stock_maximo'] > 0 && $data['stock_maximo'] < $data['stock_minimo']) {
            header("Location: index.php?action=inventario_editar&id={$data['id_inventario']}&error=stock_invalido");
            exit();
        }

        $this->inventario->actualizar($data, $this->usuarioActualId());
        header("Location: index.php?action=inventario&ok=1");
        exit();
    }
    // FORM de entrada de stock (llegada de mercadería)
    public function entradaForm() {
        Auth::verificarModulo('inventario');
        $id   = $_GET['id'];
        $item = $this->inventario->obtener($id);

        if (!$item) {
            header("Location: index.php?action=inventario");
            exit();
        }

        $proveedores = $this->proveedor->listarActivos();

        require __DIR__ . '/views/entrada.php';
    }

    public function entradaGuardar() {
        Auth::verificarModulo('inventario');

        $id          = $_POST['id_inventario'];
        $cantidad    = (int)($_POST['cantidad'] ?? 0);
        $motivo      = trim($_POST['motivo'] ?? '');
        $idProveedor = (int)($_POST['id_proveedor'] ?? 0);

        if ($cantidad <= 0) {
            header("Location: index.php?action=inventario_entrada&id={$id}&error=cantidad_invalida");
            exit();
        }

        if ($idProveedor <= 0) {
            header("Location: index.php?action=inventario_entrada&id={$id}&error=proveedor_requerido");
            exit();
        }

        // El proveedor tiene que existir
        if (!$this->proveedor->obtener($idProveedor)) {
            header("Location: index.php?action=inventario_entrada&id={$id}&error=proveedor_invalido");
            exit();
        }

        if (empty($motivo)) {
            header("Location: index.php?action=inventario_entrada&id={$id}&error=motivo_requerido");
            exit();
        }

        if (!$this->inventario->entrada($id, $cantidad, $motivo, $this->usuarioActualId(), $idProveedor)) {
            header("Location: index.php?action=inventario");
            exit();
        }
        header("Location: index.php?action=inventario&ok=entrada");
        exit();
    }

    // FORM de salida manual de stock
    public function salidaForm() {
        Auth::verificarModulo('inventario');
        $id   = $_GET['id'];
        $item = $this->inventario->obtener($id);

        if (!$item) {
            header("Location: index.php?action=inventario");
            exit();
        }

        $proveedores = $this->inventario->proveedoresConEntrada($id);

        require __DIR__ . '/views/salida.php';
    }

    public function salidaGuardar() {
        Auth::verificarModulo('inventario');

        $id          = $_POST['id_inventario'];
        $cantidad    = (int)($_POST['cantidad'] ?? 0);
        $motivo      = trim($_POST['motivo'] ?? '');
        $idProveedor = (int)($_POST['id_proveedor'] ?? 0);
        $enviarMail  = isset($_POST['enviar_correo']);

        $item = $this->inventario->obtener($id);
        if (!$item) {
            header("Location: index.php?action=inventario");
            exit();
        }

        if ($cantidad <= 0) {
            header("Location: index.php?action=inventario_salida&id={$id}&error=cantidad_invalida");
            exit();
        }

        if (empty($motivo)) {
            header("Location: index.php?action=inventario_salida&id={$id}&error=motivo_requerido");
            exit();
        }

        // La columna motivo admite hasta 500 caracteres
        if (mb_strlen($motivo) > 500) {
            header("Location: index.php?action=inventario_salida&id={$id}&error=motivo_largo");
            exit();
        }

        // El proveedor es opcional, pero si se elige tiene que existir
        // y tiene que ser uno que hizo la ENTRADA de este producto
        $proveedor = null;
        if ($idProveedor > 0) {
            $proveedor = $this->proveedor->obtener($idProveedor);
            if (!$proveedor) {
                header("Location: index.php?action=inventario_salida&id={$id}&error=proveedor_invalido");
                exit();
            }

            if (!$this->inventario->proveedorTuvoEntrada($id, $idProveedor)) {
                header("Location: index.php?action=inventario_salida&id={$id}&error=proveedor_no_corresponde");
                exit();
            }
        }

        // Para mandar el correo hace falta proveedor con email cargado
        if ($enviarMail) {
            if (!$proveedor) {
                header("Location: index.php?action=inventario_salida&id={$id}&error=proveedor_requerido");
                exit();
            }
            if (empty($proveedor['email'])) {
                header("Location: index.php?action=inventario_salida&id={$id}&error=proveedor_sin_email");
                exit();
            }
        }

        $resultado = $this->inventario->salida($id, $cantidad, $motivo, $this->usuarioActualId(), $proveedor ? $idProveedor : null);

        if (!$resultado['ok']) {
            header("Location: index.php?action=inventario_salida&id={$id}&error=" . $resultado['error']);
            exit();
        }

        // La salida ya quedó registrada. Si falla el correo NO se revierte: solo se avisa.
        if ($enviarMail) {
            $enviado = $this->enviarCorreoSalida($proveedor, $item, $cantidad, $motivo, $resultado['stock_nuevo']);
            header("Location: index.php?action=inventario&ok=salida&correo=" . ($enviado ? '1' : '0'));
            exit();
        }

        header("Location: index.php?action=inventario&ok=salida");
        exit();
    }

    // Arma y envía al proveedor el motivo de la salida
    private function enviarCorreoSalida($proveedor, $item, $cantidad, $motivo, $stockNuevo) {
        $producto = htmlspecialchars($item['nombre_producto'] ?? '');
        $nombre   = htmlspecialchars($proveedor['nombre']);
        $motivoHtml = nl2br(htmlspecialchars($motivo));
        $usuario  = htmlspecialchars($_SESSION['usuario']['email'] ?? '');
        $fecha    = date('d/m/Y H:i');

        $html = "
            <h3>Hola {$nombre},</h3>
            <p>Te informamos de una salida de mercadería registrada en <strong>GamaStore</strong>:</p>
            <ul>
                <li><strong>Producto:</strong> {$producto}</li>
                <li><strong>Cantidad:</strong> {$cantidad}</li>
                <li><strong>Fecha:</strong> {$fecha}</li>
            </ul>
            <p><strong>Motivo:</strong></p>
            <blockquote style='border-left:3px solid #a855f7;margin:0;padding:6px 12px;color:#333;'>{$motivoHtml}</blockquote>
            <br>
            <p style='color:#888;font-size:13px;'>Registrado por {$usuario}. Ante cualquier consulta, respondé a este correo.</p>
        ";

        return Correo::enviar(
            $proveedor['email'],
            $proveedor['nombre'],
            'GamaStore - Salida de producto: ' . ($item['nombre_producto'] ?? ''),
            $html
        );
    }

    // HISTORIAL de movimientos de una fila de inventario
    public function historial() {
        Auth::verificarModulo('inventario');
        $id          = $_GET['id'];
        $item        = $this->inventario->obtener($id);
        $movimientos = $this->inventario->listarMovimientos($id);

        if (!$item) {
            header("Location: index.php?action=inventario");
            exit();
        }

        require __DIR__ . '/views/historial.php';
    }
    
        // ELIMINAR físicamente una fila de inventario (solo si no tiene movimientos cargados)
    // BAJA LÓGICA de una fila de inventario (ya no se borra físicamente)
    public function delete() {
        Auth::verificarModulo('inventario');
        $id   = $_GET['id'];
        $item = $this->inventario->obtener($id);

        if (!$item) {
            header("Location: index.php?action=inventario");
            exit();
        }

        $this->inventario->darDeBaja($id);

        // AUDITORÍA: se registra la baja lógica
        Auditoria::registrar('inventario', $id, 'baja', $item, ['activo' => 0]);

        header("Location: index.php?action=inventario&baja=1");
        exit();
    }

    // REACTIVAR una fila de inventario dada de baja
    public function reactivar() {
        Auth::verificarModulo('inventario');
        $id   = $_GET['id'];
        $item = $this->inventario->obtener($id);

        if (!$item) {
            header("Location: index.php?action=inventario");
            exit();
        }

        // Si el producto ya tiene otra fila activa, no se puede reactivar esta (duplicaría el stock)
        if ($this->inventario->productoTieneFilaActiva($item['producto_id_producto'], $id)) {
            header("Location: index.php?action=inventario&error=ya_activo");
            exit();
        }

        $this->inventario->reactivar($id);

        // AUDITORÍA: se registra la reactivación
        Auditoria::registrar('inventario', $id, 'reactivar', $item, ['activo' => 1]);

        header("Location: index.php?action=inventario&reactivado=1");
        exit();
    }
}