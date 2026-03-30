<?php
session_start();

require_once '../config/database.php';
require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/ProductController.php';

$auth = new AuthController();
$product = new ProductController();

$action = $_GET['action'] ?? 'login';

switch ($action) {

    // 🔐 AUTH
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


    // 📦 PRODUCTOS
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

    default:
        echo "404";
}