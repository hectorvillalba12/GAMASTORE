<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Promociones - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>@media print { .no-print { display: none !important; } }</style>
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4 no-print">
        <h2><i class="bi bi-tags"></i> Promociones aplicadas</h2>
        <a href="index.php?action=reportes" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver a Reportes
        </a>
    </div>

    <form method="GET" action="index.php" class="row g-2 mb-4 no-print">
        <input type="hidden" name="action" value="reportes_promociones">
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
            <a href="index.php?action=reportes_excel&tipo=promociones&desde=<?= $_GET['desde'] ?? '' ?>&hasta=<?= $_GET['hasta'] ?? '' ?>"
                class="btn btn-outline-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> Exportar Excel
            </a>
            <button onclick="window.print()" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-printer"></i> Imprimir / PDF
            </button>
            <button onclick="exportarGraficosPDF('graficos_promociones')" type="button" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> Gráficos a PDF
            </button>
            <button onclick="exportarGraficosExcel('promociones', 'graficos_promociones')" type="button" class="btn btn-outline-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> Gráficos a Excel
            </button>
        </div>
    </form>

    <?php if (!empty($promociones)): ?>
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white">Descuento otorgado por promoción</div>
        <div class="card-body">
            <canvas id="graficoPromociones" height="280"></canvas>
        </div>
    </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Promoción</th>
                        <th class="text-center">Veces aplicada</th>
                        <th class="text-end">Descuento total otorgado</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($promociones)): ?>
                    <tr><td colspan="3" class="text-center text-muted py-4">Ninguna promoción se aplicó en el período seleccionado.</td></tr>
                <?php else: ?>
                    <?php foreach ($promociones as $p): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                        <td class="text-center"><?= $p['veces_aplicada'] ?></td>
                        <td class="text-end text-danger">-$<?= number_format($p['descuento_total'], 2) ?></td>
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
const datosPromos = <?= json_encode($promociones) ?>;

if (datosPromos.length > 0) {
    new Chart(document.getElementById('graficoPromociones'), {
        type: 'doughnut',
        data: {
            labels: datosPromos.map(p => p.nombre),
            datasets: [{
                data: datosPromos.map(p => parseFloat(p.descuento_total)),
                backgroundColor: ['#0dcaf0','#198754','#ffc107','#dc3545','#6f42c1','#fd7e14']
            }]
        },
        options: { responsive: true }
    });
}
</script>
</body>
</html>