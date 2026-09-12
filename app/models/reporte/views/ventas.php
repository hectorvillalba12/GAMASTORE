<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>@media print { .no-print { display: none !important; } }</style>
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h2><i class="bi bi-cash-coin"></i> Reporte de Ventas</h2>
        <a href="index.php?action=reportes" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver a Reportes
        </a>
    </div>

    <form method="GET" action="index.php" class="row g-2 mb-4 no-print">
        <input type="hidden" name="action" value="reportes_ventas">
        <div class="col-auto">
            <label class="form-label small mb-0">Desde</label>
            <input type="date" name="desde" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['desde'] ?? date('Y-m-d', strtotime('-30 days'))) ?>">
        </div>
        <div class="col-auto">
            <label class="form-label small mb-0">Hasta</label>
            <input type="date" name="hasta" class="form-control form-control-sm" value="<?= htmlspecialchars($_GET['hasta'] ?? date('Y-m-d')) ?>">
        </div>
        <div class="col-auto">
            <label class="form-label small mb-0">Agrupar por</label>
            <select name="agrupacion" class="form-select form-select-sm">
                <option value="dia" <?= ($_GET['agrupacion'] ?? 'dia') === 'dia' ? 'selected' : '' ?>>Día</option>
                <option value="semana" <?= ($_GET['agrupacion'] ?? '') === 'semana' ? 'selected' : '' ?>>Semana</option>
                <option value="mes" <?= ($_GET['agrupacion'] ?? '') === 'mes' ? 'selected' : '' ?>>Mes</option>
            </select>
        </div>
        <div class="col-auto align-self-end">
            <button type="submit" class="btn btn-primary btn-sm">Filtrar</button>
        </div>
        <div class="col-auto align-self-end ms-auto">
            <a href="index.php?action=reportes_excel&tipo=ventas&desde=<?= $_GET['desde'] ?? '' ?>&hasta=<?= $_GET['hasta'] ?? '' ?>&agrupacion=<?= $_GET['agrupacion'] ?? 'dia' ?>"
                class="btn btn-outline-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> Exportar Excel
            </a>
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-printer"></i> Imprimir / PDF
            </button>
            <button onclick="exportarGraficosPDF('graficos_ventas')" type="button" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> Gráficos a PDF
            </button>
            <button onclick="exportarGraficosExcel('ventas', 'graficos_ventas')" type="button" class="btn btn-outline-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> Gráficos a Excel
            </button>
        </div>
    </form>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <div class="text-muted small">Cantidad de ventas</div>
                    <div class="fs-3 fw-bold"><?= (int)$totales['cantidad_ventas'] ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm text-center">
                <div class="card-body">
                    <div class="text-muted small">Total facturado</div>
                    <div class="fs-3 fw-bold text-success">$<?= number_format($totales['total_facturado'], 2) ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-7">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">Evolución de ventas</div>
                <div class="card-body">
                    <canvas id="graficoVentas" height="220"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">Ventas por categoría</div>
                <div class="card-body">
                    <canvas id="graficoCategorias" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">Evolución por período</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr><th>Período</th><th class="text-center">Cantidad de ventas</th><th class="text-end">Total</th></tr>
                </thead>
                <tbody>
                <?php if (empty($ventas)): ?>
                    <tr><td colspan="3" class="text-center text-muted py-3">No hay ventas en el período seleccionado.</td></tr>
                <?php else: ?>
                    <?php $max = max(array_column($ventas, 'total')) ?: 1; ?>
                    <?php foreach ($ventas as $v): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($v['periodo']) ?>
                            <div class="progress mt-1" style="height:6px;">
                                <div class="progress-bar bg-success" style="width: <?= round(($v['total'] / $max) * 100) ?>%;"></div>
                            </div>
                        </td>
                        <td class="text-center"><?= $v['cantidad_ventas'] ?></td>
                        <td class="text-end">$<?= number_format($v['total'], 2) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">Ventas por categoría</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-light">
                    <tr><th>Categoría</th><th class="text-center">Unidades</th><th class="text-end">Total facturado</th></tr>
                </thead>
                <tbody>
                <?php if (empty($porCategoria)): ?>
                    <tr><td colspan="3" class="text-center text-muted py-3">Sin datos en el período.</td></tr>
                <?php else: ?>
                    <?php foreach ($porCategoria as $c): ?>
                    <tr>
                        <td><?= htmlspecialchars($c['categoria'] ?? 'Sin categoría') ?></td>
                        <td class="text-center"><?= $c['unidades_vendidas'] ?></td>
                        <td class="text-end">$<?= number_format($c['total_facturado'], 2) ?></td>
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
const datosVentas = <?= json_encode($ventas) ?>;
const datosCategorias = <?= json_encode($porCategoria) ?>;

if (datosVentas.length > 0) {
    new Chart(document.getElementById('graficoVentas'), {
        type: 'line',
        data: {
            labels: datosVentas.map(v => v.periodo),
            datasets: [{
                label: 'Total facturado ($)',
                data: datosVentas.map(v => parseFloat(v.total)),
                borderColor: '#198754',
                backgroundColor: 'rgba(25,135,84,0.15)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
}

if (datosCategorias.length > 0) {
    new Chart(document.getElementById('graficoCategorias'), {
        type: 'doughnut',
        data: {
            labels: datosCategorias.map(c => c.categoria ?? 'Sin categoría'),
            datasets: [{
                data: datosCategorias.map(c => parseFloat(c.total_facturado)),
                backgroundColor: ['#198754','#ffc107','#0d6efd','#dc3545','#6f42c1','#20c997','#fd7e14']
            }]
        },
        options: { responsive: true }
    });
}
</script>
</body>
</html>