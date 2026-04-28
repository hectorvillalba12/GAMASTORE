<?php
require_once __DIR__ . '/../models/Venta.php';

class VentaController {

    private $venta;

    public function __construct() {
        $db = (new Database())->connect();
        $this->venta = new venta($db);
    }

    public function index() {
        $ventas = $this->venta->listar();
        require __DIR__ . '/../views/venta/index.php';
    }

    public function create() {
        $clientes  = $this->venta->listarClientes();
        $productos = $this->venta->listarProductos();
        require __DIR__ . '/../views/venta/create.php';
    }

    public function store() {
        $productos_ids     = $_POST['producto_id'] ?? [];
        $cantidades        = $_POST['cantidad'] ?? [];
        $precios_unitarios = $_POST['precio_unitario'] ?? [];

        if (empty($productos_ids)) {
            header("Location: index.php?action=ventas_crear&error=sin_productos");
            exit();
        }

        // Calcular total
        $total = 0;
        foreach ($productos_ids as $i => $pid) {
            $total += $cantidades[$i] * $precios_unitarios[$i];
        }

        $data = [
            'total'                => $total,
            'metodo_de_pago'       => $_POST['metodo_de_pago'],
            'cliente_idcliente'    => $_POST['cliente_idcliente'] ?: null,
            'empleado_id_empleado' => null
        ];

        $id_venta = $this->venta->crear($data);

        foreach ($productos_ids as $i => $pid) {
            $this->venta->crearDetalle(
                $id_venta,
                $pid,
                (int)$cantidades[$i],
                (float)$precios_unitarios[$i]
            );
            $this->venta->descontarStock($pid, (int)$cantidades[$i]);
        }

        header("Location: index.php?action=ventas_ver&id=" . $id_venta . "&ok=1");
        exit();
    }

    public function show() {
        $id      = $_GET['id'];
        $venta   = $this->venta->obtener($id);
        $detalle = $this->venta->obtenerDetalle($id);
        require __DIR__ . '/../views/venta/show.php';
    }
}