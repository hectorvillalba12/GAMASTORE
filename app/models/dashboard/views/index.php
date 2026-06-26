<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Control - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">
            <i class="bi bi-shop"></i> GamaStore
        </h1>
        <a href="index.php?action=logout" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-box-arrow-right"></i> Cerrar sesión
        </a>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'acceso_denegado'): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-lock-fill me-1"></i> No tenés permisos para acceder a esa sección.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'sin_perfil'): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-1"></i> Tu usuario no tiene un perfil asignado. Contactá al administrador.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php
    // Obtener módulos del perfil del usuario logueado
    $modulosUsuario = [];
    $perfil_id = $_SESSION['usuario']['perfil_id'] ?? null;
    if ($perfil_id) {
        $db   = (new Database())->connect();
        $stmt = $db->prepare("SELECT modulo FROM perfil_has_modulo WHERE perfil_id = :perfil_id");
        $stmt->execute(['perfil_id' => $perfil_id]);
        $modulosUsuario = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'modulo');
    }
    ?>

    <div class="row g-4">

        <!-- CLIENTES -->
        <?php if (in_array('clientes', $modulosUsuario)): ?>
        <div class="col-md-4">
            <div class="card shadow text-center h-100">
                <div class="card-body p-4">
                    <i class="bi bi-people-fill fs-1 text-primary mb-3"></i>
                    <h4>Clientes</h4>
                    <p class="text-muted">Gestionar clientes de GamaStore</p>
                    <a href="index.php?action=clientes" class="btn btn-primary w-100">Ir a Clientes</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- PRODUCTOS -->
        <?php if (in_array('productos', $modulosUsuario)): ?>
        <div class="col-md-4">
            <div class="card shadow text-center h-100">
                <div class="card-body p-4">
                    <i class="bi bi-box-seam fs-1 text-success mb-3"></i>
                    <h4>Productos</h4>
                    <p class="text-muted">Gestionar productos de GamaStore</p>
                    <a href="index.php?action=productos" class="btn btn-success w-100">Ir a Productos</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- INVENTARIO -->
        <?php if (in_array('inventario', $modulosUsuario)): ?>
        <div class="col-md-4">
            <div class="card shadow text-center h-100">
                <div class="card-body p-4">
                    <i class="bi bi-boxes fs-1 text-warning mb-3"></i>
                    <h4>Inventario</h4>
                    <p class="text-muted">Gestionar stock y ubicaciones</p>
                    <a href="index.php?action=inventario" class="btn btn-warning w-100">Ir a Inventario</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- VENTAS -->
        <?php if (in_array('ventas', $modulosUsuario)): ?>
        <div class="col-md-4">
            <div class="card shadow text-center h-100">
                <div class="card-body p-4">
                    <i class="bi bi-cart-check fs-1 text-info mb-3"></i>
                    <h4>Ventas</h4>
                    <p class="text-muted">Ventas de GamaStore</p>
                    <a href="index.php?action=ventas" class="btn btn-info w-100 text-white">Ir a Ventas</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- USUARIOS -->
        <?php if (in_array('usuarios', $modulosUsuario)): ?>
        <div class="col-md-4">
            <div class="card shadow text-center h-100">
                <div class="card-body p-4">
                    <i class="bi bi-person-gear fs-1 text-secondary mb-3"></i>
                    <h4>Usuarios</h4>
                    <p class="text-muted">Gestionar usuarios y perfiles asignados</p>
                    <a href="index.php?action=usuarios" class="btn btn-secondary w-100">Ir a Usuarios</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- PERFILES -->
        <?php if (in_array('perfiles', $modulosUsuario)): ?>
        <div class="col-md-4">
            <div class="card shadow text-center h-100">
                <div class="card-body p-4">
                    <i class="bi bi-person-badge fs-1 text-danger mb-3"></i>
                    <h4>Perfiles</h4>
                    <p class="text-muted">Gestionar perfiles y módulos asignados</p>
                    <a href="index.php?action=perfiles" class="btn btn-danger w-100">Ir a Perfiles</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

    </div><!-- fin .row -->

</div><!-- fin .container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>