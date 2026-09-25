<?php

session_start();

ob_start(function ($html) {

    // 1) No tocar descargas (Excel, etc.)
    foreach (headers_list() as $cabecera) {
        if (stripos($cabecera, 'Content-Disposition') === 0) {
            return $html;
        }
    }

    // 2) Solo tocar páginas HTML completas (las que tienen </body>)
    $pos = strripos($html, '</body>');
    if ($pos === false) {
        return $html;
    }

    // 3) Armar lo que se va a inyectar
    $inyeccion = '';

    if (isset($_SESSION['usuario'])) {
        $u = $_SESSION['usuario'];
        $datos = [
            'id'     => $u['id_usuario']    ?? null,
            'email'  => $u['email']         ?? '',
            'rol'    => $u['rol']           ?? '',
            'perfil' => $u['perfil_nombre'] ?? '',
        ];
        $json = json_encode($datos, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE);
        $inyeccion .= "<script>window.GAMASTORE_USUARIO = $json;</script>\n";
    }

    $inyeccion .= '<script src="js/sesion.js"></script>' . "\n";

    // Insertar justo antes de </body>
    return substr($html, 0, $pos) . $inyeccion . substr($html, $pos);
});

require_once '../app/ayudantes/Env.php';
Env::load(__DIR__ . '/../.env');

require_once '../config/database.php';
require_once '../app/ayudantes/Auth.php';
require_once '../app/models/auditoria/AuditoriaController.php';
require_once '../app/models/auth/AuthController.php';
require_once '../app/models/producto/ProductController.php';
require_once '../app/models/perfil/PerfilController.php';
require_once '../app/models/usuario/UsuarioController.php';
require_once "../app/models/dashboard/DashboardController.php";
require_once "../app/models/cliente/ClienteController.php";
require_once "../app/models/inventario/InventarioController.php";
require_once "../app/models/venta/VentaController.php";
require_once "../app/models/promocion/PromocionController.php";
require_once "../app/models/reporte/ReporteController.php";
require_once '../app/models/configuracion/ConfiguracionController.php';

$auth       = new AuthController();
$product    = new ProductController();
$perfil     = new PerfilController();
$dashboard  = new DashboardController();
$cliente    = new ClienteController();
$inventario = new InventarioController();
$venta      = new VentaController();
$usuarioCtrl = new UsuarioController();
$promocion = new PromocionController();
$reporte = new ReporteController();
$auditoriaCtrl = new AuditoriaController();
$configuracion = new ConfiguracionController();

if (!isset($_GET['action'])) {
    if (isset($_SESSION['usuario'])) {
        header("Location: index.php?action=dashboard");
    } else {
        header("Location: index.php?action=login");
    }
    exit();
}

$action = $_GET['action'];


switch ($action) {

    // AUTH
    case 'login':
        $auth->showLogin();
        break;
    case 'loginPost':
        $auth->login();
        break;
    case 'logout':
        $auth->logout();
        break;
    case 'forgot':
        $auth->forgotPassword();
        break;
    case 'sendReset':
        $auth->sendReset();
        break;
    case 'resetForm':
        $auth->resetForm();
        break;
    case 'resetPassword':
        $auth->resetPassword();
        break;

    //  Registro de usuario con confirmación de contraseña
    case 'register':
        $auth->showRegister();
        break;
    case 'registerPost':
        $auth->register();
        break;

    // DASHBOARD
    case 'dashboard':
        $dashboard->index();
        break;

    // INVENTARIO
        // INVENTARIO
    case 'inventario':
        $inventario->index();
        break;
    case 'inventario_editar':
        $inventario->edit();
        break;
    case 'inventario_actualizar':
        $inventario->update();
        break;
    case 'inventario_crear':
        (new InventarioController())->create();
        break;
    case 'inventario_guardar':
        (new InventarioController())->store();
        break;
    case 'inventario_entrada':
        (new InventarioController())->entradaForm();
        break;
    case 'inventario_entrada_guardar':
        (new InventarioController())->entradaGuardar();
        break;
    case 'inventario_salida':
        (new InventarioController())->salidaForm();
        break;
    case 'inventario_salida_guardar':
        (new InventarioController())->salidaGuardar();
        break;
    case 'inventario_historial':
        (new InventarioController())->historial();
        break;
            
    case 'inventario_eliminar':
        (new InventarioController())->delete();
        break;

    // PRODUCTOS
    case 'productos':
        $product->index();
        break;
    case 'crear':
        $product->create();
        break;
    case 'guardar':
        $product->store();
        break;
    case 'editar':
        $product->edit();
        break;
    case 'actualizar':
        $product->update();
        break;
    case 'eliminar':
        $product->delete();
        break;
    case 'reactivar_producto':
        $product->reactivar();
        break;
    case 'excel':
        $product->exportExcel();
        break;
    // CONFIGURACIONES
    case 'configuraciones':
        $configuracion->index();
        break;
    case 'guardar_marca':
        $configuracion->storeMarca();
        break;
    case 'guardar_categoria':
        $configuracion->storeCategoria();
        break;

    // CLIENTES
    case 'clientes':
        $cliente->index();
        break;
    case 'create_cliente':
        $cliente->create();
        break;
    case 'store_cliente':
        $cliente->store();
        break;
    case 'edit_cliente':
        $cliente->edit($_GET['id']);
        break;
    case 'update_cliente':
        $cliente->update();
        break;
    case 'delete_cliente':
        $cliente->delete();
        break;
    case 'reactivar_cliente':
        $cliente->reactivar();
        break;
    case 'verificar_email':
        $cliente->verificarEmail();
        break;

    // VENTAS
    case 'ventas':
        $venta->index();
        break;
    case 'ventas_crear':
        $venta->create();
        break;
    case 'ventas_guardar':
        $venta->store();
        break;
    case 'ventas_ver':
        $venta->show();
        break;

    case 'ventas_cancelar':
        $venta->cancelar();
        break;
    //PERFILES
    case 'perfiles':
        $perfil->index();
        break;
    case 'create_perfil':
        $perfil->create();
        break;
    case 'store_perfil':
        $perfil->store();
        break;
    case 'edit_perfil':
        $perfil->edit();
        break;
    case 'update_perfil':
        $perfil->update();
        break;
    case 'delete_perfil':
        $perfil->delete();
        break;
    case 'reactivar_perfil':
        $perfil->reactivar();
        break;
    //USUARIO
    case 'usuarios':
        $usuarioCtrl->index();
        break;
    case 'edit_usuario':
        $usuarioCtrl->edit();
        break;
    case 'update_usuario':
        $usuarioCtrl->update();
        break;
    case 'delete_usuario':
        $usuarioCtrl->delete();
        break;
    case 'reactivar_usuario':
        $usuarioCtrl->reactivar();
        break;
        // PROMOCIONES
    case 'promociones':
        (new PromocionController())->index();
        break;
    case 'promociones_crear':
        (new PromocionController())->create();
        break;
    case 'promociones_guardar':
        (new PromocionController())->store();
        break;
    case 'promociones_editar':
        (new PromocionController())->edit();
        break;
    case 'promociones_actualizar':
        (new PromocionController())->update();
        break;
    case 'promociones_eliminar':
        (new PromocionController())->delete();
        break;
    case 'promociones_reactivar':
        (new PromocionController())->reactivar();
        break;
        // REPORTES
    case 'reportes':
        (new ReporteController())->index();
        break;
    case 'reportes_ventas':
        (new ReporteController())->ventas();
        break;
    case 'reportes_productos':
        (new ReporteController())->productos();
        break;
    case 'reportes_stock':
        (new ReporteController())->stock();
        break;
    case 'reportes_clientes':
        (new ReporteController())->clientes();
        break;
    case 'reportes_promociones':
        (new ReporteController())->promociones();
        break;
    case 'reportes_excel':
        (new ReporteController())->exportExcel();
        break;
    case 'reportes_graficos_excel':
        (new ReporteController())->exportGraficosExcel();
        break;

      // AUDITORÍA
    case 'auditoria':
        $auditoriaCtrl->index();
        break;
    default:
        echo "ERROR";
}