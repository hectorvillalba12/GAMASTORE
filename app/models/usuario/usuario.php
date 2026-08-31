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

    // CONTAR admins activos
    public function contarAdminsActivos() {
        $sql  = "SELECT COUNT(*) FROM usuario WHERE rol = 'admin' AND estado = 'activo'";
        return $this->conn->query($sql)->fetchColumn();
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

    // BUSCAR usuario por email
    public function buscarPorEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ACTUALIZAR contraseña
    public function actualizarPassword($id, $hash) {
        $stmt = $this->conn->prepare("UPDATE usuario SET password = ? WHERE id_usuario = ?");
        return $stmt->execute([$hash, $id]);
    }

    // GUARDAR token de recuperación
    public function guardarToken($id, $token, $expira) {
        $stmt = $this->conn->prepare("UPDATE usuario SET reset_token = ?, token_expira = ? WHERE id_usuario = ?");
        return $stmt->execute([$token, $expira, $id]);
    }

    // BUSCAR usuario por token (solo si no expiró)
    public function buscarPorToken($token) {
        $stmt = $this->conn->prepare("SELECT * FROM usuario WHERE reset_token = ? AND token_expira > NOW()");
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // LIMPIAR token después de usarlo
    public function limpiarToken($id) {
        $stmt = $this->conn->prepare("UPDATE usuario SET reset_token = NULL, token_expira = NULL WHERE id_usuario = ?");
        return $stmt->execute([$id]);
    }

    // REGISTRAR nuevo usuario
    public function registrar($email, $hash, $rol, $estado) {
        $stmt = $this->conn->prepare("INSERT INTO usuario (email, password, rol, estado) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$email, $hash, $rol, $estado]);
    }
}