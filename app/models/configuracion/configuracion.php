<?php
class Configuracion {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ===================== MARCAS =====================

    public function listarMarcas() {
        $sql = "SELECT * FROM marca ORDER BY marcas_disponibles ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function existeMarca($nombre) {
        $sql = "SELECT COUNT(*) FROM marca WHERE marcas_disponibles = :nombre";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['nombre' => $nombre]);
        return $stmt->fetchColumn() > 0;
    }

    public function crearMarca($nombre) {
        $sql = "INSERT INTO marca (marcas_disponibles) VALUES (:nombre)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['nombre' => $nombre]);
        return $this->conn->lastInsertId();
    }

    // ===================== CATEGORIAS =====================

    public function listarCategorias() {
        $sql = "SELECT * FROM categoria ORDER BY tipodezapatilla ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function existeCategoria($nombre) {
        $sql = "SELECT COUNT(*) FROM categoria WHERE tipodezapatilla = :nombre";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['nombre' => $nombre]);
        return $stmt->fetchColumn() > 0;
    }

    public function crearCategoria($nombre) {
        $sql = "INSERT INTO categoria (tipodezapatilla) VALUES (:nombre)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['nombre' => $nombre]);
        return $this->conn->lastInsertId();
    }
}