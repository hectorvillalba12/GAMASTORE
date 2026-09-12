<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos más vendidos - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>@media print { .no-print { display: none !important; } }</style>
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h2><i class="bi bi-trophy"></i> Productos más vendidos</h2>
        <a href="index.php?action=reportes" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver a Reportes
        </a>
    </div>

    <form method="GET" action="index.php" class="row g-2 mb-4 no-print">
        <input type="hidden" name="action" value="reportes_productos">
        <div class="col-auto">
            <label class="form-label small mb-0">Desde</label>
            <input type="date" name="desde" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['desde'] ?? date('Y-m-d', strtotime('-30 days'))) ?>">
        </div>
        <div class="col-auto">
            <label class="form-label small mb-0">Hasta</label>
            <input type="date" name="hasta" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['hasta'] ?? date('Y-m-d')) ?>">
        </div>
        <div class="col-auto align-self-end">
            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
        </div>
        <div class="col-auto align-self-end ms-auto">
            <a href="index.php?action=reportes_excel&tipo=productos&desde=<?= $_GET['desde'] ?? '' ?>&hasta=<?= $_GET['hasta'] ?? '' ?>"
                class="btn btn-outline-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> Exportar Excel
            </a>
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-printer"></i> Imprimir / PDF
            </button>
            <button onclick="exportarGraficosPDF('graficos_productos')" type="button" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> Gráficos a PDF
            </button>
            <button onclick="exportarGraficosExcel('productos', 'graficos_productos')" type="button" class="btn btn-outline-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> Gráficos a Excel
            </button>
        </div>
    </form>

    <?php if (!empty($productos)): ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">Top productos por unidades vendidas</div>
        <div class="card-body">
            <canvas id="graficoProductos" height="280"></canvas>
        </div>
    </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th class="text-center">Unidades vendidas</th>
                        <th class="text-end">Total facturado</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($productos)): ?>
                    <tr><td colspan="4" class="text-center text-muted py-4">No hay ventas en el período seleccionado.</td></tr>
                <?php else: ?>
                    <?php $max = max(array_column($productos, 'unidades_vendidas')) ?: 1; ?>
                    <?php foreach ($productos as $i => $p): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <?= htmlspecialchars($p['nombre']) ?>
                            <div class="progress mt-1" style="height:6px;">
                                <div class="progress-bar bg-warning" style="width: <?= round(($p['unidades_vendidas'] / $max) * 100) ?>%;"></div>
                            </div>
                        </td>
                        <td class="text-center fw-bold"><?= $p['unidades_vendidas'] ?></td>
                        <td class="text-end">$<?= number_format($p['total_facturado'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="js/graficos-export.js"></script>
<script>
const datosProductos = <?= json_encode($productos) ?>;

if (datosProductos.length > 0) {
    new Chart(document.getElementById('graficoProductos'), {
        type: 'bar',
        data: {
            labels: datosProductos.map(p => p.nombre),
            datasets: [{
                label: 'Unidades vendidas',
                data: datosProductos.map(p => parseInt(p.unidades_vendidas)),
                backgroundColor: '#ffc107'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { x: { beginAtZero: true } }
        }
    });
}
</script>
</body>
</html>