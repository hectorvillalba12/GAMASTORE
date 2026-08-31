<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes y Análisis - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-graph-up"></i> Reportes y Análisis</h2>
        <a href="index.php?action=dashboard" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="row g-4">

        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body p-4">
                    <i class="bi bi-cash-coin fs-1 text-success mb-3"></i>
                    <h5>Ventas por período</h5>
                    <p class="text-muted small">Diarias, semanales o mensuales</p>
                    <a href="index.php?action=reportes_ventas" class="btn btn-success w-100">Ver reporte</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body p-4">
                    <i class="bi bi-trophy fs-1 text-warning mb-3"></i>
                    <h5>Productos más vendidos</h5>
                    <p class="text-muted small">Ranking por unidades y facturación</p>
                    <a href="index.php?action=reportes_productos" class="btn btn-warning w-100">Ver reporte</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body p-4">
                    <i class="bi bi-exclamation-triangle fs-1 text-danger mb-3"></i>
                    <h5>Stock bajo</h5>
                    <p class="text-muted small">Productos bajo el mínimo, ahora mismo</p>
                    <a href="index.php?action=reportes_stock" class="btn btn-danger w-100">Ver reporte</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body p-4">
                    <i class="bi bi-people fs-1 text-primary mb-3"></i>
                    <h5>Clientes frecuentes</h5>
                    <p class="text-muted small">Ranking de compras y clientes nuevos</p>
                    <a href="index.php?action=reportes_clientes" class="btn btn-primary w-100">Ver reporte</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm h-100 text-center">
                <div class="card-body p-4">
                    <i class="bi bi-tags fs-1 text-info mb-3"></i>
                    <h5>Promociones aplicadas</h5>
                    <p class="text-muted small">Uso e impacto de descuentos</p>
                    <a href="index.php?action=reportes_promociones" class="btn btn-info w-100 text-white">Ver reporte</a>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>