<?php
class Usuario {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // LISTAR usuarios activos
    public function listar() {
        $sql = "SELECT u.id_usuario, u.email, u.rol, u.estado, u.perfil_id,
                    p.nombre AS perfil_nombre
                FROM usuario u
                LEFT JOIN perfil p ON u.perfil_id = p.id_perfil
                WHERE u.estado = 'activo'
                ORDER BY u.email ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // LISTAR usuarios inactivos
    public function listarInactivos() {
        $sql = "SELECT u.id_usuario, u.email, u.rol, u.estado, u.perfil_id,
                    p.nombre AS perfil_nombre
                FROM usuario u
                LEFT JOIN perfil p ON u.perfil_id = p.id_perfil
                WHERE u.estado = 'inactivo'
                ORDER BY u.email ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // OBTENER un usuario por id
    public function obtener($id) {
        $sql  = "SELECT u.id_usuario, u.email, u.rol, u.estado, u.perfil_id,
                    p.nombre AS perfil_nombre
                FROM usuario u
                LEFT JOIN perfil p ON u.perfil_id = p.id_perfil
                WHERE u.id_usuario = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ACTUALIZAR perfil y rol de un usuario
    public function actualizar($id, $rol, $perfil_id) {
        $sql  = "UPDATE usuario SET rol = :rol, perfil_id = :perfil_id WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id, 'rol' => $rol, 'perfil_id' => $perfil_id]);
    }

    // BAJA LÓGICA
    public function darDeBaja($id) {
        $sql  = "UPDATE usuario SET estado = 'inactivo' WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // REACTIVAR
    public function reactivar($id) {
        $sql  = "UPDATE usuario SET estado = 'activo' WHERE id_usuario = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}