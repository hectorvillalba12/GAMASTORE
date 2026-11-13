<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Productos más vendidos | GamaStore</title>
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
            <h1 class="page-titulo"><i class="bi bi-trophy"></i> Productos más vendidos</h1>
            <p class="page-sub">Ranking por unidades vendidas y facturación.</p>
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
                <input type="hidden" name="action" value="reportes_productos">

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
                    <a href="index.php?action=reportes_excel&tipo=productos&desde=<?= urlencode($_GET['desde'] ?? '') ?>&hasta=<?= urlencode($_GET['hasta'] ?? '') ?>"
                       class="btn btn-ok btn-sm">
                        <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                    </a>
                    <button type="button" class="btn btn-ghost btn-sm" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimir / PDF
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="exportarGraficosPDF('graficos_productos')">
                        <i class="bi bi-file-earmark-pdf"></i> Gráficos a PDF
                    </button>
                    <button type="button" class="btn btn-ok btn-sm" onclick="exportarGraficosExcel('productos', 'graficos_productos')">
                        <i class="bi bi-file-earmark-excel"></i> Gráficos a Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($productos)): ?>
    <div class="panel">
        <div class="panel-head"><span><i class="bi bi-bar-chart"></i> Top productos por unidades vendidas</span></div>
        <div class="chart-box"><canvas id="graficoProductos" height="280"></canvas></div>
    </div>
    <?php endif; ?>

    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-list-ol"></i> Ranking</span>
            <span class="cuenta"><?= count($productos ?? []) ?> productos</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:600px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th class="centro">Unidades vendidas</th>
                        <th class="der">Total facturado</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($productos)): ?>
                    <tr><td colspan="4" class="vacio">No hay ventas en el período seleccionado.</td></tr>
                <?php else: ?>
                    <?php $max = max(array_column($productos, 'unidades_vendidas')) ?: 1; ?>
                    <?php foreach ($productos as $i => $p): ?>
                    <tr>
                        <td class="id"><?= $i + 1 ?></td>
                        <td>
                            <span class="nombre-prod"><?= htmlspecialchars($p['nombre']) ?></span>
                            <div class="barra"><span style="width:<?= round(($p['unidades_vendidas'] / $max) * 100) ?>%"></span></div>
                        </td>
                        <td class="centro"><span class="stock-num"><?= $p['unidades_vendidas'] ?></span></td>
                        <td class="der precio">$<?= number_format($p['total_facturado'], 2, ',', '.') ?></td>
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
const datosProductos = <?= json_encode($productos, JSON_HEX_TAG | JSON_HEX_AMP) ?>;

if (datosProductos.length > 0) {
    new Chart(document.getElementById('graficoProductos'), {
        type: 'bar',
        data: {
            labels: datosProductos.map(p => p.nombre),
            datasets: [{
                label: 'Unidades vendidas',
                data: datosProductos.map(p => parseInt(p.unidades_vendidas)),
                backgroundColor: '#fbbf24',
                borderRadius: 6
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