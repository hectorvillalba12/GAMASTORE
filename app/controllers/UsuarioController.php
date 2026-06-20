<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../models/Perfil.php';

class UsuarioController {

    private $usuario;
    private $perfil;

    public function __construct() {
        $db            = (new Database())->connect();
        $this->usuario = new Usuario($db);
        $this->perfil  = new Perfil($db);
    }

    // LISTAR usuarios
    public function index() {
        Auth::verificar();
        $usuarios           = $this->usuario->listar();
        $usuariosInactivos  = $this->usuario->listarInactivos();
        $totalAdminsActivos = $this->usuario->contarAdminsActivos(); // <-- NUEVO
        require __DIR__ . '/../views/usuarios/index.php';
    }

    // MOSTRAR FORM EDITAR
    public function edit() {
        Auth::verificar();
        $id       = $_GET['id'];
        $usuario  = $this->usuario->obtener($id);
        $perfiles = $this->perfil->listar();

        if (!$usuario) {
            header("Location: index.php?action=usuarios");
            exit();
        }

        // Si es el único admin activo, no se puede editar
        if ($usuario['rol'] === 'admin' && $this->usuario->contarAdminsActivos() <= 1) {
            header("Location: index.php?action=usuarios&error=unico_admin");
            exit();
        }

        require __DIR__ . '/../views/usuarios/edit.php';
    }

    // ACTUALIZAR usuario
    public function update() {
        Auth::verificar();
        $id        = $_POST['id_usuario'];
        $rol       = trim($_POST['rol']  ?? '');
        $perfil_id = $_POST['perfil_id'] ?? null;

        $usuario = $this->usuario->obtener($id);

        // Si es el único admin activo y le están cambiando el rol, bloquear
        if ($usuario['rol'] === 'admin' && $rol !== 'admin' && $this->usuario->contarAdminsActivos() <= 1) {
            header("Location: index.php?action=usuarios&error=unico_admin");
            exit();
        }

        $this->usuario->actualizar($id, $rol, $perfil_id);
        header("Location: index.php?action=usuarios&ok=1");
        exit();
    }

    // BAJA LÓGICA
    public function delete() {
        Auth::verificar();
        $id      = $_GET['id'];
        $usuario = $this->usuario->obtener($id);

        // Si es el único admin activo, no se puede dar de baja
        if ($usuario['rol'] === 'admin' && $this->usuario->contarAdminsActivos() <= 1) {
            header("Location: index.php?action=usuarios&error=unico_admin");
            exit();
        }

        $this->usuario->darDeBaja($id);
        header("Location: index.php?action=usuarios&baja=1");
        exit();
    }

    // REACTIVAR
    public function reactivar() {
        Auth::verificar();
        $this->usuario->reactivar($_GET['id']);
        header("Location: index.php?action=usuarios&reactivado=1");
        exit();
    }
}