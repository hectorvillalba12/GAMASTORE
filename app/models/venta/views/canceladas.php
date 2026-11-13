<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ventas Canceladas | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap">

    <div class="page-head">
        <div>
            <div class="page-label">Ventas</div>
            <h1 class="page-titulo"><i class="bi bi-x-octagon"></i> Ventas canceladas</h1>
            <p class="page-sub">Historial de ventas anuladas y monto total devuelto.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=ventas" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i> Volver a ventas
            </a>
        </div>
    </div>

    <!-- Resumen -->
    <div class="kpis">
        <div class="kpi">
            <small>Ventas canceladas</small>
            <b><?= (int) $totalCanceladas ?></b>
        </div>
        <div class="kpi">
            <small>Monto total anulado</small>
            <b>$<?= number_format($montoCancelado, 2, ',', '.') ?></b>
        </div>
    </div>

    <!-- Tabla -->
    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-list-ul"></i> Listado</span>
            <span class="cuenta"><?= (int) $totalCanceladas ?> registros</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:820px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th class="der">Total</th>
                        <th>Método de pago</th>
                        <th class="centro">Estado</th>
                        <th class="centro">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($ventas)): ?>
                    <tr>
                        <td colspan="7" class="vacio">No hay ventas canceladas.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($ventas as $v): ?>
                    <tr>
                        <td class="id"><?= $v['id_venta'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                        <td class="nombre-prod"><?= htmlspecialchars($v['nombre_cliente'] ?? 'Consumidor final') ?></td>
                        <td class="der precio">$<?= number_format($v['total'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($v['metodo_de_pago']) ?></td>
                        <td class="centro"><span class="estado bajo">Cancelada</span></td>
                        <td>
                            <div class="acciones-fila">
                                <a href="index.php?action=ventas_ver&id=<?= $v['id_venta'] ?>" class="btn-icon historial" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <?php if ($totalCanceladas > 0): ?>
        <?php
            $paramsBase = ['action' => 'ventas_canceladas'];
            $inicio = max(1, $pagina - 2);
            $fin    = min($totalPaginas, $pagina + 2);
        ?>
        <div class="paginacion">
            <small>Mostrando <?= $desde ?> a <?= $hasta ?> de <?= $totalCanceladas ?> ventas canceladas</small>

            <?php if ($totalPaginas > 1): ?>
            <nav class="pag" aria-label="Paginación de ventas canceladas">
                <a class="<?= ($pagina <= 1) ? 'off' : '' ?>"
                   href="index.php?<?= http_build_query($paramsBase + ['pagina' => $pagina - 1]) ?>">&laquo; Anterior</a>

                <?php if ($inicio > 1): ?>
                    <a href="index.php?<?= http_build_query($paramsBase + ['pagina' => 1]) ?>">1</a>
                    <?php if ($inicio > 2): ?><span>…</span><?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $inicio; $i <= $fin; $i++): ?>
                    <a class="<?= ($i == $pagina) ? 'activa' : '' ?>"
                       href="index.php?<?= http_build_query($paramsBase + ['pagina' => $i]) ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($fin < $totalPaginas): ?>
                    <?php if ($fin < $totalPaginas - 1): ?><span>…</span><?php endif; ?>
                    <a href="index.php?<?= http_build_query($paramsBase + ['pagina' => $totalPaginas]) ?>"><?= $totalPaginas ?></a>
                <?php endif; ?>

                <a class="<?= ($pagina >= $totalPaginas) ? 'off' : '' ?>"
                   href="index.php?<?= http_build_query($paramsBase + ['pagina' => $pagina + 1]) ?>">Siguiente &raquo;</a>
            </nav>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</main>

</body>
</html>