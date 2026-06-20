<?php
session_start();

require_once '../config/database.php';
require_once '../app/ayudantes/Auth.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/ProductController.php';
require_once '../app/controllers/PerfilController.php';
require_once '../app/controllers/UsuarioController.php';
require_once "../app/controllers/DashboardController.php";
require_once "../app/controllers/ClienteController.php";
require_once "../app/controllers/InventarioController.php";
require_once "../app/controllers/VentaController.php";

$auth       = new AuthController();
$product    = new ProductController();
$perfil     = new PerfilController();
$dashboard  = new DashboardController();
$cliente    = new ClienteController();
$inventario = new InventarioController();
$venta      = new VentaController();
$usuarioCtrl = new UsuarioController();

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
    case 'inventario':
        $inventario->index();
        break;
    case 'inventario_editar':
        $inventario->edit();
        break;
    case 'inventario_actualizar':
        $inventario->update();
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
    default:
        echo "ERROR";
}