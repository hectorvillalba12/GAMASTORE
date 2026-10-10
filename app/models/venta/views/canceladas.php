<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ventas Canceladas - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-x-octagon text-danger"></i> Ventas Canceladas</h2>
        <a href="index.php?action=ventas" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver a Ventas
        </a>
    </div>

    <!-- RESUMEN -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-danger text-center">
                <div class="card-body py-3">
                    <div class="text-muted small">Ventas canceladas</div>
                    <div class="fs-3 fw-bold text-danger"><?= $totalCanceladas ?></div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card shadow-sm border-danger text-center">
                <div class="card-body py-3">
                    <div class="text-muted small">Monto total anulado</div>
                    <div class="fs-3 fw-bold text-danger">$<?= number_format($montoCancelado, 2) ?></div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABLA DE VENTAS CANCELADAS -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th class="text-end">Total</th>
                            <th>Método de Pago</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($ventas)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No hay ventas canceladas.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ventas as $v): ?>
                        <tr>
                            <td><?= $v['id_venta'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                            <td><?= htmlspecialchars($v['nombre_cliente'] ?? 'Consumidor final') ?></td>
                            <td class="text-end fw-bold">$<?= number_format($v['total'], 2) ?></td>
                            <td><?= htmlspecialchars($v['metodo_de_pago']) ?></td>
                            <td class="text-center"><span class="badge bg-danger">Cancelada</span></td>
                            <td class="text-center">
                                <a href="index.php?action=ventas_ver&id=<?= $v['id_venta'] ?>"
                                class="btn btn-info btn-sm text-white" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- PAGINACIÓN -->
    <?php if ($totalCanceladas > 0): ?>
        <?php
            $paramsBase = ['action' => 'ventas_canceladas'];

            // Ventana de páginas visibles: 2 antes y 2 después de la actual
            $inicio = max(1, $pagina - 2);
            $fin    = min($totalPaginas, $pagina + 2);
        ?>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 mb-4 gap-2">
            <small class="text-muted">
                Mostrando <?= $desde ?> a <?= $hasta ?> de <?= $totalCanceladas ?> ventas canceladas
            </small>

            <?php if ($totalPaginas > 1): ?>
            <nav aria-label="Paginación de ventas canceladas">
                <ul class="pagination pagination-sm mb-0">

                    <!-- Anterior -->
                    <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="index.php?<?= http_build_query($paramsBase + ['pagina' => $pagina - 1]) ?>">
                            &laquo; Anterior
                        </a>
                    </li>

                    <!-- Primera página + puntos suspensivos -->
                    <?php if ($inicio > 1): ?>
                        <li class="page-item">
                            <a class="page-link"
                            href="index.php?<?= http_build_query($paramsBase + ['pagina' => 1]) ?>">1</a>
                        </li>
                        <?php if ($inicio > 2): ?>
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Páginas del medio -->
                    <?php for ($i = $inicio; $i <= $fin; $i++): ?>
                        <li class="page-item <?= ($i == $pagina) ? 'active' : '' ?>">
                            <a class="page-link"
                            href="index.php?<?= http_build_query($paramsBase + ['pagina' => $i]) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <!-- Última página + puntos suspensivos -->
                    <?php if ($fin < $totalPaginas): ?>
                        <?php if ($fin < $totalPaginas - 1): ?>
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        <?php endif; ?>
                        <li class="page-item">
                            <a class="page-link"
                            href="index.php?<?= http_build_query($paramsBase + ['pagina' => $totalPaginas]) ?>">
                                <?= $totalPaginas ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Siguiente -->
                    <li class="page-item <?= ($pagina >= $totalPaginas) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="index.php?<?= http_build_query($paramsBase + ['pagina' => $pagina + 1]) ?>">
                            Siguiente &raquo;
                        </a>
                    </li>

                </ul>
            </nav>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>