<?php
require_once __DIR__ . '/../models/Perfil.php';

class PerfilController {

    private $perfil;
    private $modulos = ['dashboard', 'productos', 'clientes', 'inventario', 'ventas', 'usuarios', 'perfiles'];

    public function __construct() {
        $db           = (new Database())->connect();
        $this->perfil = new Perfil($db);
    }

    // LISTAR perfiles
    public function index() {
        Auth::verificar();
        $perfiles          = $this->perfil->listar();
        $perfilesInactivos = $this->perfil->listarInactivos();
        require __DIR__ . '/../views/perfiles/index.php';
    }

    // MOSTRAR FORM CREAR
    public function create() {
        Auth::verificar();
        $modulos = $this->modulos;
        require __DIR__ . '/../views/perfiles/create.php';
    }

    // GUARDAR nuevo perfil
    public function store() {
        Auth::verificar();
        $nombre      = trim($_POST['nombre']      ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $modulos     = $_POST['modulos']          ?? [];

        if (empty($nombre)) {
            header("Location: index.php?action=create_perfil&error=nombre_requerido");
            exit();
        }

        $id_perfil = $this->perfil->crear($nombre, $descripcion);
        $this->perfil->guardarModulos($id_perfil, $modulos);

        header("Location: index.php?action=perfiles&ok=1");
        exit();
    }

    // MOSTRAR FORM EDITAR
    public function edit() {
        Auth::verificar();
        $id      = $_GET['id'];
        $perfil  = $this->perfil->obtener($id);
        $modulos = $this->modulos;
        $modulosDelPerfil = $this->perfil->obtenerModulos($id);
        require __DIR__ . '/../views/perfiles/edit.php';
    }

    // ACTUALIZAR perfil
    public function update() {
        Auth::verificar();
        $id          = $_POST['id_perfil'];
        $nombre      = trim($_POST['nombre']      ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $modulos     = $_POST['modulos']          ?? [];

        if (empty($nombre)) {
            header("Location: index.php?action=edit_perfil&id={$id}&error=nombre_requerido");
            exit();
        }

        $this->perfil->actualizar($id, $nombre, $descripcion);
        $this->perfil->guardarModulos($id, $modulos);

        header("Location: index.php?action=perfiles&ok=1");
        exit();
    }

    // BAJA LÓGICA
    public function delete() {
        Auth::verificar();
        $this->perfil->darDeBaja($_GET['id']);
        header("Location: index.php?action=perfiles&baja=1");
        exit();
    }

    // REACTIVAR
    public function reactivar() {
        Auth::verificar();
        $this->perfil->reactivar($_GET['id']);
        header("Location: index.php?action=perfiles&reactivado=1");
        exit();
    }
}