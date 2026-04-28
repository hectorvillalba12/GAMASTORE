<?php
class Product {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // LISTAR
    public function listar() {
        $sql = "SELECT * FROM producto";
        return $this->conn->query($sql);
    }

    // CREAR
    public function crear($data) {

        $sql = "INSERT INTO producto 
        (nombre, tipodezapatillas, precio, talle_id_talle, marca_id_marca, color_id_color, categoria_id_categoria)
        VALUES 
        (:nombre, :tipo, :precio, :talle, :marca, :color, :categoria)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'nombre' => $data['nombre'],
            'tipo' => $data['tipodezapatillas'],
            'precio' => $data['precio'],
            'talle' => $data['talle'],
            'marca' => $data['marca'],
            'color' => $data['color'],
            'categoria' => $data['categoria']
        ]);
    }

    //OBTENER
    public function obtener($id) {
        $sql = "SELECT * FROM producto WHERE id_producto = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ACTUALIZAR
    public function actualizar($data) {
        $sql = "UPDATE producto SET 
                nombre=:nombre,
                tipodezapatillas=:tipo,
                precio=:precio,
                talle_id_talle=:talle,
                marca_id_marca=:marca,
                color_id_color=:color,
                categoria_id_categoria=:categoria
                WHERE id_producto=:id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
    public function exportarExcel() {
    $db = new Database();
    $conn = $db->connect();

    $product = new Product($conn);
    $product->exportarExcel();
    }

    // ELIMINAR
    public function eliminar($id) {
        $sql = "DELETE FROM producto WHERE id_producto = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id'=>$id]);
    }
}