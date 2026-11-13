<?php
require_once __DIR__ . '/proveedor.php';

class ProveedorController {

    private $proveedor;

    public function __construct() {
        $db = (new Database())->connect();
        $this->proveedor = new Proveedor($db);
    }

    // Lee y limpia los datos del formulario
    private function datosFormulario() {
        return [
            'nombre'    => trim($_POST['nombre']    ?? ''),
            'telefono'  => trim($_POST['telefono']  ?? ''),
            'direccion' => trim($_POST['direccion'] ?? ''),
            'email'     => trim($_POST['email']     ?? ''),
        ];
    }

    // Devuelve el código de error o '' si todo está bien
    private function validar($data, $excluirId = 0) {
        if ($data['nombre'] === '') {
            return 'nombre_requerido';
        }
        if (!preg_match('/^[0-9+\-\s()]{6,30}$/', $data['telefono'])) {
            return 'telefono_invalido';
        }
        if ($data['direccion'] === '') {
            return 'direccion_requerida';
        }
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return 'email_invalido';
        }
        if ($this->proveedor->existeEmail($data['email'], $excluirId)) {
            return 'email_duplicado';
        }
        return '';
    }

    public function index() {
        Auth::verificarModulo('proveedores');

        $buscar = trim($_GET['buscar'] ?? '');

        // PAGINACIÓN
        $porPagina        = 10;
        $totalProveedores = $this->proveedor->contar($buscar);
        $totalPaginas     = max(1, (int)ceil($totalProveedores / $porPagina));

        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        if ($pagina < 1) $pagina = 1;
        if ($pagina > $totalPaginas) $pagina = $totalPaginas;

        $offset = ($pagina - 1) * $porPagina;
        $desde  = $totalProveedores > 0 ? $offset + 1 : 0;
        $hasta  = min($offset + $porPagina, $totalProveedores);

        $proveedores          = $this->proveedor->listar($buscar, $porPagina, $offset);
        $proveedoresInactivos = $this->proveedor->listarInactivos();

        require __DIR__ . '/views/index.php';
    }

    public function create() {
        Auth::verificarModulo('proveedores');
        require __DIR__ . '/views/create.php';
    }

    public function store() {
        Auth::verificarModulo('proveedores');

        $data  = $this->datosFormulario();
        $error = $this->validar($data);

        if ($error !== '') {
            header("Location: index.php?action=proveedores_crear&error=$error");
            exit();
        }

        $id = $this->proveedor->crear($data);
        Auditoria::registrar('proveedor', $id, 'crear', null, $data);

        header("Location: index.php?action=proveedores&ok=1");
        exit();
    }

    public function edit() {
        Auth::verificarModulo('proveedores');

        $item = $this->proveedor->obtener($_GET['id'] ?? 0);
        if (!$item) {
            header("Location: index.php?action=proveedores");
            exit();
        }

        require __DIR__ . '/views/edit.php';
    }

    public function update() {
        Auth::verificarModulo('proveedores');

        $id    = (int)($_POST['id_proveedor'] ?? 0);
        $antes = $this->proveedor->obtener($id);
        if (!$antes) {
            header("Location: index.php?action=proveedores");
            exit();
        }

        $data  = $this->datosFormulario();
        $error = $this->validar($data, $id);

        if ($error !== '') {
            header("Location: index.php?action=proveedores_editar&id=$id&error=$error");
            exit();
        }

        $data['id_proveedor'] = $id;
        $this->proveedor->actualizar($data);
        Auditoria::registrar('proveedor', $id, 'actualizar', $antes, $data);

        header("Location: index.php?action=proveedores&ok=1");
        exit();
    }

    public function delete() {
        Auth::verificarModulo('proveedores');

        $id   = $_GET['id'] ?? 0;
        $item = $this->proveedor->obtener($id);
        if ($item) {
            $this->proveedor->darDeBaja($id);
            Auditoria::registrar('proveedor', $id, 'baja', $item, ['activo' => 0]);
        }

        header("Location: index.php?action=proveedores&baja=1");
        exit();
    }

    public function reactivar() {
        Auth::verificarModulo('proveedores');

        $id   = $_GET['id'] ?? 0;
        $item = $this->proveedor->obtener($id);
        if ($item) {
            $this->proveedor->reactivar($id);
            Auditoria::registrar('proveedor', $id, 'reactivar', $item, ['activo' => 1]);
        }

        header("Location: index.php?action=proveedores&reactivado=1");
        exit();
    }
}