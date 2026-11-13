<?php
require_once __DIR__ . '/venta.php';
require_once __DIR__ . '/../promocion/promocion.php';
require_once __DIR__ . '/../caja/caja.php';

class VentaController {

    private $venta;
    private $promocion;
    private $caja;
    private $db;
    

    public function __construct() {
        $this->db        = (new Database())->connect();
        $this->venta      = new venta($this->db);
        $this->promocion  = new Promocion($this->db);
        $this->caja       = new Caja($this->db);   
    }

    private function usuarioActualId() {
        return $_SESSION['usuario']['id_usuario'] ?? null;
    }

    public function index() {
        Auth::verificarModulo('ventas');

        // FILTROS 
        $filtros    = ['cliente' => trim($_GET['cliente'] ?? '')];
        $hayFiltros = ($filtros['cliente'] !== '');

        // PAGINACIÓN
        $porPagina    = 10; 
        $totalVentas  = $this->venta->contar($filtros);
        $totalPaginas = max(1, (int)ceil($totalVentas / $porPagina));

        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        if ($pagina < 1) $pagina = 1;
        if ($pagina > $totalPaginas) $pagina = $totalPaginas;

        $offset = ($pagina - 1) * $porPagina;

        $desde = $totalVentas > 0 ? $offset + 1 : 0;
        $hasta = min($offset + $porPagina, $totalVentas);

        $ventas = $this->venta->listar($filtros, $porPagina, $offset);

        require __DIR__ . '/views/index.php';
    }
    // REGISTRO de ventas canceladas
    public function canceladas() {
        Auth::verificarModulo('ventas');

        $resumen         = $this->venta->resumenCanceladas();
        $totalCanceladas = (int)$resumen['cantidad'];
        $montoCancelado  = (float)$resumen['monto'];

        // PAGINACIÓN
        $porPagina    = 3;
        $totalPaginas = max(1, (int)ceil($totalCanceladas / $porPagina));

        $pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
        if ($pagina < 1) $pagina = 1;
        if ($pagina > $totalPaginas) $pagina = $totalPaginas;

        $offset = ($pagina - 1) * $porPagina;

        $desde = $totalCanceladas > 0 ? $offset + 1 : 0;
        $hasta = min($offset + $porPagina, $totalCanceladas);

        $ventas = $this->venta->listarCanceladas($porPagina, $offset);

        require __DIR__ . '/views/canceladas.php';
    }

    public function create() {
        Auth::verificarModulo('ventas');
        if (!$this->caja->obtenerAbierta()) {
            header("Location: index.php?action=caja&error=caja_requerida");
            exit();
        }
        $clientes  = $this->venta->listarClientes();
        $productos = $this->venta->listarProductos();

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

        if (!$this->caja->obtenerAbierta()) {
            header("Location: index.php?action=caja&error=caja_requerida");
            exit();
        }

        $productos_ids     = $_POST['producto_id'] ?? [];
        $cantidades        = $_POST['cantidad'] ?? [];
        $precios_unitarios = $_POST['precio_unitario'] ?? [];
        $cliente_id        = (int)($_POST['cliente_idcliente'] ?? 0);
        $aplicar_promo     = $_POST['aplicar_promo'] ?? []; // '1' o '0' por cada línea, alineado por índice
        
        // El cliente es obligatorio y tiene que estar registrado y activo
        if ($cliente_id <= 0) {
            header("Location: index.php?action=ventas_crear&error=cliente_requerido");
            exit();
        }
        if (!$this->venta->existeClienteActivo($cliente_id)) {
            header("Location: index.php?action=ventas_crear&error=cliente_invalido");
            exit();
        }
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
            // Lock compartido: si se está cerrando la caja, esta venta espera o se rechaza
            $caja = $this->caja->obtenerAbierta(true);
            if (!$caja) {
                throw new Exception('caja_cerrada');
            }
            $data['caja_id'] = $caja['id_caja'];

            $id_venta = $this->venta->crear($data);
            $total    = 0;

            foreach ($productos_ids as $i => $pid) {
                $cantidad        = (int)$cantidades[$i];
                $precio_unitario = (float)$precios_unitarios[$i];
                $nombre_producto = $_POST['nombre_producto'][$i] ?? "Producto #{$pid}";

                $quierePromo = ($aplicar_promo[$i] ?? '0') === '1';
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
            if ($e->getMessage() === 'caja_cerrada') {
                header("Location: index.php?action=caja&error=caja_requerida");
                exit();
            }
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
        // No se cancelan ventas de una caja que ya fue cerrada (desfasaría el arqueo)
        if (!empty($venta['caja_id']) && $this->caja->estaCerrada($venta['caja_id'])) {
            header("Location: index.php?action=ventas_ver&id={$id}&error=caja_cerrada");
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