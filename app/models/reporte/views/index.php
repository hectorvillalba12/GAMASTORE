<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reportes y Análisis | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css?v=<?= @filemtime(__DIR__ . '/../../../../public/css/gamastore.css') ?>" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap">

    <div class="page-head no-print">
        <div>
            <div class="page-label">Módulo de gestión</div>
            <h1 class="page-titulo"><i class="bi bi-graph-up"></i> Reportes y análisis</h1>
            <p class="page-sub">Ventas, stock, clientes y promociones de GamaStore.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=dashboard" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Filtros -->
    <div class="panel no-print">
        <div class="panel-body">
            <form method="GET" action="index.php" class="filtros">
                <input type="hidden" name="action" value="reportes">

                <div class="filtro">
                    <label for="desde">Desde</label>
                    <div class="input-wrap">
                        <i class="bi bi-calendar-event icono"></i>
                        <input type="date" id="desde" name="desde"
                               value="<?= htmlspecialchars($_GET['desde'] ?? date('Y-m-d', strtotime('-30 days'))) ?>">
                    </div>
                </div>

                <div class="filtro">
                    <label for="hasta">Hasta</label>
                    <div class="input-wrap">
                        <i class="bi bi-calendar-check icono"></i>
                        <input type="date" id="hasta" name="hasta"
                               value="<?= htmlspecialchars($_GET['hasta'] ?? date('Y-m-d')) ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-main"><i class="bi bi-funnel"></i> Filtrar</button>

                <div class="push">
                    <button type="button" class="btn btn-danger" onclick="exportarGraficosPDF('reportes_generales')">
                        <i class="bi bi-file-earmark-pdf"></i> Todo a PDF
                    </button>
                    <button type="button" class="btn btn-ok" onclick="exportarGraficosExcel('general', 'reportes_generales')">
                        <i class="bi bi-file-earmark-excel"></i> Todo a Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resumen general -->
    <h2 class="sec-titulo">Resumen general</h2>
    <div class="graf-grid">
        <div class="panel">
            <div class="panel-head"><span><i class="bi bi-graph-up-arrow"></i> Evolución de ventas</span></div>
            <div class="chart-box"><canvas id="graficoResumenVentas" height="200"></canvas></div>
        </div>
        <div class="panel">
            <div class="panel-head"><span><i class="bi bi-pie-chart"></i> Ventas por categoría</span></div>
            <div class="chart-box"><canvas id="graficoResumenCategorias" height="200"></canvas></div>
        </div>
        <div class="panel">
            <div class="panel-head"><span><i class="bi bi-trophy"></i> Top productos vendidos</span></div>
            <div class="chart-box"><canvas id="graficoResumenProductos" height="200"></canvas></div>
        </div>
        <div class="panel">
            <div class="panel-head"><span><i class="bi bi-exclamation-triangle"></i> Stock actual vs. mínimo</span></div>
            <div class="chart-box"><canvas id="graficoResumenStock" height="200"></canvas></div>
        </div>
        <div class="panel">
            <div class="panel-head"><span><i class="bi bi-people"></i> Top clientes frecuentes</span></div>
            <div class="chart-box"><canvas id="graficoResumenClientes" height="200"></canvas></div>
        </div>
        <div class="panel">
            <div class="panel-head"><span><i class="bi bi-tags"></i> Descuento por promoción</span></div>
            <div class="chart-box"><canvas id="graficoResumenPromociones" height="200"></canvas></div>
        </div>
    </div>

    <!-- Reportes detallados -->
    <div class="no-print">
        <h2 class="sec-titulo">Reportes detallados</h2>
        <div class="rep-grid">

            <a href="index.php?action=reportes_ventas" class="rep-card">
                <div class="rep-icono"><i class="bi bi-cash-coin"></i></div>
                <h3>Ventas por período</h3>
                <p>Diarias, semanales o mensuales.</p>
                <div class="ir">Ver reporte <i class="bi bi-arrow-right"></i></div>
            </a>

            <a href="index.php?action=reportes_productos" class="rep-card">
                <div class="rep-icono"><i class="bi bi-trophy"></i></div>
                <h3>Productos más vendidos</h3>
                <p>Ranking por unidades y facturación.</p>
                <div class="ir">Ver reporte <i class="bi bi-arrow-right"></i></div>
            </a>

            <a href="index.php?action=reportes_stock" class="rep-card">
                <div class="rep-icono rojo"><i class="bi bi-exclamation-triangle"></i></div>
                <h3>Stock bajo</h3>
                <p>Productos bajo el mínimo, ahora mismo.</p>
                <div class="ir">Ver reporte <i class="bi bi-arrow-right"></i></div>
            </a>

            <a href="index.php?action=reportes_clientes" class="rep-card">
                <div class="rep-icono"><i class="bi bi-people"></i></div>
                <h3>Clientes frecuentes</h3>
                <p>Ranking de compras y clientes nuevos.</p>
                <div class="ir">Ver reporte <i class="bi bi-arrow-right"></i></div>
            </a>

            <a href="index.php?action=reportes_promociones" class="rep-card">
                <div class="rep-icono"><i class="bi bi-tags"></i></div>
                <h3>Promociones aplicadas</h3>
                <p>Uso e impacto de descuentos.</p>
                <div class="ir">Ver reporte <i class="bi bi-arrow-right"></i></div>
            </a>

        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="js/graficos-export.js"></script>
<script>
const datosVentas       = <?= json_encode($ventas, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
const datosCategorias   = <?= json_encode($porCategoria, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
const datosProductos    = <?= json_encode($productos, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
const datosStock        = <?= json_encode($stock, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
const datosClientes     = <?= json_encode($clientes, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
const datosPromociones  = <?= json_encode($promociones, JSON_HEX_TAG | JSON_HEX_AMP) ?>;

const dona = { responsive: true, cutout: '62%', plugins: { legend: { position: 'bottom' } } };

if (datosVentas.length > 0) {
    new Chart(document.getElementById('graficoResumenVentas'), {
        type: 'line',
        data: {
            labels: datosVentas.map(v => v.periodo),
            datasets: [{
                label: 'Total facturado ($)',
                data: datosVentas.map(v => parseFloat(v.total)),
                borderColor: '#c084fc',
                backgroundColor: 'rgba(168,85,247,0.18)',
                pointBackgroundColor: '#c084fc',
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
                backgroundColor: GS_PALETA, borderColor: GS_FONDO, borderWidth: 3
            }]
        },
        options: dona
    });
}

if (datosProductos.length > 0) {
    new Chart(document.getElementById('graficoResumenProductos'), {
        type: 'bar',
        data: {
            labels: datosProductos.map(p => p.nombre),
            datasets: [{ label: 'Unidades vendidas', data: datosProductos.map(p => parseInt(p.unidades_vendidas)), backgroundColor: '#fbbf24', borderRadius: 6 }]
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
                { label: 'Stock actual', data: datosStock.map(p => parseInt(p.stock_actual)), backgroundColor: '#f87171', borderRadius: 6 },
                { label: 'Stock mínimo', data: datosStock.map(p => parseInt(p.stock_minimo)), backgroundColor: 'rgba(255,255,255,0.28)', borderRadius: 6 }
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
            datasets: [{ label: 'Compras', data: datosClientes.map(c => parseInt(c.cantidad_compras)), backgroundColor: '#38bdf8', borderRadius: 6 }]
        },
        options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } }, scales: { x: { beginAtZero: true } } }
    });
}

if (datosPromociones.length > 0) {
    new Chart(document.getElementById('graficoResumenPromociones'), {
        type: 'doughnut',
        data: {
            labels: datosPromociones.map(p => p.nombre),
            datasets: [{
                data: datosPromociones.map(p => parseFloat(p.descuento_total)),
                backgroundColor: GS_PALETA, borderColor: GS_FONDO, borderWidth: 3
            }]
        },
        options: dona
    });
}
</script>

</body>
</html>