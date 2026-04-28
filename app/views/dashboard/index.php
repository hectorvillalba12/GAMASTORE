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

    <h1 class="text-center mb-5">
        <i class="bi bi-shop"></i>  GamaStore
    </h1>

    <div class="row g-4">

        <!-- CLIENTES -->
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

        <!-- PRODUCTOS -->
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

        <!-- INVENTARIO -->
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

        <!-- VENTAS -->
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

    </div><!-- fin .row -->

</div><!-- fin .container -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>