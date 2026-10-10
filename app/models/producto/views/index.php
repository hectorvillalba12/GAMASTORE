<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-box-seam"></i> Productos</h2>
        <div>
            <a href="index.php?action=dashboard" class="btn btn-outline-secondary btn-sm me-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="index.php?action=crear" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> Nuevo Producto
            </a>
        </div>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-1"></i> Producto guardado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['baja'])): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="bi bi-box-arrow-down me-1"></i> Producto dado de baja correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['reactivado'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-box-arrow-up me-1"></i> Producto reactivado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    
    <!-- FILTROS: marca y talle -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="index.php" class="row g-3 align-items-end">
                <input type="hidden" name="action" value="productos">

                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-tag"></i> Marca
                    </label>
                    <select name="marca" class="form-select">
                        <option value="">Todas las marcas</option>
                        <?php foreach ($marcas as $m): ?>
                            <option value="<?= $m['id_marca'] ?>"
                                <?= ($filtroMarca == $m['id_marca']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['marcas_disponibles']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-rulers"></i> Talle
                    </label>
                    <select name="talle" class="form-select">
                        <option value="">Todos los talles</option>
                        <?php foreach ($talles as $t): ?>
                            <option value="<?= $t['id_talle'] ?>"
                                <?= ($filtroTalle == $t['id_talle']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['talles_disponibles']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <button type="submit" class="btn btn-dark">
                        <i class="bi bi-funnel"></i> Filtrar
                    </button>
                    <a href="index.php?action=productos" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLA PRODUCTOS ACTIVOS -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-box-seam"></i> Productos Activos
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Marca</th>
                            <th>Talle</th>
                            <th>Color</th>
                            <th>Categoría</th>
                            <th class="text-end">Precio</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($productos)): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <?= (!empty($filtroMarca) || !empty($filtroTalle))
                                    ? 'No se encontraron productos con esos filtros.'
                                    : 'No hay productos activos.' ?>
                            </td>            
                        </tr>
                    <?php else: ?>
                        <?php foreach ($productos as $p): ?>
                        <tr>
                            <td><?= $p['id_producto'] ?></td>
                            <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                            <td><?= htmlspecialchars($p['tipodezapatillas']) ?></td>
                            <td><?= htmlspecialchars($p['marca'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($p['talle'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($p['color'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($p['categoria'] ?? '—') ?></td>
                            <td class="text-end">$<?= number_format($p['precio'], 2, ',', '.') ?></td>
                            <td class="text-center">
                                <a href="index.php?action=editar&id=<?= $p['id_producto'] ?>"
                                class="btn btn-warning btn-sm text-white" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalBajaProducto"
                                    data-id="<?= $p['id_producto'] ?>"
                                    data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                                    title="Dar de baja">
                                    <i class="bi bi-box-arrow-down"></i>
                                </button>
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
    <?php if ($totalProductos > 0): ?>
        <?php
            // Mantiene los filtros activos en los links de cada página
            $paramsBase = ['action' => 'productos'];
            if (!empty($filtroMarca)) $paramsBase['marca'] = $filtroMarca;
            if (!empty($filtroTalle)) $paramsBase['talle'] = $filtroTalle;

            // Ventana de páginas visibles: 2 antes y 2 después de la actual
            $inicio = max(1, $pagina - 2);
            $fin    = min($totalPaginas, $pagina + 2);
        ?>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-2">
            <small class="text-muted">
                Mostrando <?= $desde ?> a <?= $hasta ?> de <?= $totalProductos ?> productos
            </small>

            <?php if ($totalPaginas > 1): ?>
            <nav aria-label="Paginación de productos">
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
    <!-- TABLA PRODUCTOS INACTIVOS (baja lógica) -->
    <?php if (!empty($productosInactivos)): ?>
    <div class="card shadow-sm border-secondary mb-4">
        <div class="card-header bg-secondary text-white">
            <i class="bi bi-box"></i> Productos Inactivos (baja lógica)
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Marca</th>
                            <th>Talle</th>
                            <th>Color</th>
                            <th>Categoría</th>
                            <th class="text-end">Precio</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productosInactivos as $p): ?>
                        <tr class="table-light text-muted">
                            <td><?= $p['id_producto'] ?></td>
                            <td><?= htmlspecialchars($p['nombre']) ?></td>
                            <td><?= htmlspecialchars($p['tipodezapatillas']) ?></td>
                            <td><?= htmlspecialchars($p['marca'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($p['talle'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($p['color'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($p['categoria'] ?? '—') ?></td>
                            <td class="text-end">$<?= number_format($p['precio'], 2, ',', '.') ?></td>
                            <td class="text-center">
                                <a href="index.php?action=reactivar_producto&id=<?= $p['id_producto'] ?>"
                                class="btn btn-success btn-sm" title="Reactivar producto">
                                    <i class="bi bi-box-arrow-up"></i> Reactivar
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- Modal confirmar baja lógica -->
<div class="modal fade" id="modalBajaProducto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-box-arrow-down"></i> Confirmar Baja</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Dar de baja al producto <strong id="nombreProductoBaja"></strong>?</p>
                <p class="text-muted small mb-0">
                    <i class="bi bi-info-circle"></i>
                    El producto <strong>no se elimina</strong> de la base de datos.
                    Queda registrado como inactivo y puede ser reactivado en cualquier momento.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a id="btnConfirmarBajaProducto" href="#" class="btn btn-danger">
                    <i class="bi bi-box-arrow-down"></i> Dar de baja
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('modalBajaProducto').addEventListener('show.bs.modal', function (e) {
    const id     = e.relatedTarget.getAttribute('data-id');
    const nombre = e.relatedTarget.getAttribute('data-nombre');
    document.getElementById('nombreProductoBaja').textContent = nombre;
    document.getElementById('btnConfirmarBajaProducto').href = 'index.php?action=eliminar&id=' + id;
});
</script>
</body>
</html>