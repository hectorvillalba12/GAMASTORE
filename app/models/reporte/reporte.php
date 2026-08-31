<?php
class Reporte {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // TOTALES generales del período (para el resumen arriba de cada reporte)
    public function totalesVentas($desde, $hasta) {
        $sql = "SELECT COUNT(*) AS cantidad_ventas, COALESCE(SUM(total), 0) AS total_facturado
                FROM venta
                WHERE fecha BETWEEN :desde AND :hasta";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['desde' => $desde . ' 00:00:00', 'hasta' => $hasta . ' 23:59:59']);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // VENTAS agrupadas por día, semana o mes
    public function ventasPorPeriodo($desde, $hasta, $agrupacion = 'dia') {
        $formato = match ($agrupacion) {
            'semana' => "DATE_FORMAT(fecha, '%x-S%v')",
            'mes'    => "DATE_FORMAT(fecha, '%Y-%m')",
            default  => "DATE(fecha)",
        };

        $sql = "SELECT {$formato} AS periodo, COUNT(*) AS cantidad_ventas, SUM(total) AS total
                FROM venta
                WHERE fecha BETWEEN :desde AND :hasta
                GROUP BY periodo
                ORDER BY periodo ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['desde' => $desde . ' 00:00:00', 'hasta' => $hasta . ' 23:59:59']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // PRODUCTOS más vendidos en el período
    public function productosMasVendidos($desde, $hasta, $limite = 10) {
        $sql = "SELECT p.nombre,
                    SUM(dv.cantidad) AS unidades_vendidas,
                    SUM(dv.precio_final) AS total_facturado
                FROM detalle_venta dv
                JOIN venta v ON dv.venta_idventa = v.id_venta
                JOIN producto p ON dv.productos_idproducto = p.id_producto
                WHERE v.fecha BETWEEN :desde AND :hasta
                GROUP BY p.id_producto, p.nombre
                ORDER BY unidades_vendidas DESC
                LIMIT :limite";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':desde', $desde . ' 00:00:00');
        $stmt->bindValue(':hasta', $hasta . ' 23:59:59');
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // VENTAS por categoría en el período
    public function ventasPorCategoria($desde, $hasta) {
        $sql = "SELECT cat.tipodezapatilla AS categoria,
                    SUM(dv.cantidad) AS unidades_vendidas,
                    SUM(dv.precio_final) AS total_facturado
                FROM detalle_venta dv
                JOIN venta v ON dv.venta_idventa = v.id_venta
                JOIN producto p ON dv.productos_idproducto = p.id_producto
                LEFT JOIN categoria cat ON p.categoria_id_categoria = cat.id_categoria
                WHERE v.fecha BETWEEN :desde AND :hasta
                GROUP BY cat.id_categoria, cat.tipodezapatilla
                ORDER BY total_facturado DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['desde' => $desde . ' 00:00:00', 'hasta' => $hasta . ' 23:59:59']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // STOCK bajo (no depende de fechas, es una foto del momento actual)
    public function stockBajo() {
        $sql = "SELECT p.nombre, cat.tipodezapatilla AS categoria,
                    i.stock_actual, i.stock_minimo
                FROM inventario i
                JOIN producto p ON i.producto_id_producto = p.id_producto
                LEFT JOIN categoria cat ON p.categoria_id_categoria = cat.id_categoria
                WHERE i.stock_actual <= i.stock_minimo
                ORDER BY i.stock_actual ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    // CLIENTES frecuentes en el período (más compras / más facturado)
    public function clientesFrecuentes($desde, $hasta, $limite = 10) {
        $sql = "SELECT CONCAT(per.nombre, ' ', per.apellido) AS nombre_cliente,
                    COUNT(v.id_venta) AS cantidad_compras,
                    SUM(v.total) AS total_comprado
                FROM venta v
                JOIN cliente c ON v.cliente_idcliente = c.id_cliente
                JOIN persona per ON c.persona_idpersona = per.id_persona
                WHERE v.fecha BETWEEN :desde AND :hasta
                GROUP BY c.id_cliente, nombre_cliente
                ORDER BY cantidad_compras DESC
                LIMIT :limite";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':desde', $desde . ' 00:00:00');
        $stmt->bindValue(':hasta', $hasta . ' 23:59:59');
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // CLIENTES nuevos (registrados) en el período
    public function clientesNuevos($desde, $hasta) {
        $sql = "SELECT COUNT(*) FROM cliente
                WHERE fecha_registro BETWEEN :desde AND :hasta";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['desde' => $desde . ' 00:00:00', 'hasta' => $hasta . ' 23:59:59']);
        return $stmt->fetchColumn();
    }

    // PROMOCIONES aplicadas en el período
    public function promocionesAplicadas($desde, $hasta) {
        $sql = "SELECT pr.nombre,
                    COUNT(dv.id_detalleventa) AS veces_aplicada,
                    SUM(dv.descuento) AS descuento_total
                FROM detalle_venta dv
                JOIN promocion pr ON dv.promocion_id_promocion = pr.id_promocion
                JOIN venta v ON dv.venta_idventa = v.id_venta
                WHERE v.fecha BETWEEN :desde AND :hasta
                GROUP BY pr.id_promocion, pr.nombre
                ORDER BY veces_aplicada DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['desde' => $desde . ' 00:00:00', 'hasta' => $hasta . ' 23:59:59']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}