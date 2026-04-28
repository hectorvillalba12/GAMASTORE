<?php
class Persona {
    private $conn;
    private $table = "persona";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear($data) {
        $sql = "INSERT INTO persona(nombre, apellido, dni, telefono, email)
                VALUES(:nombre, :apellido, :dni, :telefono, :email)";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);

        return $this->conn->lastInsertId();
    }

    public function actualizar($data) {
        $sql = "UPDATE persona SET nombre=:nombre, apellido=:apellido
                WHERE id_persona=:id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
}