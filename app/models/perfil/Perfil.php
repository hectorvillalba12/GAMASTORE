<?php
class Perfil {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // LISTAR perfiles activos
    public function listar() {
        $sql = "SELECT * FROM perfil WHERE estado = 'activo' ORDER BY nombre ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // LISTAR perfiles inactivos
    public function listarInactivos() {
        $sql = "SELECT * FROM perfil WHERE estado = 'inactivo' ORDER BY nombre ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // OBTENER un perfil por id
    public function obtener($id) {
        $sql  = "SELECT * FROM perfil WHERE id_perfil = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CREAR perfil
    public function crear($nombre, $descripcion) {
        $sql  = "INSERT INTO perfil (nombre, descripcion, estado) VALUES (:nombre, :descripcion, 'activo')";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['nombre' => $nombre, 'descripcion' => $descripcion]);
        return $this->conn->lastInsertId();
    }

    // ACTUALIZAR perfil
    public function actualizar($id, $nombre, $descripcion) {
        $sql  = "UPDATE perfil SET nombre = :nombre, descripcion = :descripcion WHERE id_perfil = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id, 'nombre' => $nombre, 'descripcion' => $descripcion]);
    }

    // BAJA LÓGICA
    public function darDeBaja($id) {
        $sql  = "UPDATE perfil SET estado = 'inactivo' WHERE id_perfil = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // REACTIVAR
    public function reactivar($id) {
        $sql  = "UPDATE perfil SET estado = 'activo' WHERE id_perfil = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // OBTENER módulos de un perfil
    public function obtenerModulos($perfil_id) {
        $sql  = "SELECT modulo FROM perfil_has_modulo WHERE perfil_id = :perfil_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['perfil_id' => $perfil_id]);
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'modulo');
    }

    // GUARDAR módulos de un perfil (borra los anteriores y guarda los nuevos)
    public function guardarModulos($perfil_id, $modulos) {
        // Borrar módulos anteriores
        $sql  = "DELETE FROM perfil_has_modulo WHERE perfil_id = :perfil_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['perfil_id' => $perfil_id]);

        // Insertar los nuevos
        $sql  = "INSERT INTO perfil_has_modulo (perfil_id, modulo) VALUES (:perfil_id, :modulo)";
        $stmt = $this->conn->prepare($sql);
        foreach ($modulos as $modulo) {
            $stmt->execute(['perfil_id' => $perfil_id, 'modulo' => $modulo]);
        }
    }
}