<?php
class Cliente {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Arma las condiciones de búsqueda (nombre, apellido, DNI, teléfono).
    // Mismo criterio que el buscador de "Nueva venta": varias palabras, sin importar mayúsculas ni tildes.
    private function condicionBusqueda($buscar, &$params) {
        $sql      = '';
        $terminos = preg_split('/\s+/', trim($buscar), -1, PREG_SPLIT_NO_EMPTY);

        foreach ($terminos as $i => $t) {
            // Escapa los comodines de LIKE para que % y _ se busquen como texto normal
            $t = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $t);
            $sql .= " AND CONCAT_WS(' ', p.nombre, p.apellido, p.dni, p.telefono) LIKE :t$i";
            $params["t$i"] = '%' . $t . '%';
        }
        return $sql;
    }

    // LISTAR clientes ACTIVOS (con búsqueda y paginación opcionales)
    public function listar($buscar = '', $limite = null, $offset = 0) {
        $params = [];

        $sql = "SELECT c.id_cliente, c.fecha_registro, c.estado, c.email_verificado,
                    p.id_persona, p.nombre, p.apellido, p.dni, p.email, p.telefono
                FROM cliente c
                JOIN persona p ON c.persona_idpersona = p.id_persona
                WHERE c.estado = 'activo'";

        $sql .= $this->condicionBusqueda($buscar, $params);
        $sql .= " ORDER BY p.nombre ASC, p.apellido ASC, c.id_cliente ASC";

        if ($limite !== null) {
            $sql .= " LIMIT :limite OFFSET :offset";
        }

        $stmt = $this->conn->prepare($sql);

        foreach ($params as $clave => $valor) {
            $stmt->bindValue(':' . $clave, $valor);
        }
        if ($limite !== null) {
            $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CONTAR clientes ACTIVOS (respetando la búsqueda) para calcular las páginas
    public function contar($buscar = '') {
        $params = [];

        $sql = "SELECT COUNT(*)
                FROM cliente c
                JOIN persona p ON c.persona_idpersona = p.id_persona
                WHERE c.estado = 'activo'";

        $sql .= $this->condicionBusqueda($buscar, $params);

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $clave => $valor) {
            $stmt->bindValue(':' . $clave, $valor);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
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