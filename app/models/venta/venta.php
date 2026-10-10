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

    // Arma la condición del filtro por cliente (nombre y/o apellido).
    // Devuelve el SQL para concatenar después de "WHERE 1=1" y completa $params por referencia.
    private function condicionFiltros($filtros, &$params) {
        $sql = '';

        $terminosCliente = preg_split('/\s+/', trim($filtros['cliente'] ?? ''), -1, PREG_SPLIT_NO_EMPTY);
        foreach ($terminosCliente as $i => $t) {
            // Escapa los comodines de LIKE para que % y _ se busquen como texto normal
            $t = str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $t);
            $sql .= " AND CONCAT_WS(' ', p.nombre, p.apellido) LIKE :cli$i";
            $params["cli$i"] = '%' . $t . '%';
        }

        return $sql;
    }

    // LISTAR ventas con filtros opcionales (cliente, producto, método de pago) y paginación
    public function listar($filtros = [], $limite = 10, $offset = 0) {
        $params = [];

        $sql = "SELECT v.*,
                    CONCAT(p.nombre, ' ', p.apellido) AS nombre_cliente,
                    (SELECT GROUP_CONCAT(DISTINCT pr.nombre ORDER BY pr.nombre SEPARATOR ', ')
                        FROM detalle_venta dv
                        JOIN producto pr ON dv.productos_idproducto = pr.id_producto
                        WHERE dv.venta_idventa = v.id_venta) AS productos
                FROM venta v
                LEFT JOIN cliente c ON v.cliente_idcliente = c.id_cliente
                LEFT JOIN persona p ON c.persona_idpersona = p.id_persona
                WHERE 1=1";
        $sql .= $this->condicionFiltros($filtros, $params);
        $sql .= " ORDER BY v.fecha DESC
                LIMIT :limite OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $clave => $valor) {
            $stmt->bindValue(":$clave", $valor);
        }
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CONTAR el total de ventas que cumplen los filtros (para calcular las páginas)
    public function contar($filtros = []) {
        $params = [];

        $sql = "SELECT COUNT(*)
                FROM venta v
                LEFT JOIN cliente c ON v.cliente_idcliente = c.id_cliente
                LEFT JOIN persona p ON c.persona_idpersona = p.id_persona
                WHERE 1=1";
        $sql .= $this->condicionFiltros($filtros, $params);

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $clave => $valor) {
            $stmt->bindValue(":$clave", $valor);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    // LISTAR solo las ventas CANCELADAS (con paginación)
    public function listarCanceladas($limite = 10, $offset = 0) {
        $sql = "SELECT v.*,
                    CONCAT(p.nombre, ' ', p.apellido) AS nombre_cliente
                FROM venta v
                LEFT JOIN cliente c ON v.cliente_idcliente = c.id_cliente
                LEFT JOIN persona p ON c.persona_idpersona = p.id_persona
                WHERE v.estado = 'cancelada'
                ORDER BY v.fecha DESC
                LIMIT :limite OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // RESUMEN de ventas canceladas: cantidad y monto total anulado
    public function resumenCanceladas() {
        $sql = "SELECT COUNT(*) AS cantidad, COALESCE(SUM(total), 0) AS monto
                FROM venta
                WHERE estado = 'cancelada'";
        return $this->conn->query($sql)->fetch(PDO::FETCH_ASSOC);
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
            'total'          => 0, 
            'metodo_de_pago' => $data['metodo_de_pago'],
            'cliente_id'     => $data['cliente_idcliente'] ?: null,
            'empleado_id'    => $data['empleado_id_empleado'] ?: null
        ]);
        return $this->conn->lastInsertId();
    }

    
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
        $descripcion = mb_substr("{$nombre_producto} x{$cantidad}{$descripcion_extra}", 0, 250);

        $sql = "INSERT INTO detalle_venta
                    (IVA, descripcion, cantidad, precio_producto, descuento, total_venta, precio_final,
                    venta_idventa, productos_idproducto, promocion_id_promocion)
                VALUES
                    (:iva, :descripcion, :cantidad, :precio_producto, :descuento, :total_venta, :precio_final,
                    :venta_idventa, :productos_idproducto, :promocion_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'iva'                  => $iva,
            'descripcion'          => $descripcion,
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
        $sql = "SELECT stock_actual FROM inventario WHERE producto_id_producto = :id_producto AND activo = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id_producto' => $id_producto]);
        $fila = $stmt->fetch(PDO::FETCH_ASSOC);
        return $fila && $fila['stock_actual'] >= $cantidad;
    }

    // DESCONTAR stock en inventario y dejar registro en el historial de movimientos
    public function descontarStock($id_producto, $cantidad, $id_venta, $usuario_id = null) {
        $sql = "SELECT id_inventario, stock_actual FROM inventario
                WHERE producto_id_producto = :id_producto AND activo = 1";
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
                    WHERE producto_id_producto = :id_producto
                    ORDER BY activo DESC, id_inventario DESC
                    LIMIT 1";
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

    // LISTAR clientes para el selector
    public function listarClientes() {
        $sql = "SELECT c.id_cliente,
                    CONCAT(p.nombre, ' ', p.apellido) AS nombre_completo,
                    p.dni,
                    p.telefono
                FROM cliente c
                JOIN persona p ON c.persona_idpersona = p.id_persona
                ORDER BY p.nombre ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // LISTAR productos con stock disponible
    public function listarProductos() {
        $sql = "SELECT pr.id_producto, pr.nombre, pr.precio, i.stock_actual,
                    t.talles_disponibles AS talle
                FROM producto pr
                LEFT JOIN inventario i ON i.producto_id_producto = pr.id_producto AND i.activo = 1
                LEFT JOIN talle t ON t.id_talle = pr.talle_id_talle
                WHERE pr.activo = 1
                ORDER BY pr.nombre ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}