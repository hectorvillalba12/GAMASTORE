<?php
require_once __DIR__ . '/venta.php';
require_once __DIR__ . '/../promocion/promocion.php';

class VentaController {

    private $venta;
    private $promocion;
    private $db;

    public function __construct() {
        $this->db        = (new Database())->connect();
        $this->venta      = new venta($this->db);
        $this->promocion  = new Promocion($this->db);
    }

    private function usuarioActualId() {
        return $_SESSION['usuario']['id_usuario'] ?? null;
    }

    public function index() {
        Auth::verificarModulo('ventas');
        $ventas = $this->venta->listar();
        require __DIR__ . '/views/index.php';
    }

    public function create() {
        Auth::verificarModulo('ventas');
        $clientes  = $this->venta->listarClientes();
        $productos = $this->venta->listarProductos();

        // Se arma un mapa id_producto => promoción vigente (sin filtrar por cliente todavía,
        // porque el cliente recién se elige en el formulario). Sirve para mostrar un aviso visual.
        $promosPorProducto = [];
        foreach ($productos as $p) {
            $promo = $this->promocion->obtenerPromocionVigenteParaProducto($p['id_producto']);
            if ($promo) {
                $promosPorProducto[$p['id_producto']] = $promo;
            }
        }

        require __DIR__ . '/views/create.php';
    }

    public function store() {
        Auth::verificarModulo('ventas');

        $productos_ids     = $_POST['producto_id'] ?? [];
        $cantidades        = $_POST['cantidad'] ?? [];
        $precios_unitarios = $_POST['precio_unitario'] ?? [];
        $cliente_id        = $_POST['cliente_idcliente'] ?: null;
        $aplicar_promo     = $_POST['aplicar_promo'] ?? []; // '1' o '0' por cada línea, alineado por índice

        if (empty($productos_ids)) {
            header("Location: index.php?action=ventas_crear&error=sin_productos");
            exit();
        }

        foreach ($productos_ids as $i => $pid) {
            if (!$this->venta->hayStockSuficiente($pid, (int)$cantidades[$i])) {
                header("Location: index.php?action=ventas_crear&error=stock_insuficiente");
                exit();
            }
        }

        $usuario_id  = $this->usuarioActualId();
        $id_empleado = $this->venta->obtenerEmpleadoPorUsuario($usuario_id);

        $data = [
            'metodo_de_pago'       => $_POST['metodo_de_pago'],
            'cliente_idcliente'    => $cliente_id,
            'empleado_id_empleado' => $id_empleado
        ];

        $this->db->beginTransaction();

        try {
            $id_venta = $this->venta->crear($data);
            $total    = 0;

            foreach ($productos_ids as $i => $pid) {
                $cantidad        = (int)$cantidades[$i];
                $precio_unitario = (float)$precios_unitarios[$i];
                $nombre_producto = $_POST['nombre_producto'][$i] ?? "Producto #{$pid}";

                // Se busca si el producto tiene una promoción vigente, respetando la
                // restricción por cliente si la promoción la tiene, y solo si el vendedor
                // no la desactivó manualmente para esta línea (checkbox "Aplicar promoción").
                $quierePromo = ($aplicar_promo[$i] ?? '1') !== '0';
                $promo = $quierePromo ? $this->promocion->obtenerPromocionVigenteParaProducto($pid, $cliente_id) : null;
                $promoParaDetalle = null;

                if ($promo) {
                    $subtotal = $cantidad * $precio_unitario;
                    $promoParaDetalle = [
                    'id_promocion'     => $promo['id_promocion'],
                    'nombre'           => $promo['nombre'],
                    'monto_descuento'  => $this->promocion->calcularDescuento($subtotal, $promo)
                    ];
                }

                $total += $this->venta->crearDetalle($id_venta, $pid, $nombre_producto, $cantidad, $precio_unitario, $promoParaDetalle);

                if (!$this->venta->descontarStock($pid, $cantidad, $id_venta, $usuario_id)) {
                    throw new Exception('stock_insuficiente');
                }
            }

            $this->venta->actualizarTotal($id_venta, $total);
            $this->db->commit();

        } catch (Exception $e) {
            $this->db->rollBack();
            header("Location: index.php?action=ventas_crear&error=error_al_guardar");
            exit();
        }

        header("Location: index.php?action=ventas_ver&id=" . $id_venta . "&ok=1");
        exit();
    }

    public function show() {
        Auth::verificarModulo('ventas');
        $id      = $_GET['id'];
        $venta   = $this->venta->obtener($id);
        $detalle = $this->venta->obtenerDetalle($id);
        require __DIR__ . '/views/show.php';
    }
    
    public function cancelar() {
        Auth::verificarModulo('ventas');
        $id    = $_GET['id'];
        $venta = $this->venta->obtener($id);

        if (!$venta) {
            header("Location: index.php?action=ventas");
            exit();
        }

        if ($venta['estado'] === 'cancelada') {
            header("Location: index.php?action=ventas_ver&id={$id}&error=ya_cancelada");
            exit();
        }

        $this->db->beginTransaction();

        try {
            $this->venta->restaurarStockPorVenta($id, $this->usuarioActualId());
            $this->venta->cancelar($id);
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            header("Location: index.php?action=ventas_ver&id={$id}&error=error_al_cancelar");
            exit();
        }

        header("Location: index.php?action=ventas_ver&id={$id}&cancelada=1");
        exit();
    }


}