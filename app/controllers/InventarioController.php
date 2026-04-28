<?php
require_once __DIR__ . '/../models/inventario.php';

class InventarioController {

    private $inventario;

    public function __construct() {
        $db = (new Database())->connect();
        $this->inventario = new Inventario($db);
    }

    public function index() {
        $inventarios = $this->inventario->listar();
        require __DIR__ . '/../views/inventario/index.php';
    }

    public function create() {
        $productos = $this->inventario->productosDisponibles();
        require __DIR__ . '/../views/inventario/create.php';
    }

    public function store() {
        $data = [
            'stock_actual'         => $_POST['stock_actual'],
            'ubicacion'            => $_POST['ubicacion'],
            'stock_maximo'         => $_POST['stock_maximo'],
            'stock_minimo'         => $_POST['stock_minimo'],
            'producto_id_producto' => $_POST['producto_id_producto']
        ];

        $this->inventario->crear($data);
        header("Location: index.php?action=inventario");
        exit();
    }

    public function edit() {
        $id = $_GET['id'];
        $item = $this->inventario->obtener($id);
        $productos = $this->inventario->productosDisponibles();
        require __DIR__ . '/../views/inventario/edit.php';
    }

    public function update() {
        $data = [
            'id_inventario'        => $_POST['id_inventario'],
            'stock_actual'         => $_POST['stock_actual'],
            'ubicacion'            => $_POST['ubicacion'],
            'stock_maximo'         => $_POST['stock_maximo'],
            'stock_minimo'         => $_POST['stock_minimo'],
            'producto_id_producto' => $_POST['producto_id_producto']
        ];

        $this->inventario->actualizar($data);
        header("Location: index.php?action=inventario");
        exit();
    }

    public function delete() {
        $id = $_GET['id'];
        $this->inventario->eliminar($id);
        header("Location: index.php?action=inventario");
        exit();
    }
}