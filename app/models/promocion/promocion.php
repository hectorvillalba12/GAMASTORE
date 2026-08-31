<?php
class Promocion {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // LISTAR con estado calculado al vuelo (no se guarda en la base)
    public function listar() {
        $sql = "SELECT *,
                CASE
                    WHEN activa = 0 THEN 'inactiva'
                    WHEN NOW() < fecha_inicio THEN 'proxima'
                    WHEN NOW() > fecha_fin THEN 'vencida'
                    ELSE 'vigente'
                END AS estado
                FROM promocion
                ORDER BY fecha_inicio DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obtener($id) {
        $sql = "SELECT * FROM promocion WHERE id_promocion = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function crear($data) {
        $sql = "INSERT INTO promocion
                    (nombre, descripcion, tipo_descuento, descuento_porcentaje, monto_fijo, fecha_inicio, fecha_fin, activa)
                VALUES
                    (:nombre, :descripcion, :tipo, :porcentaje, :monto_fijo, :fecha_inicio, :fecha_fin, 1)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            'nombre'       => $data['nombre'],
            'descripcion'  => $data['descripcion'],
            'tipo'         => $data['tipo_descuento'],
            'porcentaje'   => $data['tipo_descuento'] === 'porcentaje' ? $data['valor'] : null,
            'monto_fijo'   => $data['tipo_descuento'] === 'monto_fijo' ? $data['valor'] : null,
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_fin'    => $data['fecha_fin'],
        ]);
        return $this->conn->lastInsertId();
    }

    public function actualizar($data) {
        $sql = "UPDATE promocion SET
                    nombre = :nombre,
                    descripcion = :descripcion,
                    tipo_descuento = :tipo,
                    descuento_porcentaje = :porcentaje,
                    monto_fijo = :monto_fijo,
                    fecha_inicio = :fecha_inicio,
                    fecha_fin = :fecha_fin
                WHERE id_promocion = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            'id'           => $data['id'],
            'nombre'       => $data['nombre'],
            'descripcion'  => $data['descripcion'],
            'tipo'         => $data['tipo_descuento'],
            'porcentaje'   => $data['tipo_descuento'] === 'porcentaje' ? $data['valor'] : null,
            'monto_fijo'   => $data['tipo_descuento'] === 'monto_fijo' ? $data['valor'] : null,
            'fecha_inicio' => $data['fecha_inicio'],
            'fecha_fin'    => $data['fecha_fin'],
        ]);
    }

    public function darDeBaja($id) {
        $sql = "UPDATE promocion SET activa = 0 WHERE id_promocion = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function reactivar($id) {
        $sql = "UPDATE promocion SET activa = 1 WHERE id_promocion = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    // ÁMBITO: asigna la promoción a una lista puntual de productos
    // (borra las asignaciones previas y carga las nuevas)
    public function asignarProductos($id_promocion, array $productos_ids) {
        $del = $this->conn->prepare("DELETE FROM productos_has_promocion WHERE promocion_id_promocion = :id");
        $del->execute(['id' => $id_promocion]);

        if (empty($productos_ids)) return;

        $sql  = "INSERT INTO productos_has_promocion (productos_id_producto, promocion_id_promocion) VALUES (:producto, :promocion)";
        $stmt = $this->conn->prepare($sql);
        foreach ($productos_ids as $id_producto) {
            $stmt->execute(['producto' => $id_producto, 'promocion' => $id_promocion]);
        }
    }

    // ÁMBITO: asigna la promoción a TODOS los productos activos de una categoría
    public function asignarProductosPorCategoria($id_promocion, $id_categoria) {
        $sql  = "SELECT id_producto FROM producto WHERE categoria_id_categoria = :cat AND activo = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['cat' => $id_categoria]);
        $ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $this->asignarProductos($id_promocion, $ids);
    }

    public function obtenerProductosAsignados($id_promocion) {
        $sql = "SELECT p.id_producto, p.nombre, p.precio
                FROM productos_has_promocion pp
                JOIN producto p ON pp.productos_id_producto = p.id_producto
                WHERE pp.promocion_id_promocion = :id
                ORDER BY p.nombre ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id_promocion]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Clientes puntuales a los que aplica (opcional; si no hay ninguno cargado, aplica a cualquier cliente)
    public function asignarClientes($id_promocion, array $clientes_ids) {
        $del = $this->conn->prepare("DELETE FROM cliente_has_promocion WHERE promocion_id_promocion = :id");
        $del->execute(['id' => $id_promocion]);

        if (empty($clientes_ids)) return;

        $sql  = "INSERT INTO cliente_has_promocion (cliente_id_cliente, promocion_id_promocion) VALUES (:cliente, :promocion)";
        $stmt = $this->conn->prepare($sql);
        foreach ($clientes_ids as $id_cliente) {
            $stmt->execute(['cliente' => $id_cliente, 'promocion' => $id_promocion]);
        }
    }

    public function obtenerClientesAsignados($id_promocion) {
        $sql = "SELECT c.id_cliente, CONCAT(p.nombre, ' ', p.apellido) AS nombre_completo
                FROM cliente_has_promocion cp
                JOIN cliente c ON cp.cliente_id_cliente = c.id_cliente
                JOIN persona p ON c.persona_idpersona = p.id_persona
                WHERE cp.promocion_id_promocion = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id_promocion]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca si un producto tiene una promoción VIGENTE en este momento (para usar en Ventas)
    // Si $id_cliente viene cargado, respeta también la restricción por cliente puntual (si la promo tiene alguna)
    public function obtenerPromocionVigenteParaProducto($id_producto, $id_cliente = null) {
        $sql = "SELECT pr.*
                FROM promocion pr
                JOIN productos_has_promocion pp ON pp.promocion_id_promocion = pr.id_promocion
                WHERE pp.productos_id_producto = :producto
                    AND pr.activa = 1
                    AND NOW() BETWEEN pr.fecha_inicio AND pr.fecha_fin
                ORDER BY pr.fecha_inicio DESC
                LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['producto' => $id_producto]);
        $promo = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$promo) return null;

        // Si la promoción tiene clientes puntuales asignados, solo aplica a esos clientes
        $clientesAsignados = $this->obtenerClientesAsignados($promo['id_promocion']);
        if (!empty($clientesAsignados)) {
            $idsPermitidos = array_column($clientesAsignados, 'id_cliente');
            if (!in_array($id_cliente, $idsPermitidos)) {
                return null;
            }
        }

        return $promo;
    }

    // Calcula el monto de descuento sobre un subtotal, según el tipo de promoción
    public function calcularDescuento($subtotal, $promocion) {
        if ($promocion['tipo_descuento'] === 'porcentaje') {
            return round($subtotal * ($promocion['descuento_porcentaje'] / 100), 2);
        }
        // monto_fijo: nunca descuenta más de lo que vale el subtotal
        return min((float)$promocion['monto_fijo'], $subtotal);
    }
}