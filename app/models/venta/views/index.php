<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Ventas - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-cart-check"></i> Ventas</h2>
        <div>
            <a href="index.php?action=dashboard" class="btn btn-outline-secondary btn-sm me-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="index.php?action=ventas_canceladas" class="btn btn-outline-danger btn-sm me-2">
                <i class="bi bi-x-octagon"></i> Ventas canceladas
            </a>
            <a href="index.php?action=ventas_crear" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> Nueva Venta
            </a>
        </div>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            Venta registrada con éxito.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <!-- FILTRO POR CLIENTE -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="index.php" class="row g-2 align-items-end">
                <input type="hidden" name="action" value="ventas">

                <div class="col-12 col-md-8">
                    <label class="form-label fw-semibold" for="cliente">
                        <i class="bi bi-search"></i> Buscar cliente
                    </label>
                    <input type="text" id="cliente" name="cliente" class="form-control"
                        placeholder="Nombre o apellido..." autocomplete="off"
                        value="<?= htmlspecialchars($filtros['cliente']) ?>">
                </div>

                <div class="col-12 col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                    <?php if ($hayFiltros): ?>
                        <a href="index.php?action=ventas" class="btn btn-outline-secondary flex-fill">
                            <i class="bi bi-x-lg"></i> Limpiar
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Productos</th>
                            <th class="text-end">Total</th>
                            <th>Método de Pago</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($ventas)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <?= $hayFiltros ? 'No se encontraron ventas con esos filtros.' : 'No hay ventas registradas.' ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($ventas as $v): ?>
                        <tr>
                            <td><?= $v['id_venta'] ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                            <td><?= htmlspecialchars($v['nombre_cliente'] ?? 'Consumidor final') ?></td>
                            <td><?= htmlspecialchars($v['productos'] ?? '—') ?></td>
                            <td class="text-end fw-bold">$<?= number_format($v['total'], 2) ?></td>
                            <td><?= htmlspecialchars($v['metodo_de_pago']) ?></td>
                            <td class="text-center">
                                <?php if ($v['estado'] === 'cancelada'): ?>
                                    <span class="badge bg-danger">Cancelada</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Completada</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="index.php?action=ventas_ver&id=<?= $v['id_venta'] ?>"
                                class="btn btn-info btn-sm text-white" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if ($v['estado'] !== 'cancelada'): ?>
                                <a href="index.php?action=ventas_cancelar&id=<?= $v['id_venta'] ?>"
                                class="btn btn-danger btn-sm" title="Cancelar venta"
                                onclick="return confirm('¿Seguro que querés cancelar esta venta? Se devolverá el stock de los productos.');">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                                <?php endif; ?>
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
    <?php if ($totalVentas > 0): ?>
        <?php
            // Conserva los filtros activos al cambiar de página
            $paramsBase = ['action' => 'ventas'] + array_filter($filtros, function ($v) { return $v !== ''; });

            // Ventana de páginas visibles: 2 antes y 2 después de la actual
            $inicio = max(1, $pagina - 2);
            $fin    = min($totalPaginas, $pagina + 2);
        ?>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 mb-4 gap-2">
            <small class="text-muted">
                Mostrando <?= $desde ?> a <?= $hasta ?> de <?= $totalVentas ?> ventas
            </small>

            <?php if ($totalPaginas > 1): ?>
            <nav aria-label="Paginación de ventas">
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