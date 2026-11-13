<?php
class Proveedor {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Búsqueda por varias palabras (nombre, teléfono, dirección, email)
    private function condicionBusqueda($buscar, &$params) {
        $sql      = '';
        $terminos = preg_split('/\s+/', trim($buscar), -1, PREG_SPLIT_NO_EMPTY);

        foreach ($terminos as $i => $t) {
            $t = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $t);
            $sql .= " AND CONCAT_WS(' ', nombre, telefono, direccion, email) LIKE :t$i";
            $params["t$i"] = '%' . $t . '%';
        }
        return $sql;
    }

    // LISTAR proveedores ACTIVOS con búsqueda y paginación
    public function listar($buscar = '', $limite = 10, $offset = 0) {
        $params = [];
        $sql = "SELECT * FROM proveedor WHERE activo = 1"
            . $this->condicionBusqueda($buscar, $params)
            . " ORDER BY nombre ASC LIMIT :limite OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contar($buscar = '') {
        $params = [];
        $sql = "SELECT COUNT(*) FROM proveedor WHERE activo = 1"
            . $this->condicionBusqueda($buscar, $params);

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue(':' . $k, $v);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function listarInactivos() {
        return $this->conn->query("SELECT * FROM proveedor WHERE activo = 0 ORDER BY nombre ASC")
                        ->fetchAll(PDO::FETCH_ASSOC);
    }

    // Para los <select> de inventario (entrada / salida)
    public function listarActivos() {
        return $this->conn->query("SELECT id_proveedor, nombre, email FROM proveedor WHERE activo = 1 ORDER BY nombre ASC")
                        ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener($id) {
        $stmt = $this->conn->prepare("SELECT * FROM proveedor WHERE id_proveedor = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($data) {
        $sql = "INSERT INTO proveedor (nombre, telefono, direccion, email, activo)
                VALUES (:nombre, :telefono, :direccion, :email, 1)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'nombre'    => $data['nombre'],
            'telefono'  => $data['telefono'],
            'direccion' => $data['direccion'],
            'email'     => $data['email'],
        ]);
        return $this->conn->lastInsertId();
    }

    public function actualizar($data) {
        $sql = "UPDATE proveedor SET
                    nombre = :nombre, telefono = :telefono,
                    direccion = :direccion, email = :email
                WHERE id_proveedor = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id'        => $data['id_proveedor'],
            'nombre'    => $data['nombre'],
            'telefono'  => $data['telefono'],
            'direccion' => $data['direccion'],
            'email'     => $data['email'],
        ]);
    }

    public function darDeBaja($id) {
        $stmt = $this->conn->prepare("UPDATE proveedor SET activo = 0 WHERE id_proveedor = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function reactivar($id) {
        $stmt = $this->conn->prepare("UPDATE proveedor SET activo = 1 WHERE id_proveedor = :id");
        return $stmt->execute(['id' => $id]);
    }

    // ¿Ya existe otro proveedor con ese email? (excluye el id que se está editando)
    public function existeEmail($email, $excluirId = 0) {
        $stmt = $this->conn->prepare(
            "SELECT COUNT(*) FROM proveedor WHERE email = :email AND id_proveedor != :excluir"
        );
        $stmt->execute(['email' => $email, 'excluir' => $excluirId]);
        return $stmt->fetchColumn() > 0;
    }
}