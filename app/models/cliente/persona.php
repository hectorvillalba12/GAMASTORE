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

    // NUEVO — verifica si el DNI ya existe en otra persona
    // $excluir_id: si se pasa, excluye ese id_persona (útil al editar)
    public function existeDni($dni, $excluir_id = null) {
        if ($excluir_id) {
            $sql  = "SELECT COUNT(*) FROM persona WHERE dni = :dni AND id_persona != :excluir";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['dni' => $dni, 'excluir' => $excluir_id]);
        } else {
            $sql  = "SELECT COUNT(*) FROM persona WHERE dni = :dni";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['dni' => $dni]);
        }
        return $stmt->fetchColumn() > 0;
    }

    // NUEVO — verifica si el email ya existe en otra persona
    // $excluir_id: si se pasa, excluye ese id_persona (útil al editar)
    public function existeEmail($email, $excluir_id = null) {
        if (empty($email)) return false;
        if ($excluir_id) {
            $sql  = "SELECT COUNT(*) FROM persona WHERE email = :email AND id_persona != :excluir";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['email' => $email, 'excluir' => $excluir_id]);
        } else {
            $sql  = "SELECT COUNT(*) FROM persona WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['email' => $email]);
        }
        return $stmt->fetchColumn() > 0;
    }
}