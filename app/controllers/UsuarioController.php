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
        $usuarios          = $this->usuario->listar();
        $usuariosInactivos = $this->usuario->listarInactivos();
        require __DIR__ . '/../views/usuarios/index.php';
    }

    // MOSTRAR FORM EDITAR
    public function edit() {
        Auth::verificar();
        $id      = $_GET['id'];
        $usuario = $this->usuario->obtener($id);
        $perfiles = $this->perfil->listar();
        if (!$usuario) {
            header("Location: index.php?action=usuarios");
            exit();
        }
        require __DIR__ . '/../views/usuarios/edit.php';
    }

    // ACTUALIZAR usuario
    public function update() {
        Auth::verificar();
        $id        = $_POST['id_usuario'];
        $rol       = trim($_POST['rol']       ?? '');
        $perfil_id = $_POST['perfil_id']      ?? null;

        $this->usuario->actualizar($id, $rol, $perfil_id);
        header("Location: index.php?action=usuarios&ok=1");
        exit();
    }

    // BAJA LÓGICA
    public function delete() {
        Auth::verificar();
        $this->usuario->darDeBaja($_GET['id']);
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