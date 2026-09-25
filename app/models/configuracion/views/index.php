<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuraciones - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-gear-fill"></i> Configuraciones</h2>
        <a href="index.php?action=dashboard" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <?php
    $errores = [
        'marca_vacia'         => 'El nombre de la marca es obligatorio.',
        'marca_duplicada'     => 'Ya existe una marca con ese nombre.',
        'categoria_vacia'     => 'El nombre de la categoría es obligatorio.',
        'categoria_duplicada' => 'Ya existe una categoría con ese nombre.',
    ];
    ?>

    <?php if (isset($_GET['error']) && isset($errores[$_GET['error']])): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= $errores[$_GET['error']] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['ok_marca'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-1"></i> Marca creada correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['ok_categoria'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-1"></i> Categoría creada correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row">

        <!-- ===================== MARCAS ===================== -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-award"></i> Marcas</span>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaMarca">
                        <i class="bi bi-plus-lg"></i> Nueva marca
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($marcas)): ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-4">No hay marcas cargadas.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($marcas as $m): ?>
                                    <tr>
                                        <td><?= $m['id_marca'] ?></td>
                                        <td><?= htmlspecialchars($m['marcas_disponibles']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ===================== CATEGORIAS ===================== -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-tags"></i> Categorías</span>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalNuevaCategoria">
                        <i class="bi bi-plus-lg"></i> Nueva categoría
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Nombre</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (empty($categorias)): ?>
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-4">No hay categorías cargadas.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($categorias as $c): ?>
                                    <tr>
                                        <td><?= $c['id_categoria'] ?></td>
                                        <td><?= htmlspecialchars($c['tipodezapatilla']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- MODAL: Nueva marca -->
<div class="modal fade" id="modalNuevaMarca" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="index.php?action=guardar_marca">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-award"></i> Nueva marca</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Nombre de la marca</label>
                    <input type="text" name="marcas_disponibles" class="form-control"
                        placeholder="Ej: Reebok" required autofocus>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- MODAL: Nueva categoria -->
<div class="modal fade" id="modalNuevaCategoria" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="index.php?action=guardar_categoria">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="bi bi-tags"></i> Nueva categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Nombre de la categoría</label>
                    <input type="text" name="tipodezapatilla" class="form-control"
                        placeholder="Ej: Skate" required autofocus>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Guardar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php if (isset($_GET['error']) && strpos($_GET['error'], 'marca') !== false): ?>
<script>
    window.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('modalNuevaMarca')).show();
    });
</script>
<?php elseif (isset($_GET['error']) && strpos($_GET['error'], 'categoria') !== false): ?>
<script>
    window.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('modalNuevaCategoria')).show();
    });
</script>
<?php endif; ?>

</body>
</html>