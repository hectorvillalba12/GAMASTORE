<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clientes frecuentes | GamaStore</title>
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
            <h1 class="page-titulo"><i class="bi bi-people"></i> Clientes frecuentes</h1>
            <p class="page-sub">Ranking de compras y clientes nuevos del período.</p>
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
                <input type="hidden" name="action" value="reportes_clientes">

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
                    <a href="index.php?action=reportes_excel&tipo=clientes&desde=<?= urlencode($_GET['desde'] ?? '') ?>&hasta=<?= urlencode($_GET['hasta'] ?? '') ?>"
                       class="btn btn-ok btn-sm">
                        <i class="bi bi-file-earmark-excel"></i> Exportar Excel
                    </a>
                    <button type="button" class="btn btn-ghost btn-sm" onclick="window.print()">
                        <i class="bi bi-printer"></i> Imprimir / PDF
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="exportarGraficosPDF('graficos_clientes')">
                        <i class="bi bi-file-earmark-pdf"></i> Gráficos a PDF
                    </button>
                    <button type="button" class="btn btn-ok btn-sm" onclick="exportarGraficosExcel('clientes', 'graficos_clientes')">
                        <i class="bi bi-file-earmark-excel"></i> Gráficos a Excel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Resumen -->
    <div class="kpis">
        <div class="kpi neutra">
            <small>Clientes nuevos en el período</small>
            <b><?= (int) $nuevos ?></b>
        </div>
    </div>

    <?php if (!empty($clientes)): ?>
    <div class="panel">
        <div class="panel-head"><span><i class="bi bi-bar-chart"></i> Top clientes por cantidad de compras</span></div>
        <div class="chart-box"><canvas id="graficoClientes" height="280"></canvas></div>
    </div>
    <?php endif; ?>

    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-list-ol"></i> Ranking de clientes</span>
            <span class="cuenta"><?= count($clientes ?? []) ?> clientes</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:560px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th class="centro">Compras</th>
                        <th class="der">Total comprado</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($clientes)): ?>
                    <tr><td colspan="4" class="vacio">No hay compras en el período seleccionado.</td></tr>
                <?php else: ?>
                    <?php foreach ($clientes as $i => $c): ?>
                    <tr>
                        <td class="id"><?= $i + 1 ?></td>
                        <td>
                            <div class="celda-persona">
                                <div class="avatar-sm"><?= strtoupper(mb_substr($c['nombre_cliente'], 0, 1)) ?></div>
                                <?= htmlspecialchars($c['nombre_cliente']) ?>
                            </div>
                        </td>
                        <td class="centro"><span class="stock-num"><?= $c['cantidad_compras'] ?></span></td>
                        <td class="der precio">$<?= number_format($c['total_comprado'], 2, ',', '.') ?></td>
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
const datosClientes = <?= json_encode($clientes, JSON_HEX_TAG | JSON_HEX_AMP) ?>;

if (datosClientes.length > 0) {
    new Chart(document.getElementById('graficoClientes'), {
        type: 'bar',
        data: {
            labels: datosClientes.map(c => c.nombre_cliente),
            datasets: [{
                label: 'Compras',
                data: datosClientes.map(c => parseInt(c.cantidad_compras)),
                backgroundColor: '#38bdf8',
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