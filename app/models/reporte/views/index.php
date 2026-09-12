<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes y Análisis - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>@media print { .no-print { display: none !important; } }</style>
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h2><i class="bi bi-graph-up"></i> Reportes y Análisis</h2>
        <a href="index.php?action=dashboard" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <form method="GET" action="index.php" class="row g-2 mb-4 no-print">
        <input type="hidden" name="action" value="reportes">
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
            <button onclick="exportarGraficosPDF('reportes_generales')" type="button" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> Exportar todo a PDF
            </button>
            <button onclick="exportarGraficosExcel('general', 'reportes_generales')" type="button" class="btn btn-outline-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> Exportar todo a Excel
            </button>
        </div>
    </form>

    <h5 class="mb-3">Resumen general</h5>
    <div class="row g-3 mb-5">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">Evolución de ventas</div>
                <div class="card-body"><canvas id="graficoResumenVentas" height="200"></canvas></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">Ventas por categoría</div>
                <div class="card-body"><canvas id="graficoResumenCategorias" height="200"></canvas></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">Top productos vendidos</div>
                <div class="card-body"><canvas id="graficoResumenProductos" height="200"></canvas></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-danger text-white">Stock actual vs. mínimo</div>
                <div class="card-body"><canvas id="graficoResumenStock" height="200"></canvas></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">Top clientes frecuentes</div>
                <div class="card-body"><canvas id="graficoResumenClientes" height="200"></canvas></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-info text-white">Descuento por promoción</div>
                <div class="card-body"><canvas id="graficoResumenPromociones" height="200"></canvas></div>
            </div>
        </div>
    </div>

    <h5 class="mb-3">Reportes detallados</h5>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="js/graficos-export.js"></script>
<script>
const datosVentas       = <?= json_encode($ventas) ?>;
const datosCategorias   = <?= json_encode($porCategoria) ?>;
const datosProductos    = <?= json_encode($productos) ?>;
const datosStock        = <?= json_encode($stock) ?>;
const datosClientes     = <?= json_encode($clientes) ?>;
const datosPromociones  = <?= json_encode($promociones) ?>;

if (datosVentas.length > 0) {
    new Chart(document.getElementById('graficoResumenVentas'), {
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
        options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
}

if (datosCategorias.length > 0) {
    new Chart(document.getElementById('graficoResumenCategorias'), {
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

if (datosProductos.length > 0) {
    new Chart(document.getElementById('graficoResumenProductos'), {
        type: 'bar',
        data: {
            labels: datosProductos.map(p => p.nombre),
            datasets: [{ label: 'Unidades vendidas', data: datosProductos.map(p => parseInt(p.unidades_vendidas)), backgroundColor: '#ffc107' }]
        },
        options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true } } }
    });
}

if (datosStock.length > 0) {
    new Chart(document.getElementById('graficoResumenStock'), {
        type: 'bar',
        data: {
            labels: datosStock.map(p => p.nombre),
            datasets: [
                { label: 'Stock actual', data: datosStock.map(p => parseInt(p.stock_actual)), backgroundColor: '#dc3545' },
                { label: 'Stock mínimo', data: datosStock.map(p => parseInt(p.stock_minimo)), backgroundColor: '#adb5bd' }
            ]
        },
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
}

if (datosClientes.length > 0) {
    new Chart(document.getElementById('graficoResumenClientes'), {
        type: 'bar',
        data: {
            labels: datosClientes.map(c => c.nombre_cliente),
            datasets: [{ label: 'Compras', data: datosClientes.map(c => parseInt(c.cantidad_compras)), backgroundColor: '#0d6efd' }]
        },
        options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true } } }
    });
}

if (datosPromociones.length > 0) {
    new Chart(document.getElementById('graficoResumenPromociones'), {
        type: 'doughnut',
        data: {
            labels: datosPromociones.map(p => p.nombre),
            datasets: [{ data: datosPromociones.map(p => parseFloat(p.descuento_total)), backgroundColor: ['#0dcaf0','#198754','#ffc107','#dc3545','#6f42c1','#fd7e14'] }]
        },
        options: { responsive: true }
    });
}
</script>
</body>
</html>