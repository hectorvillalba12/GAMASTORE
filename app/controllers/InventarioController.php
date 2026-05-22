<?php
require_once __DIR__ . '/../models/Inventario.php';

class InventarioController {

    private $inventario;

    public function __construct() {
        $db = (new Database())->connect();
        $this->inventario = new Inventario($db);
    }

    public function index() {
        Auth::verificarModulo('inventario');
        $inventario = $this->inventario->listar();
        require __DIR__ . '/../views/inventario/index.php';
    }

    public function edit() {
        Auth::verificarModulo('inventario');
        $id         = $_GET['id'];
        $inventario = $this->inventario->obtener($id);
        require __DIR__ . '/../views/inventario/edit.php';
    }

    public function update() {
        Auth::verificarModulo('inventario');
        $this->inventario->actualizar($_POST);
        header("Location: index.php?action=inventario&ok=1");
        exit();
    }
}