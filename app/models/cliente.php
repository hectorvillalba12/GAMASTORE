<?php
class Cliente {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // LISTAR todos los clientes activos
    public function listar() {
        $sql = "SELECT c.id_cliente, c.fecha_registro, c.estado,
                    p.id_persona, p.nombre, p.apellido, p.dni, p.email, p.telefono
                FROM cliente c
                JOIN persona p ON c.persona_idpersona = p.id_persona
                ORDER BY p.nombre ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // OBTENER un cliente por id_cliente
    public function obtener($id) {
    $sql = "SELECT c.id_cliente, c.fecha_registro, c.estado,
                p.id_persona, p.nombre, p.apellido, p.dni, p.email, p.telefono
            FROM cliente c
            JOIN persona p ON c.persona_idpersona = p.id_persona
            WHERE c.id_cliente = :id";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute(['id' => $id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
    }   

    // CREAR cliente 
    public function crear($id_persona) {
        $sql = "INSERT INTO cliente (persona_idpersona, fecha_registro)
                VALUES (:id_persona, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id_persona' => $id_persona]);
    }

    // ELIMINAR
    public function eliminar($id) {
    $sql = "DELETE FROM cliente WHERE id_cliente = :id";
    $stmt = $this->conn->prepare($sql);
    return $stmt->execute(['id' => $id]);
    }
}