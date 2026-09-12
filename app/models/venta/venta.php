<?php
class venta {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Acceso a la conexión, necesario para que el controller maneje la transacción
    public function getConexion() {
        return $this->conn;
    }

    // LISTAR todas las ventas con cliente
    public function listar() {
        $sql = "SELECT v.*,
                    CONCAT(p.nombre, ' ', p.apellido) AS nombre_cliente
                FROM venta v
                LEFT JOIN cliente c ON v.cliente_idcliente = c.id_cliente
                LEFT JOIN persona p ON c.persona_idpersona = p.id_persona
                ORDER BY v.fecha DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // OBTENER una venta por ID
    public function obtener($id) {
        $sql = "SELECT v.*,
                    CONCAT(p.nombre, ' ', p.apellido) AS nombre_cliente,
                    e.id_empleado, pe.nombre AS nombre_empleado
                FROM venta v
                LEFT JOIN cliente c ON v.cliente_idcliente = c.id_cliente
                LEFT JOIN persona p ON c.persona_idpersona = p.id_persona
                LEFT JOIN empleado e ON v.empleado_id_empleado = e.id_empleado
                LEFT JOIN persona pe ON e.persona_id_persona = pe.id_persona
                WHERE v.id_venta = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Busca el id_empleado correspondiente al usuario logueado
    public function obtenerEmpleadoPorUsuario($usuario_id) {
        if (!$usuario_id) return null;
        $sql = "SELECT id_empleado FROM empleado WHERE usuario_id_usuario = :usuario_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['usuario_id' => $usuario_id]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila ? $fila['id_empleado'] : null;
    }

    // CREAR una venta y devolver su ID. El total se completa después, con updateTotal()
    public function crear($data) {
        $sql = "INSERT INTO venta (fecha, total, metodo_de_pago, cliente_idcliente, empleado_id_empleado)
                VALUES (NOW(), :total, :metodo_de_pago, :cliente_id, :empleado_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'total'          => 0, // se actualiza al final, una vez sumados los detalles con IVA
            'metodo_de_pago' => $data['metodo_de_pago'],
            'cliente_id'     => $data['cliente_idcliente'] ?: null,
            'empleado_id'    => $data['empleado_id_empleado'] ?: null
        ]);
        return $this->conn->lastInsertId();
    }

    // Actualiza el total de la venta una vez que se conocen todos los detalles (con IVA incluido)
    public function actualizarTotal($id_venta, $total) {
        $sql = "UPDATE venta SET total = :total WHERE id_venta = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['total' => $total, 'id' => $id_venta]);
    }

        public function crearDetalle($id_venta, $id_producto, $nombre_producto, $cantidad, $precio_unitario, $promocion = null) {
        $iva      = 0;
        $subtotal = $cantidad * $precio_unitario;
        $descuento = 0;
        $descripcion_extra = '';

        if ($promocion) {
            $descuento = $promocion['monto_descuento'];
            $descripcion_extra = " — Promo: {$promocion['nombre']}";
        }

        $subtotal_con_descuento = $subtotal - $descuento;
        $monto_iva    = round($subtotal_con_descuento * $iva, 2);
        $precio_final = round($subtotal_con_descuento + $monto_iva, 2);

        $sql = "INSERT INTO detalle_venta
                    (IVA, descripcion, cantidad, precio_producto, descuento, total_venta, precio_final,
                    venta_idventa, productos_idproducto, promocion_id_promocion)
                VALUES
                    (:iva, :descripcion, :cantidad, :precio_producto, :descuento, :total_venta, :precio_final,
                    :venta_idventa, :productos_idproducto, :promocion_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'iva'                  => $iva,
            'descripcion'          => "{$nombre_producto} x{$cantidad}{$descripcion_extra}",
            'cantidad'             => $cantidad,
            'precio_producto'      => $precio_unitario,
            'descuento'            => $descuento,
            'total_venta'          => $subtotal,
            'precio_final'         => $precio_final,
            'venta_idventa'        => $id_venta,
            'productos_idproducto' => $id_producto,
            'promocion_id'         => $promocion['id_promocion'] ?? null
        ]);

        return $precio_final;
    }

    // Verifica si hay stock suficiente ANTES de tocar nada (se usa antes de confirmar la venta)
    public function hayStockSuficiente($id_producto, $cantidad) {
        $sql = "SELECT stock_actual FROM inventario WHERE producto_id_producto = :id_producto";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id_producto' => $id_producto]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila && $fila['stock_actual'] >= $cantidad;
    }

    // DESCONTAR stock en inventario y dejar registro en el historial de movimientos
    public function descontarStock($id_producto, $cantidad, $id_venta, $usuario_id = null) {
        $sql = "SELECT id_inventario, stock_actual FROM inventario
                WHERE producto_id_producto = :id_producto";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id_producto' => $id_producto]);
        $inv = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$inv || $inv['stock_actual'] < $cantidad) {
            return false; // sin stock suficiente
        }

        $stock_anterior = (int)$inv['stock_actual'];
        $stock_nuevo    = $stock_anterior - $cantidad;

        $sqlUpdate = "UPDATE inventario SET stock_actual = :stock_nuevo WHERE id_inventario = :id_inventario";
        $stmtUpdate = $this->conn->prepare($sqlUpdate);
        $stmtUpdate->execute(['stock_nuevo' => $stock_nuevo, 'id_inventario' => $inv['id_inventario']]);

        // Queda registrado en el mismo historial que usa el módulo de Inventario
        $sqlMov = "INSERT INTO movimiento_inventario
                        (inventario_id_inventario, tipo, cantidad, stock_anterior, stock_nuevo, motivo, usuario_id_usuario, fecha)
                    VALUES
                        (:inventario_id, 'salida', :cantidad, :stock_anterior, :stock_nuevo, :motivo, :usuario_id, NOW())";
        $stmtMov = $this->conn->prepare($sqlMov);
        $stmtMov->execute([
            'inventario_id'  => $inv['id_inventario'],
            'cantidad'       => $cantidad,
            'stock_anterior' => $stock_anterior,
            'stock_nuevo'    => $stock_nuevo,
            'motivo'         => "Venta #{$id_venta}",
            'usuario_id'     => $usuario_id
        ]);

        return true;
    }
    
    // Restaura el stock de todos los productos de una venta (usado al cancelarla)
    public function restaurarStockPorVenta($id_venta, $usuario_id = null) {
        $detalle = $this->obtenerDetalle($id_venta);

        foreach ($detalle as $d) {
            $sql = "SELECT id_inventario, stock_actual FROM inventario
                    WHERE producto_id_producto = :id_producto";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute(['id_producto' => $d['productos_idproducto']]);
            $inv = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$inv) continue; // si el producto ya no tiene registro de inventario, se omite

            $stock_anterior = (int)$inv['stock_actual'];
            $stock_nuevo    = $stock_anterior + (int)$d['cantidad'];

            $sqlUpdate = "UPDATE inventario SET stock_actual = :stock_nuevo WHERE id_inventario = :id_inventario";
            $stmtUpdate = $this->conn->prepare($sqlUpdate);
            $stmtUpdate->execute(['stock_nuevo' => $stock_nuevo, 'id_inventario' => $inv['id_inventario']]);

        // Mismo historial que usa el módulo de Inventario, ahora como 'entrada'
            $sqlMov = "INSERT INTO movimiento_inventario
                            (inventario_id_inventario, tipo, cantidad, stock_anterior, stock_nuevo, motivo, usuario_id_usuario, fecha)
                        VALUES
                            (:inventario_id, 'entrada', :cantidad, :stock_anterior, :stock_nuevo, :motivo, :usuario_id, NOW())";
            $stmtMov = $this->conn->prepare($sqlMov);
            $stmtMov->execute([
                'inventario_id'  => $inv['id_inventario'],
                'cantidad'       => $d['cantidad'],
                'stock_anterior' => $stock_anterior,
                'stock_nuevo'    => $stock_nuevo,
                'motivo'         => "Cancelación venta #{$id_venta}",
                'usuario_id'     => $usuario_id
            ]);
        }
    }

// Marca una venta como cancelada
    public function cancelar($id_venta) {
        $sql = "UPDATE venta SET estado = 'cancelada' WHERE id_venta = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id_venta]);
    }
    


    // OBTENER detalle de una venta
    public function obtenerDetalle($id_venta) {
        $sql = "SELECT dv.*, pr.nombre AS nombre_producto
                FROM detalle_venta dv
                JOIN producto pr ON dv.productos_idproducto = pr.id_producto
                WHERE dv.venta_idventa = :id_venta";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id_venta' => $id_venta]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // LISTAR clientes para el select
    public function listarClientes() {
        $sql = "SELECT c.id_cliente, CONCAT(p.nombre, ' ', p.apellido) AS nombre_completo
                FROM cliente c
                JOIN persona p ON c.persona_idpersona = p.id_persona
                ORDER BY p.nombre ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // LISTAR productos con stock disponible
    public function listarProductos() {
        $sql = "SELECT pr.id_producto, pr.nombre, pr.precio, i.stock_actual
                FROM producto pr
                LEFT JOIN inventario i ON i.producto_id_producto = pr.id_producto
                WHERE pr.activo = 1
                ORDER BY pr.nombre ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}