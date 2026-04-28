<?php
class Inventario {
    private $conn;
    private $table = "inventario";

    public function __construct($db) {
        $this->conn = $db;
    }

    // LISTAR con JOIN a producto
    public function listar() {
        $sql = "SELECT i.*, p.nombre AS nombre_producto 
                FROM inventario i
                LEFT JOIN producto p ON i.producto_id_producto = p.id_producto
                ORDER BY i.id_inventario DESC";
        return $this->conn->query($sql);
    }

    // OBTENER uno por ID
    public function obtener($id) {
        $sql = "SELECT i.*, p.nombre AS nombre_producto 
                FROM inventario i
                LEFT JOIN producto p ON i.producto_id_producto = p.id_producto
                WHERE i.id_inventario = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CREAR
    public function crear($data) {
        $sql = "INSERT INTO inventario (stock_actual, ubicacion, stock_maximo, stock_minimo, producto_id_producto)
                VALUES (:stock_actual, :ubicacion, :stock_maximo, :stock_minimo, :producto_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'stock_actual'  => $data['stock_actual'],
            'ubicacion'     => $data['ubicacion'],
            'stock_maximo'  => $data['stock_maximo'],
            'stock_minimo'  => $data['stock_minimo'],
            'producto_id'   => $data['producto_id_producto']
        ]);
    }

    // ACTUALIZAR
    public function actualizar($data) {
        $sql = "UPDATE inventario SET
                    stock_actual = :stock_actual,
                    ubicacion = :ubicacion,
                    stock_maximo = :stock_maximo,
                    stock_minimo = :stock_minimo,
                    producto_id_producto = :producto_id
                WHERE id_inventario = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id'            => $data['id_inventario'],
            'stock_actual'  => $data['stock_actual'],
            'ubicacion'     => $data['ubicacion'],
            'stock_maximo'  => $data['stock_maximo'],
            'stock_minimo'  => $data['stock_minimo'],
            'producto_id'   => $data['producto_id_producto']
        ]);
    }

    // ELIMINAR
    public function eliminar($id) {
        $sql = "DELETE FROM inventario WHERE id_inventario = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // LISTAR todos los productos para el select
    public function productosDisponibles() {
        $sql = "SELECT * FROM producto ORDER BY nombre ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
