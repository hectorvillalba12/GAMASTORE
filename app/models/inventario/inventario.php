<?php
class Inventario {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Arma las condiciones del filtro (se usa en listar y contar)
    private function condicionesFiltro($buscar, $idTalle, $estado) {
        $where = " WHERE i.activo = 1";

        if ($buscar !== '') {
            $where .= " AND (p.nombre LIKE :buscar OR p.codigo LIKE :buscar2)";
        }
        if (!empty($idTalle)) {
            $where .= " AND p.talle_id_talle = :talle";
        }
        if ($estado === 'sobre') {
            $where .= " AND i.stock_maximo > 0 AND i.stock_actual >= i.stock_maximo";
        } elseif ($estado === 'bajo') {
            $where .= " AND i.stock_actual <= i.stock_minimo AND NOT (i.stock_maximo > 0 AND i.stock_actual >= i.stock_maximo)";
        } elseif ($estado === 'normal') {
            $where .= " AND i.stock_actual > i.stock_minimo AND NOT (i.stock_maximo > 0 AND i.stock_actual >= i.stock_maximo)";
        }

        return $where;
    }

    private function bindFiltro($stmt, $buscar, $idTalle) {
        if ($buscar !== '') {
            $stmt->bindValue(':buscar', '%' . $buscar . '%');
            $stmt->bindValue(':buscar2', '%' . $buscar . '%');
        }
        if (!empty($idTalle)) {
            $stmt->bindValue(':talle', (int)$idTalle, PDO::PARAM_INT);
        }
    }

    // LISTAR inventario ACTIVO con filtros y paginación
    public function listar($limite = 10, $offset = 0, $buscar = '', $idTalle = null, $estado = '') {
        $sql = "SELECT i.*,
                    p.codigo,
                    p.nombre AS nombre_producto,
                    p.precio,
                    p.tipodezapatillas,
                    t.talles_disponibles AS talle,
                    p.imagen AS imagen
                FROM inventario i
                LEFT JOIN producto p ON i.producto_id_producto = p.id_producto
                LEFT JOIN talle t ON p.talle_id_talle = t.id_talle"
                . $this->condicionesFiltro($buscar, $idTalle, $estado) .
                " ORDER BY p.nombre DESC
                LIMIT :limite OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        $this->bindFiltro($stmt, $buscar, $idTalle);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CONTAR filas de inventario ACTIVAS según el filtro (para calcular las páginas)
    public function contar($buscar = '', $idTalle = null, $estado = '') {
        $sql = "SELECT COUNT(*)
                FROM inventario i
                LEFT JOIN producto p ON i.producto_id_producto = p.id_producto"
                . $this->condicionesFiltro($buscar, $idTalle, $estado);

        $stmt = $this->conn->prepare($sql);
        $this->bindFiltro($stmt, $buscar, $idTalle);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }
    
    // OBTENER una fila de inventario por ID
    public function obtener($id) {
        $sql = "SELECT i.*,
            p.nombre AS nombre_producto,
            p.precio,
            p.tipodezapatillas,
            t.talles_disponibles AS talle
            FROM inventario i
            LEFT JOIN producto p ON i.producto_id_producto = p.id_producto
            LEFT JOIN talle t ON p.talle_id_talle = t.id_talle
            WHERE i.id_inventario = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    // LISTAR filas de inventario INACTIVAS (baja lógica)
    public function listarInactivos() {
        $sql = "SELECT i.*,
                    p.codigo,
                    p.nombre AS nombre_producto,
                    p.tipodezapatillas,
                    t.talles_disponibles AS talle,
                    p.imagen AS imagen
                FROM inventario i
                LEFT JOIN producto p ON i.producto_id_producto = p.id_producto
                LEFT JOIN talle t ON p.talle_id_talle = t.id_talle
                WHERE i.activo = 0
                ORDER BY p.nombre ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    // CANTIDAD de items con stock bajo — para mostrar como aviso en el listado / dashboard
    public function contarStockBajo() {
        $sql = "SELECT COUNT(*) FROM inventario WHERE activo = 1 AND stock_actual <= stock_minimo";
        return $this->conn->query($sql)->fetchColumn();
    }
    
    // CREAR carga manual de inventario (alta inicial de una fila)
    public function crear($data, $usuario_id = null) {
        $sql = "INSERT INTO inventario (stock_actual, ubicacion, stock_maximo, stock_minimo, producto_id_producto)
                VALUES (:stock_actual, :ubicacion, :stock_maximo, :stock_minimo, :producto_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'stock_actual'  => $data['stock_actual'],
            'ubicacion'     => $data['ubicacion'],
            'stock_maximo'  => $data['stock_maximo'],
            'stock_minimo'  => $data['stock_minimo'],
            'producto_id'   => $data['producto_id_producto']
        ]);

        $id_inventario = $this->conn->lastInsertId();

        // Si se cargó stock inicial mayor a 0, queda registrado como entrada
        if ($data['stock_actual'] > 0) {
            $this->registrarMovimiento($id_inventario, 'entrada', $data['stock_actual'], 0, $data['stock_actual'], 'Carga inicial de inventario', $usuario_id);
        }

        return $id_inventario;
    }

    // ACTUALIZAR datos generales (ubicación, mínimos, máximos y stock actual manual)
    // Si el stock_actual cambia respecto al valor anterior, se registra como AJUSTE en el historial
    public function actualizar($data, $usuario_id = null) {
        $anterior = $this->obtener($data['id_inventario']);

        $sql = "UPDATE inventario SET
                    stock_actual = :stock_actual,
                    ubicacion = :ubicacion,
                    stock_maximo = :stock_maximo,
                    stock_minimo = :stock_minimo,
                    producto_id_producto = :producto_id
                WHERE id_inventario = :id";
        $stmt = $this->conn->prepare($sql);
        $resultado = $stmt->execute([
            'id'            => $data['id_inventario'],
            'stock_actual'  => $data['stock_actual'],
            'ubicacion'     => $data['ubicacion'],
            'stock_maximo'  => $data['stock_maximo'],
            'stock_minimo'  => $data['stock_minimo'],
            'producto_id'   => $data['producto_id_producto']
        ]);

        if ($anterior && (int)$anterior['stock_actual'] !== (int)$data['stock_actual']) {
            $diferencia = (int)$data['stock_actual'] - (int)$anterior['stock_actual'];
            $this->registrarMovimiento(
                $data['id_inventario'],
                'ajuste',
                abs($diferencia),
                (int)$anterior['stock_actual'],
                (int)$data['stock_actual'],
                'Ajuste manual desde edición de inventario',
                $usuario_id
            );
        }

        return $resultado;
    }

    // ENTRADA de productos (llegada de mercadería de un proveedor)
    public function entrada($id_inventario, $cantidad, $motivo, $usuario_id = null, $id_proveedor = null) {
        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn->prepare(
                "SELECT stock_actual FROM inventario WHERE id_inventario = :id AND activo = 1 FOR UPDATE"
            );
            $stmt->execute(['id' => $id_inventario]);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$fila) {
                $this->conn->rollBack();
                return false;
            }

            $stock_anterior = (int)$fila['stock_actual'];
            $stock_nuevo    = $stock_anterior + $cantidad;

            $upd = $this->conn->prepare("UPDATE inventario SET stock_actual = :n WHERE id_inventario = :id");
            $upd->execute(['n' => $stock_nuevo, 'id' => $id_inventario]);

            $this->registrarMovimiento($id_inventario, 'entrada', $cantidad, $stock_anterior, $stock_nuevo, $motivo, $usuario_id, $id_proveedor);

            $this->conn->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            return false;
        }
    }

    // SALIDA manual de productos (dañado, devolución al proveedor, etc.)
    public function salida($id_inventario, $cantidad, $motivo, $usuario_id = null, $id_proveedor = null) {
        $this->conn->beginTransaction();
        try {
            $stmt = $this->conn->prepare(
                "SELECT stock_actual FROM inventario WHERE id_inventario = :id AND activo = 1 FOR UPDATE"
            );
            $stmt->execute(['id' => $id_inventario]);
            $fila = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$fila) {
                $this->conn->rollBack();
                return ['ok' => false, 'error' => 'no_existe'];
            }

            $stock_anterior = (int)$fila['stock_actual'];

            if ($cantidad > $stock_anterior) {
                $this->conn->rollBack();
                return ['ok' => false, 'error' => 'stock_insuficiente'];
            }

            $stock_nuevo = $stock_anterior - $cantidad;

            $upd = $this->conn->prepare("UPDATE inventario SET stock_actual = :n WHERE id_inventario = :id");
            $upd->execute(['n' => $stock_nuevo, 'id' => $id_inventario]);

            $this->registrarMovimiento($id_inventario, 'salida', $cantidad, $stock_anterior, $stock_nuevo, $motivo, $usuario_id, $id_proveedor);

            $this->conn->commit();
            return ['ok' => true, 'stock_nuevo' => $stock_nuevo];
        } catch (Throwable $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            return ['ok' => false, 'error' => 'no_existe'];
        }
    }
    // Proveedores que hicieron al menos una ENTRADA de esta fila de inventario
    public function proveedoresConEntrada($id_inventario) {
        $sql = "SELECT DISTINCT pv.id_proveedor, pv.nombre, pv.email
                FROM movimiento_inventario m
                INNER JOIN proveedor pv ON m.proveedor_id_proveedor = pv.id_proveedor
                WHERE m.inventario_id_inventario = :id
                AND m.tipo = 'entrada'
                AND pv.activo = 1
                ORDER BY pv.nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id_inventario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ¿Este proveedor hizo una entrada de esta fila de inventario?
    public function proveedorTuvoEntrada($id_inventario, $id_proveedor) {
        $sql = "SELECT COUNT(*)
                FROM movimiento_inventario
                WHERE inventario_id_inventario = :inv
                AND proveedor_id_proveedor = :prov
                AND tipo = 'entrada'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['inv' => $id_inventario, 'prov' => $id_proveedor]);
        return $stmt->fetchColumn() > 0;
    }
    // REGISTRAR un movimiento en el historial (proveedor opcional)
    public function registrarMovimiento($id_inventario, $tipo, $cantidad, $stock_anterior, $stock_nuevo, $motivo, $usuario_id = null, $id_proveedor = null) {
        $sql = "INSERT INTO movimiento_inventario
                (inventario_id_inventario, tipo, cantidad, stock_anterior, stock_nuevo, motivo, usuario_id_usuario, proveedor_id_proveedor, fecha)
                VALUES
                (:inventario_id, :tipo, :cantidad, :stock_anterior, :stock_nuevo, :motivo, :usuario_id, :proveedor_id, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'inventario_id'  => $id_inventario,
            'tipo'           => $tipo,
            'cantidad'       => $cantidad,
            'stock_anterior' => $stock_anterior,
            'stock_nuevo'    => $stock_nuevo,
            'motivo'         => $motivo,
            'usuario_id'     => $usuario_id,
            'proveedor_id'   => $id_proveedor
        ]);
    }

    // LISTAR historial de movimientos (ahora con el proveedor)
    public function listarMovimientos($id_inventario) {
        $sql = "SELECT m.*, u.email AS usuario_email, pv.nombre AS proveedor_nombre
                FROM movimiento_inventario m
                LEFT JOIN usuario u    ON m.usuario_id_usuario = u.id_usuario
                LEFT JOIN proveedor pv ON m.proveedor_id_proveedor = pv.id_proveedor
                WHERE m.inventario_id_inventario = :id
                ORDER BY m.fecha DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id_inventario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // LISTAR todos los productos para el select
    // Productos activos que todavía NO tienen fila de inventario activa
    public function productosDisponibles() {
            $sql = "SELECT p.*, t.talles_disponibles AS talle
                    FROM producto p
                    LEFT JOIN talle t ON p.talle_id_talle = t.id_talle
                    WHERE p.activo = 1
                        AND NOT EXISTS (
                            SELECT 1 FROM inventario i
                            WHERE i.producto_id_producto = p.id_producto AND i.activo = 1
                        )
                    ORDER BY p.nombre ASC";
            return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        }
    // BAJA LÓGICA — pone activo = 0 en lugar de borrar (el historial de movimientos se conserva)
    public function darDeBaja($id_inventario) {
        $sql  = "UPDATE inventario SET activo = 0 WHERE id_inventario = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id_inventario]);
    }

    // REACTIVAR — pone activo = 1
    public function reactivar($id_inventario) {
        $sql  = "UPDATE inventario SET activo = 1 WHERE id_inventario = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id_inventario]);
    }

    // ¿El producto ya tiene OTRA fila de inventario activa? (evita duplicados al reactivar)
    public function productoTieneFilaActiva($id_producto, $excluir_id) {
        $sql  = "SELECT COUNT(*) FROM inventario
                WHERE producto_id_producto = :producto AND activo = 1 AND id_inventario != :excluir";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['producto' => $id_producto, 'excluir' => $excluir_id]);
        return $stmt->fetchColumn() > 0;
    }
}