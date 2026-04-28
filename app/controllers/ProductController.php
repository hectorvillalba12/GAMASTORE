<?php
require_once __DIR__ . '/../models/Product.php';

class ProductController {

    private $producto;

    public function __construct() {
        $db = (new Database())->connect();
        $this->producto = new Product($db);
    }

    public function index() {
        $productos = $this->producto->listar();
        require __DIR__ . '/../views/productos/index.php';
    }

    public function create() {
        
        $db = (new Database())->connect();

        $marcas = $db->query("SELECT * FROM marca");
        $talles = $db->query("SELECT * FROM talle");
        $colores = $db->query("SELECT * FROM color");
        $categorias = $db->query("SELECT * FROM categoria");
        require __DIR__ . '/../views/productos/create.php';
    }

    public function store() {
        $data = [
            'nombre' => $_POST['nombre'],
            'tipodezapatillas' => $_POST['tipodezapatillas'],
            'precio' => $_POST['precio'],
            'talle' => $_POST['talle_id_talle'],
            'marca' => $_POST['marca_id_marca'],
            'color' => $_POST['color_id_color'],
            'categoria' => $_POST['categoria_id_categoria']
        ];

        $this->producto->crear($data);

        header("Location: index.php?action=productos");
        exit();
    }

    public function edit() {
        $db = (new Database())->connect();

        $id = $_GET['id'];
        $producto = $this->producto->obtener($id);

        $marcas = $db->query("SELECT * FROM marca")->fetchAll(PDO::FETCH_ASSOC);
        $talles = $db->query("SELECT * FROM talle")->fetchAll(PDO::FETCH_ASSOC);
        $colores = $db->query("SELECT * FROM color")->fetchAll(PDO::FETCH_ASSOC);
        $categorias = $db->query("SELECT * FROM categoria")->fetchAll(PDO::FETCH_ASSOC);

        require __DIR__ . '/../views/productos/edit.php';
    }

    public function update() {
        $data = [
            'id' => $_POST['id_producto'],
            'nombre' => $_POST['nombre'],
            'tipo' => $_POST['tipodezapatillas'],
            'precio' => $_POST['precio'],
            'talle' => $_POST['talle'],
            'marca' => $_POST['marca'],
            'color' => $_POST['color'],
            'categoria' => $_POST['categoria']
        ];

        $this->producto->actualizar($data);

        header("Location: index.php?action=productos");
        exit();
    }

    public function exportExcel() {
        
        $db = new Database();
        $productos = $db->connect()->query("SELECT * FROM producto")->fetchAll();

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=productos.xls");

        echo "ID\tNombre\tPrecio\n";
        foreach ($productos as $p) {
            echo $p['id_producto']."\t".$p['nombre']."\t".$p['precio']."\n";
        }
    }

    public function delete() {
        $id = $_GET['id'];
        $this->producto->eliminar($id);

        header("Location: index.php?action=productos");
        exit();
    }
}
