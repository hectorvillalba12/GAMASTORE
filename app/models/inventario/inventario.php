<?php
class Inventario {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar() {
        $sql = "SELECT i.*,
                    p.nombre AS nombre_producto,
                    p.precio,
                    p.tipodezapatillas,
                    t.talles_disponibles AS talle,
                    c.colores_disponibles AS color
                FROM inventario i
                LEFT JOIN producto p ON i.producto_id_producto = p.id_producto
                LEFT JOIN talle t ON p.talle_id_talle = t.id_talle
                LEFT JOIN color c ON p.color_id_color = c.id_color
                ORDER BY p.nombre ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener($id) {
        $sql = "SELECT i.*,
            p.nombre AS nombre_producto,
            p.precio,
            p.tipodezapatillas
            FROM inventario i
            LEFT JOIN producto p ON i.producto_id_producto = p.id_producto
            WHERE i.id_inventario = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CANTIDAD de items con stock bajo — para mostrar como aviso en el listado / dashboard
    public function contarStockBajo() {
        $sql = "SELECT COUNT(*) FROM inventario WHERE stock_actual <= stock_minimo";
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

    // ENTRADA de productos (ej: llegada de mercadería del proveedor)
    public function entrada($id_inventario, $cantidad, $motivo, $usuario_id = null) {
        $item = $this->obtener($id_inventario);
        if (!$item) return false;

        $stock_anterior = (int)$item['stock_actual'];
        $stock_nuevo    = $stock_anterior + $cantidad;

        $sql  = "UPDATE inventario SET stock_actual = :stock_nuevo WHERE id_inventario = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['stock_nuevo' => $stock_nuevo, 'id' => $id_inventario]);

        $this->registrarMovimiento($id_inventario, 'entrada', $cantidad, $stock_anterior, $stock_nuevo, $motivo, $usuario_id);

        return true;
    }

    // SALIDA manual de productos (ej: producto dañado, devolución al proveedor, etc.)
    // No confundir con el descuento automático que hace una venta
    public function salida($id_inventario, $cantidad, $motivo, $usuario_id = null) {
        $item = $this->obtener($id_inventario);
        if (!$item) return ['ok' => false, 'error' => 'no_existe'];

        $stock_anterior = (int)$item['stock_actual'];

        if ($cantidad > $stock_anterior) {
            return ['ok' => false, 'error' => 'stock_insuficiente'];
        }

        $stock_nuevo = $stock_anterior - $cantidad;

        $sql  = "UPDATE inventario SET stock_actual = :stock_nuevo WHERE id_inventario = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['stock_nuevo' => $stock_nuevo, 'id' => $id_inventario]);

        $this->registrarMovimiento($id_inventario, 'salida', $cantidad, $stock_anterior, $stock_nuevo, $motivo, $usuario_id);

        return ['ok' => true];
    }

    // REGISTRAR un movimiento en el historial
    public function registrarMovimiento($id_inventario, $tipo, $cantidad, $stock_anterior, $stock_nuevo, $motivo, $usuario_id = null) {
        $sql = "INSERT INTO movimiento_inventario
                (inventario_id_inventario, tipo, cantidad, stock_anterior, stock_nuevo, motivo, usuario_id_usuario, fecha)
                VALUES
                (:inventario_id, :tipo, :cantidad, :stock_anterior, :stock_nuevo, :motivo, :usuario_id, NOW())";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'inventario_id'  => $id_inventario,
            'tipo'           => $tipo,
            'cantidad'       => $cantidad,
            'stock_anterior' => $stock_anterior,
            'stock_nuevo'    => $stock_nuevo,
            'motivo'         => $motivo,
            'usuario_id'     => $usuario_id
        ]);
    }

    // LISTAR historial de movimientos de una fila de inventario puntual
    public function listarMovimientos($id_inventario) {
        $sql = "SELECT m.*, u.email AS usuario_email
                FROM movimiento_inventario m
                LEFT JOIN usuario u ON m.usuario_id_usuario = u.id_usuario
                WHERE m.inventario_id_inventario = :id
                ORDER BY m.fecha DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id_inventario]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // LISTAR todos los productos para el select
    public function productosDisponibles() {
        $sql = "SELECT * FROM producto WHERE activo = 1 ORDER BY nombre ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}