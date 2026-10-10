<?php
require_once __DIR__ . '/inventario.php';

class InventarioController {

    private $inventario;

    public function __construct() {
        $db = (new Database())->connect();
        $this->inventario = new Inventario($db);
    }

    // Helper para obtener el id del usuario logueado (queda registrado en cada movimiento)
    private function usuarioActualId() {
        return $_SESSION['usuario']['id_usuario'] ?? null;
    }

    public function index() {
        Auth::verificarModulo('inventario');

        // PAGINACIÓN
        $porPagina        = 10;
        $totalInventarios = $this->inventario->contar();
        $totalPaginas     = max(1, (int)ceil($totalInventarios / $porPagina));

        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        if ($pagina < 1) $pagina = 1;
        if ($pagina > $totalPaginas) $pagina = $totalPaginas;

        $offset = ($pagina - 1) * $porPagina;

        $desde = $totalInventarios > 0 ? $offset + 1 : 0;
        $hasta = min($offset + $porPagina, $totalInventarios);

        $inventarios          = $this->inventario->listar($porPagina, $offset);
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

        if ($data['stock_maximo'] > 0 && $data['stock_maximo'] < $data['stock_minimo']) {
            header("Location: index.php?action=inventario_crear&error=stock_invalido");
            exit();
        }

        $this->inventario->crear($data, $this->usuarioActualId());
        header("Location: index.php?action=inventario&ok=1");
        exit();
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
            'stock_actual'          => (int)($_POST['stock_actual'] ?? 0),
            'stock_minimo'          => (int)($_POST['stock_minimo'] ?? 0),
            'stock_maximo'          => (int)($_POST['stock_maximo'] ?? 0),
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

    // FORM de entrada de stock (ej: llegada de mercadería)
    public function entradaForm() {
        Auth::verificarModulo('inventario');
        $id   = $_GET['id'];
        $item = $this->inventario->obtener($id);

        if (!$item) {
            header("Location: index.php?action=inventario");
            exit();
        }

        require __DIR__ . '/views/entrada.php';
    }

    public function entradaGuardar() {
        Auth::verificarModulo('inventario');

        $id       = $_POST['id_inventario'];
        $cantidad = (int)($_POST['cantidad'] ?? 0);
        $motivo   = trim($_POST['motivo'] ?? '');

        if ($cantidad <= 0) {
            header("Location: index.php?action=inventario_entrada&id={$id}&error=cantidad_invalida");
            exit();
        }

        if (empty($motivo)) {
            header("Location: index.php?action=inventario_entrada&id={$id}&error=motivo_requerido");
            exit();
        }

        $this->inventario->entrada($id, $cantidad, $motivo, $this->usuarioActualId());
        header("Location: index.php?action=inventario&ok=entrada");
        exit();
    }

    // FORM de salida manual de stock (ej: producto dañado, devolución a proveedor)
    public function salidaForm() {
        Auth::verificarModulo('inventario');
        $id   = $_GET['id'];
        $item = $this->inventario->obtener($id);

        if (!$item) {
            header("Location: index.php?action=inventario");
            exit();
        }

        require __DIR__ . '/views/salida.php';
    }

    public function salidaGuardar() {
        Auth::verificarModulo('inventario');

        $id       = $_POST['id_inventario'];
        $cantidad = (int)($_POST['cantidad'] ?? 0);
        $motivo   = trim($_POST['motivo'] ?? '');

        if ($cantidad <= 0) {
            header("Location: index.php?action=inventario_salida&id={$id}&error=cantidad_invalida");
            exit();
        }

        if (empty($motivo)) {
            header("Location: index.php?action=inventario_salida&id={$id}&error=motivo_requerido");
            exit();
        }

        $resultado = $this->inventario->salida($id, $cantidad, $motivo, $this->usuarioActualId());

        if (!$resultado['ok']) {
            header("Location: index.php?action=inventario_salida&id={$id}&error=" . $resultado['error']);
            exit();
        }

        header("Location: index.php?action=inventario&ok=salida");
        exit();
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