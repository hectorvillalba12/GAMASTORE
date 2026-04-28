<?php
class Cliente {
    private $conn;
    private $table = "cliente";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar() {
    $sql = "SELECT c.id_cliente, p.nombre, p.apellido, p.dni, p.email
            FROM cliente c
            JOIN persona p ON c.persona_idpersona = p.id_persona";
            #WHERE c.estado='activo'";

    return $this->conn->query($sql);
}  

    public function crear($id_persona) {
        $sql = "INSERT INTO cliente(persona_idpersona, fecha_registro)
                VALUES(:id_persona, NOW())";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id_persona' => $id_persona]);
    }

    public function eliminar($id) {
        $sql = "UPDATE cliente SET estado='inactivo'
                WHERE id_cliente=:id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function obtener($id) {
        $sql = "SELECT * FROM cliente c
                JOIN persona p ON c.persona_idpersona = p.id_persona
                WHERE c.id_cliente=:id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id'=>$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}