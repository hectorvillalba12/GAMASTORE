<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Reporte de Stock Bajo | GamaStore</title>
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
            <h1 class="page-titulo"><i class="bi bi-exclamation-triangle"></i> Reporte de stock bajo</h1>
            <p class="page-sub">Foto del momento actual: no depende de un rango de fechas.</p>
        </div>
        <div class="page-btns no-print">
            <a href="index.php?action=reportes" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i> Volver a reportes
            </a>
        </div>
    </div>

    <!-- Exportación -->
    <div class="panel no-print">
        <div class="panel-body">
            <div class="filtros">
                <div class="push" style="margin-left:0">
                    <a href="index.php?action=reportes_excel&tipo=stock" class="btn btn-ok btn-sm">
                        <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                    </a>
                    <button type="button" class="btn btn-ghost btn-sm" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimir / PDF
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="exportarGraficosPDF('graficos_stock')">
                        <i class="bi bi-file-earmark-pdf"></i> Gráficos a PDF
                    </button>
                    <button type="button" class="btn btn-ok btn-sm" onclick="exportarGraficosExcel('stock', 'graficos_stock')">
                        <i class="bi bi-file-earmark-excel"></i> Gráficos a Excel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($productos)): ?>
    <div class="panel">
        <div class="panel-head"><span><i class="bi bi-bar-chart"></i> Stock actual vs. mínimo</span></div>
        <div class="chart-box"><canvas id="graficoStock" height="280"></canvas></div>
    </div>
    <?php endif; ?>

    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-boxes"></i> Productos bajo el mínimo</span>
            <span class="cuenta"><?= count($productos ?? []) ?> productos</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:560px">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th class="centro">Stock actual</th>
                        <th class="centro">Stock mínimo</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($productos)): ?>
                    <tr>
                        <td colspan="4" class="vacio">
                            <i class="bi bi-check-circle" style="color:var(--ok)"></i>
                            No hay productos con stock bajo. Todo en orden.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($productos as $p): ?>
                    <tr>
                        <td class="nombre-prod"><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= htmlspecialchars($p['categoria'] ?? '—') ?></td>
                        <td class="centro"><span class="stock-num bajo"><?= $p['stock_actual'] ?></span></td>
                        <td class="centro"><?= $p['stock_minimo'] ?></td>
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
const datosStock = <?= json_encode($productos, JSON_HEX_TAG | JSON_HEX_AMP) ?>;

if (datosStock.length > 0) {
    new Chart(document.getElementById('graficoStock'), {
        type: 'bar',
        data: {
            labels: datosStock.map(p => p.nombre),
            datasets: [
                {
                    label: 'Stock actual',
                    data: datosStock.map(p => parseInt(p.stock_actual)),
                    backgroundColor: '#f87171',
                    borderRadius: 6
                },
                {
                    label: 'Stock mínimo',
                    data: datosStock.map(p => parseInt(p.stock_minimo)),
                    backgroundColor: 'rgba(255,255,255,0.28)',
                    borderRadius: 6
                }
            ]
        },
        options: {
            responsive: true,
            scales: { y: { beginAtZero: true } }
        }
    });
}
</script>

</body>
</html>