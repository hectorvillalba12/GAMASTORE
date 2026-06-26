<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
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
                            <td colspan="9" class="text-center text-muted py-4">No hay productos activos.</td>
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