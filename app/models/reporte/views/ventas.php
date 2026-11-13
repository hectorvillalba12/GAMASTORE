<?php $agrup = $_GET['agrupacion'] ?? 'dia'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reporte de Ventas | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css?v=<?= @filemtime(__DIR__ . '/../../../../public/css/gamastore.css') ?>" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap">

    <div class="page-head">
        <div>
            <div class="page-label">Reportes</div>
            <h1 class="page-titulo"><i class="bi bi-cash-coin"></i> Reporte de ventas</h1>
            <p class="page-sub">Facturación por período y por categoría.</p>
        </div>
        <div class="page-btns no-print">
            <a href="index.php?action=reportes" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i> Volver a reportes
            </a>
        </div>
    </div>

    <!-- Filtros y exportación -->
    <div class="panel no-print">
        <div class="panel-body">
            <form method="GET" action="index.php" class="filtros">
                <input type="hidden" name="action" value="reportes_ventas">

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

                <div class="filtro">
                    <label for="agrupacion">Agrupar por</label>
                    <div class="input-wrap">
                        <i class="bi bi-calendar3 icono"></i>
                        <select id="agrupacion" name="agrupacion">
                            <option value="dia"    <?= $agrup === 'dia'    ? 'selected' : '' ?>>Día</option>
                            <option value="semana" <?= $agrup === 'semana' ? 'selected' : '' ?>>Semana</option>
                            <option value="mes"    <?= $agrup === 'mes'    ? 'selected' : '' ?>>Mes</option>
                        </select>
                        <i class="bi bi-chevron-down flecha"></i>
                    </div>
                </div>

                <button type="submit" class="btn btn-main"><i class="bi bi-funnel"></i> Filtrar</button>

                <div class="push">
                    <a href="index.php?action=reportes_excel&tipo=ventas&desde=<?= urlencode($_GET['desde'] ?? '') ?>&hasta=<?= urlencode($_GET['hasta'] ?? '') ?>&agrupacion=<?= urlencode($agrup) ?>"
                       class="btn btn-ok btn-sm">
                        <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                    </a>
                    <button type="button" class="btn btn-ghost btn-sm" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimir / PDF
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="exportarGraficosPDF('graficos_ventas')">
                        <i class="bi bi-file-earmark-pdf"></i> Gráficos a PDF
                    </button>
                    <button type="button" class="btn btn-ok btn-sm" onclick="exportarGraficosExcel('ventas', 'graficos_ventas')">
                        <i class="bi bi-file-earmark-excel"></i> Gráficos a Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resumen -->
    <div class="kpis">
        <div class="kpi neutra">
            <small>Cantidad de ventas</small>
            <b><?= (int) $totales['cantidad_ventas'] ?></b>
        </div>
        <div class="kpi verde">
            <small>Total facturado</small>
            <b>$<?= number_format($totales['total_facturado'], 2, ',', '.') ?></b>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="graf-grid asim">
        <div class="panel">
            <div class="panel-head"><span><i class="bi bi-graph-up-arrow"></i> Evolución de ventas</span></div>
            <div class="chart-box"><canvas id="graficoVentas" height="220"></canvas></div>
        </div>
        <div class="panel">
            <div class="panel-head"><span><i class="bi bi-pie-chart"></i> Ventas por categoría</span></div>
            <div class="chart-box"><canvas id="graficoCategorias" height="220"></canvas></div>
        </div>
    </div>

    <!-- Tabla por período -->
    <div class="panel">
        <div class="panel-head"><span><i class="bi bi-calendar3"></i> Evolución por período</span></div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:560px">
                <thead>
                    <tr>
                        <th>Período</th>
                        <th class="centro">Cantidad de ventas</th>
                        <th class="der">Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($ventas)): ?>
                    <tr><td colspan="3" class="vacio">No hay ventas en el período seleccionado.</td></tr>
                <?php else: ?>
                    <?php $max = max(array_column($ventas, 'total')) ?: 1; ?>
                    <?php foreach ($ventas as $v): ?>
                    <tr>
                        <td>
                            <?= htmlspecialchars($v['periodo']) ?>
                            <div class="barra"><span style="width:<?= round(($v['total'] / $max) * 100) ?>%"></span></div>
                        </td>
                        <td class="centro"><?= $v['cantidad_ventas'] ?></td>
                        <td class="der precio">$<?= number_format($v['total'], 2, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tabla por categoría -->
    <div class="panel">
        <div class="panel-head"><span><i class="bi bi-collection"></i> Ventas por categoría</span></div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:560px">
                <thead>
                    <tr>
                        <th>Categoría</th>
                        <th class="centro">Unidades</th>
                        <th class="der">Total facturado</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($porCategoria)): ?>
                    <tr><td colspan="3" class="vacio">Sin datos en el período.</td></tr>
                <?php else: ?>
                    <?php foreach ($porCategoria as $c): ?>
                    <tr>
                        <td class="nombre-prod"><?= htmlspecialchars($c['categoria'] ?? 'Sin categoría') ?></td>
                        <td class="centro"><?= $c['unidades_vendidas'] ?></td>
                        <td class="der precio">$<?= number_format($c['total_facturado'], 2, ',', '.') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="js/graficos-export.js"></script>
<script>
const datosVentas     = <?= json_encode($ventas, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
const datosCategorias = <?= json_encode($porCategoria, JSON_HEX_TAG | JSON_HEX_AMP) ?>;

if (datosVentas.length > 0) {
    new Chart(document.getElementById('graficoVentas'), {
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
    new Chart(document.getElementById('graficoCategorias'), {
        type: 'doughnut',
        data: {
            labels: datosCategorias.map(c => c.categoria ?? 'Sin categoría'),
            datasets: [{
                data: datosCategorias.map(c => parseFloat(c.total_facturado)),
                backgroundColor: GS_PALETA, borderColor: GS_FONDO, borderWidth: 3
            }]
        },
        options: { responsive: true, cutout: '62%', plugins: { legend: { position: 'bottom' } } }
    });
}
</script>

</body>
</html>