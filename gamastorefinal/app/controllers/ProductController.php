<?php
class ProductController {

    private function auth() {
        if (!isset($_SESSION['usuario'])) {
            header("Location: index.php");
            exit();
        }
    }

    public function index() {
        $this->auth();
        $db = new Database();
        $productos = $db->connect()->query("SELECT * FROM producto")->fetchAll();
        include '../app/views/productos/index.php';
    }

    public function create() {
        $this->auth();
        include '../app/views/productos/create.php';
    }

    public function store() {
        $this->auth();
        $db = new Database();
        $stmt = $db->connect()->prepare("INSERT INTO producto((nombre, tipodezapatillas, precio, talle_id_talle, marca_id_marca, categoria_id_categoria, color_id_color) 
) VALUES(?,?,?,?,?,?,?)");
        $stmt->execute([$_POST['nombre'], $_POST['tipodezapatilla'],$_POST['precio'],$_POST['talle_id_talle'],$_POST['marca_id_marca'],$_POST['categoria_id_categoria'],$_POST['color_id_color']]);
        header("Location: index.php?action=productos");
    }

    public function edit() {
        $this->auth();
        $db = new Database();
        $stmt = $db->connect()->prepare("SELECT * FROM producto WHERE id_producto=?");
        $stmt->execute([$_GET['id']]);
        $producto = $stmt->fetch();
        include '../app/views/productos/edit.php';
    }

    public function update() {
        $this->auth();
        $db = new Database();
        $stmt = $db->connect()->prepare("UPDATE producto SET nombre=?, precio=? WHERE id_producto=?");
        $stmt->execute([$_POST['nombre'], $_POST['precio'], $_POST['id']]);
        header("Location: index.php?action=productos");
    }

    public function delete() {
        $this->auth();
        $db = new Database();
        $stmt = $db->connect()->prepare("DELETE FROM producto WHERE id_producto=?");
        $stmt->execute([$_GET['id']]);
        header("Location: index.php?action=productos");
    }

    public function exportExcel() {
        $this->auth();
        $db = new Database();
        $productos = $db->connect()->query("SELECT * FROM producto")->fetchAll();

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=productos.xls");

        echo "ID\tNombre\tPrecio\n";
        foreach ($productos as $p) {
            echo $p['id_producto']."\t".$p['nombre']."\t".$p['precio']."\n";
        }
    }
}
