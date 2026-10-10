<?php
class Product {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // LISTAR productos ACTIVOS con filtros opcionales y paginación
    public function listar($idMarca = null, $idTalle = null, $limite = 10, $offset = 0) {
        $sql = "SELECT p.*,
                    t.talles_disponibles AS talle,
                    m.marcas_disponibles AS marca,
                    c.colores_disponibles AS color,
                    cat.tipodezapatilla AS categoria
                FROM producto p
                LEFT JOIN talle t ON p.talle_id_talle = t.id_talle
                LEFT JOIN marca m ON p.marca_id_marca = m.id_marca
                LEFT JOIN color c ON p.color_id_color = c.id_color
                LEFT JOIN categoria cat ON p.categoria_id_categoria = cat.id_categoria
                WHERE p.activo = 1";

        if (!empty($idMarca)) {
            $sql .= " AND p.marca_id_marca = :marca";
        }
        if (!empty($idTalle)) {
            $sql .= " AND p.talle_id_talle = :talle";
        }

        $sql .= " ORDER BY p.nombre ASC LIMIT :limite OFFSET :offset";

        $stmt = $this->conn->prepare($sql);

        if (!empty($idMarca)) {
            $stmt->bindValue(':marca', $idMarca, PDO::PARAM_INT);
        }
        if (!empty($idTalle)) {
            $stmt->bindValue(':talle', $idTalle, PDO::PARAM_INT);
        }
        
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CONTAR productos ACTIVOS  para calcular las páginas
    public function contarActivos($idMarca = null, $idTalle = null) {
        $sql = "SELECT COUNT(*) FROM producto p WHERE p.activo = 1";

        if (!empty($idMarca)) {
            $sql .= " AND p.marca_id_marca = :marca";
        }
        if (!empty($idTalle)) {
            $sql .= " AND p.talle_id_talle = :talle";
        }

        $stmt = $this->conn->prepare($sql);

        if (!empty($idMarca)) {
            $stmt->bindValue(':marca', $idMarca, PDO::PARAM_INT);
        }
        if (!empty($idTalle)) {
            $stmt->bindValue(':talle', $idTalle, PDO::PARAM_INT);
        }

        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
    // LISTAR solo productos INACTIVOS (baja lógica)
    public function listarInactivos() {
        $sql = "SELECT p.*,
                    t.talles_disponibles AS talle,
                    m.marcas_disponibles AS marca,
                    c.colores_disponibles AS color,
                    cat.tipodezapatilla AS categoria
                FROM producto p
                LEFT JOIN talle t ON p.talle_id_talle = t.id_talle
                LEFT JOIN marca m ON p.marca_id_marca = m.id_marca
                LEFT JOIN color c ON p.color_id_color = c.id_color
                LEFT JOIN categoria cat ON p.categoria_id_categoria = cat.id_categoria
                WHERE p.activo = 0
                ORDER BY p.nombre ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // VALIDAR DUPLICADOS — mismo nombre + talle + marca + color
    public function existeDuplicado($nombre, $talle, $marca, $color, $excluir_id = null) {
        $sql = "SELECT COUNT(*) FROM producto
                WHERE nombre = :nombre
                AND talle_id_talle = :talle
                AND marca_id_marca = :marca
                AND color_id_color = :color";

        if ($excluir_id) {
            $sql .= " AND id_producto != :excluir_id";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nombre', $nombre);
        $stmt->bindValue(':talle', $talle);
        $stmt->bindValue(':marca', $marca);
        $stmt->bindValue(':color', $color);

        if ($excluir_id) {
            $stmt->bindValue(':excluir_id', $excluir_id);
        }

        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    // CREAR — retorna el id para crear inventario automáticamente
    public function crear($data) {
        $sql = "INSERT INTO producto
                (nombre, descripcion, tipodezapatillas, precio, talle_id_talle, marca_id_marca, color_id_color, categoria_id_categoria, activo)
                VALUES
                (:nombre, :descripcion, :tipo, :precio, :talle, :marca, :color, :categoria, 1)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'nombre'      => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'tipo'        => $data['tipodezapatillas'],
            'precio'      => $data['precio'],
            'talle'       => $data['talle'],
            'marca'       => $data['marca'],
            'color'       => $data['color'],
            'categoria'   => $data['categoria']
        ]);

        return $this->conn->lastInsertId();
    }

    // OBTENER
    public function obtener($id) {
        $sql = "SELECT * FROM producto WHERE id_producto = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ACTUALIZAR
    public function actualizar($data) {
        $sql = "UPDATE producto SET
                    nombre                 = :nombre,
                    descripcion            = :descripcion,
                    tipodezapatillas       = :tipo,
                    precio                 = :precio,
                    talle_id_talle         = :talle,
                    marca_id_marca         = :marca,
                    color_id_color         = :color,
                    categoria_id_categoria = :categoria
                WHERE id_producto = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id'          => $data['id'],
            'nombre'      => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'tipo'        => $data['tipo'],
            'precio'      => $data['precio'],
            'talle'       => $data['talle'],
            'marca'       => $data['marca'],
            'color'       => $data['color'],
            'categoria'   => $data['categoria']
        ]);
    }

    // BAJA LÓGICA — pone activo = 0 en lugar de borrar
    public function darDeBaja($id) {
        $sql = "UPDATE producto SET activo = 0 WHERE id_producto = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // REACTIVAR — pone activo = 1
    public function reactivar($id) {
        $sql = "UPDATE producto SET activo = 1 WHERE id_producto = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }
}