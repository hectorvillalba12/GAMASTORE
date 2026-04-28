<?php
class Persona {
    private $conn;
    private $table = "persona";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function crear($data) {
        $sql = "INSERT INTO persona (nombre, apellido, dni, telefono, email)
                VALUES (:nombre, :apellido, :dni, :telefono, :email)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'nombre'   => $data['nombre'],
            'apellido' => $data['apellido'],
            'dni'      => $data['dni'],
            'telefono' => $data['telefono'],
            'email'    => $data['email']
        ]);

        return $this->conn->lastInsertId();
    }

    public function actualizar($data) {
        $sql = "UPDATE persona 
                SET nombre   = :nombre,
                    apellido = :apellido,
                    dni      = :dni,
                    telefono = :telefono,
                    email    = :email
                WHERE id_persona = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id'       => $data['id'],
            'nombre'   => $data['nombre'],
            'apellido' => $data['apellido'],
            'dni'      => $data['dni'],
            'telefono' => $data['telefono'],
            'email'    => $data['email']
        ]);
    }
}