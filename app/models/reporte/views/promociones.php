<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Promociones aplicadas | GamaStore</title>
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
            <h1 class="page-titulo"><i class="bi bi-tags"></i> Promociones aplicadas</h1>
            <p class="page-sub">Uso e impacto de los descuentos en el período.</p>
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
                <input type="hidden" name="action" value="reportes_promociones">

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
                    <a href="index.php?action=reportes_excel&tipo=promociones&desde=<?= urlencode($_GET['desde'] ?? '') ?>&hasta=<?= urlencode($_GET['hasta'] ?? '') ?>"
                       class="btn btn-ok btn-sm">
                        <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                    </a>
                    <button type="button" class="btn btn-ghost btn-sm" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimir / PDF
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="exportarGraficosPDF('graficos_promociones')">
                        <i class="bi bi-file-earmark-pdf"></i> Gráficos a PDF
                    </button>
                    <button type="button" class="btn btn-ok btn-sm" onclick="exportarGraficosExcel('promociones', 'graficos_promociones')">
                        <i class="bi bi-file-earmark-excel"></i> Gráficos a Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php if (!empty($promociones)): ?>
    <div class="panel">
        <div class="panel-head"><span><i class="bi bi-pie-chart"></i> Descuento otorgado por promoción</span></div>
        <div class="chart-box" style="max-width:560px;margin:0 auto"><canvas id="graficoPromociones" height="280"></canvas></div>
    </div>
    <?php endif; ?>

    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-percent"></i> Detalle por promoción</span>
            <span class="cuenta"><?= count($promociones ?? []) ?> promociones</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:560px">
                <thead>
                    <tr>
                        <th>Promoción</th>
                        <th class="centro">Veces aplicada</th>
                        <th class="der">Descuento total otorgado</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($promociones)): ?>
                    <tr><td colspan="3" class="vacio">Ninguna promoción se aplicó en el período seleccionado.</td></tr>
                <?php else: ?>
                    <?php foreach ($promociones as $p): ?>
                    <tr>
                        <td class="nombre-prod"><?= htmlspecialchars($p['nombre']) ?></td>
                        <td class="centro"><?= $p['veces_aplicada'] ?></td>
                        <td class="der precio desc-neg">-$<?= number_format($p['descuento_total'], 2, ',', '.') ?></td>
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
const datosPromos = <?= json_encode($promociones, JSON_HEX_TAG | JSON_HEX_AMP) ?>;

if (datosPromos.length > 0) {
    new Chart(document.getElementById('graficoPromociones'), {
        type: 'doughnut',
        data: {
            labels: datosPromos.map(p => p.nombre),
            datasets: [{
                data: datosPromos.map(p => parseFloat(p.descuento_total)),
                backgroundColor: GS_PALETA, borderColor: GS_FONDO, borderWidth: 3
            }]
        },
        options: { responsive: true, cutout: '62%', plugins: { legend: { position: 'bottom' } } }
    });
}
</script>

</body>
</html>