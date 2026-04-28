<?php
class venta {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
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
                    CONCAT(p.nombre, ' ', p.apellido) AS nombre_cliente
                FROM venta v
                LEFT JOIN cliente c ON v.cliente_idcliente = c.id_cliente
                LEFT JOIN persona p ON c.persona_idpersona = p.id_persona
                WHERE v.id_venta = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // CREAR una venta y devolver su ID
    public function crear($data) {
        $sql = "INSERT INTO venta (fecha, total, metodo_de_pago, cliente_idcliente, empleado_id_empleado)
                VALUES (NOW(), :total, :metodo_de_pago, :cliente_id, :empleado_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'total'          => $data['total'],
            'metodo_de_pago' => $data['metodo_de_pago'],
            'cliente_id'     => $data['cliente_idcliente'] ?: null,
            'empleado_id'    => $data['empleado_id_empleado'] ?: null
        ]);
        return $this->conn->lastInsertId();
    }

    // INSERTAR detalle de venta
    // Columnas reales: id_detalleventa, IVA, descripcion, precio_producto,
    //                  total_venta, precio_final, venta_idventa, productos_idproducto
    public function crearDetalle($id_venta, $id_producto, $cantidad, $precio_unitario) {
        $iva          = 0.21;
        $subtotal     = $cantidad * $precio_unitario;
        $monto_iva    = round($subtotal * $iva, 2);
        $precio_final = round($subtotal + $monto_iva, 2);

        $sql = "INSERT INTO detalle_venta
                    (IVA, descripcion, precio_producto, total_venta, precio_final,
                    venta_idventa, productos_idproducto)
                VALUES
                    (:iva, :descripcion, :precio_producto, :total_venta, :precio_final,
                    :venta_idventa, :productos_idproducto)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'iva'                  => $iva,
            'descripcion'          => "Producto #{$id_producto} x{$cantidad}",
            'precio_producto'      => $precio_unitario,
            'total_venta'          => $subtotal,
            'precio_final'         => $precio_final,
            'venta_idventa'        => $id_venta,
            'productos_idproducto' => $id_producto
        ]);
    }

    // DESCONTAR stock en inventario
    public function descontarStock($id_producto, $cantidad) {
        $sql = "UPDATE inventario 
                SET stock_actual = stock_actual - :cantidad
                WHERE producto_id_producto = :id_producto AND stock_actual >= :cantidad";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'cantidad'    => $cantidad,
            'id_producto' => $id_producto
        ]);
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
                ORDER BY pr.nombre ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}