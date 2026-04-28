<?php
session_start();

require_once '../config/database.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/ProductController.php';
require_once "../app/controllers/DashboardController.php";
require_once "../app/controllers/ClienteController.php";
require_once "../app/controllers/InventarioController.php";
require_once "../app/controllers/VentaController.php";

$auth = new AuthController();
$product = new ProductController();
$dashboard = new DashboardController();
$cliente = new ClienteController();
$inventario = new InventarioController();
$venta = new VentaController();
if (!isset($_GET['action'])) {
    if (isset($_SESSION['user'])) {
        header("Location: index.php?action=dashboard");
    } else {
        header("Location: index.php?action=login");
    }
    exit();
}

$action = $_GET['action'];

switch ($action) {

    //  AUTH
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
    
    // DASHBOARD
    case 'dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;
    // INVENTARIO
    case 'inventario':
        $inventario->index();
        break;

    case 'inventario_crear':
        $inventario->create();
        break;

    case 'inventario_guardar':
        $inventario->store();
        break;

    case 'inventario_editar':
        $inventario->edit();
        break;

    case 'inventario_actualizar':
        $inventario->update();
        break;

    case 'inventario_eliminar':
        $inventario->delete();
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

    case 'excel':
        $product->exportExcel();
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


    default:
        echo "ERROR";
}