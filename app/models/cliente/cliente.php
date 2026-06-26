<?php
class Cliente {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // LISTAR solo clientes ACTIVOS
    public function listar() {
        $sql = "SELECT c.id_cliente, c.fecha_registro, c.estado, c.email_verificado,
                    p.id_persona, p.nombre, p.apellido, p.dni, p.email, p.telefono
                FROM cliente c
                JOIN persona p ON c.persona_idpersona = p.id_persona
                WHERE c.estado = 'activo'
                ORDER BY p.nombre ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // LISTAR solo clientes INACTIVOS (baja lógica)
    public function listarInactivos() {
        $sql = "SELECT c.id_cliente, c.fecha_registro, c.estado, c.email_verificado,
                    p.id_persona, p.nombre, p.apellido, p.dni, p.email, p.telefono
                FROM cliente c
                JOIN persona p ON c.persona_idpersona = p.id_persona
                WHERE c.estado = 'inactivo'
                ORDER BY p.nombre ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // OBTENER un cliente por id_cliente
    public function obtener($id) {
        $sql = "SELECT c.id_cliente, c.fecha_registro, c.estado, c.email_verificado,
                    p.id_persona, p.nombre, p.apellido, p.dni, p.email, p.telefono
                FROM cliente c
                JOIN persona p ON c.persona_idpersona = p.id_persona
                WHERE c.id_cliente = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CREAR cliente — acepta token para verificación de email
    public function crear($id_persona, $token = null) {
        $sql = "INSERT INTO cliente (persona_idpersona, fecha_registro, email_token, email_verificado, estado)
                VALUES (:id_persona, NOW(), :token, 0, 'activo')";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id_persona' => $id_persona,
            'token'      => $token
        ]);
    }

    // BAJA LÓGICA — cambia estado a 'inactivo' en lugar de borrar
    public function darDeBaja($id) {
        $sql  = "UPDATE cliente SET estado = 'inactivo' WHERE id_cliente = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // REACTIVAR — cambia estado a 'activo'
    public function reactivar($id) {
        $sql  = "UPDATE cliente SET estado = 'activo' WHERE id_cliente = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // Marca el email como verificado y borra el token
    public function verificarEmail($token) {
        $sql  = "UPDATE cliente SET email_verificado = 1, email_token = NULL
                WHERE email_token = :token";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['token' => $token]);
        return $stmt->rowCount() > 0;
    }

    // Busca un cliente por su token
    public function obtenerPorToken($token) {
        $sql  = "SELECT c.id_cliente, p.email
                FROM cliente c
                JOIN persona p ON c.persona_idpersona = p.id_persona
                WHERE c.email_token = :token";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}