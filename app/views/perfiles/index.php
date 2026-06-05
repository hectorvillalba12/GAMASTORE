<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfiles - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-person-badge"></i> Perfiles</h2>
        <div>
            <a href="index.php?action=dashboard" class="btn btn-outline-secondary btn-sm me-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="index.php?action=create_perfil" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> Nuevo Perfil
            </a>
        </div>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-1"></i> Perfil guardado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['baja'])): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="bi bi-dash-circle me-1"></i> Perfil dado de baja correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['reactivado'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-1"></i> Perfil reactivado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- PERFILES ACTIVOS -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-person-badge"></i> Perfiles Activos
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Módulos asignados</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($perfiles)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No hay perfiles activos.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($perfiles as $p): ?>
                        <tr>
                            <td><?= $p['id_perfil'] ?></td>
                            <td><strong><?= htmlspecialchars($p['nombre']) ?></strong></td>
                            <td><?= htmlspecialchars($p['descripcion'] ?? '—') ?></td>
                            <td>
                                <?php
                                $db      = (new Database())->connect();
                                $perfil  = new Perfil($db);
                                $modulos = $perfil->obtenerModulos($p['id_perfil']);
                                foreach ($modulos as $m):
                                ?>
                                    <span class="badge bg-primary me-1"><?= ucfirst($m) ?></span>
                                <?php endforeach; ?>
                            </td>
                            <td class="text-center">
                                <a href="index.php?action=edit_perfil&id=<?= $p['id_perfil'] ?>"
                                class="btn btn-warning btn-sm text-white" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalBajaPerfil"
                                    data-id="<?= $p['id_perfil'] ?>"
                                    data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                                    title="Dar de baja">
                                    <i class="bi bi-dash-circle"></i>
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

    <!-- PERFILES INACTIVOS -->
    <?php if (!empty($perfilesInactivos)): ?>
    <div class="card shadow-sm border-secondary mb-4">
        <div class="card-header bg-secondary text-white">
            <i class="bi bi-person-x"></i> Perfiles Inactivos (baja lógica)
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($perfilesInactivos as $p): ?>
                        <tr class="table-light text-muted">
                            <td><?= $p['id_perfil'] ?></td>
                            <td><?= htmlspecialchars($p['nombre']) ?></td>
                            <td><?= htmlspecialchars($p['descripcion'] ?? '—') ?></td>
                            <td class="text-center">
                                <a href="index.php?action=reactivar_perfil&id=<?= $p['id_perfil'] ?>"
                                class="btn btn-success btn-sm">
                                    <i class="bi bi-check-circle"></i> Reactivar
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

<!-- Modal baja lógica -->
<div class="modal fade" id="modalBajaPerfil" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-dash-circle"></i> Confirmar Baja</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Dar de baja al perfil <strong id="nombrePerfilBaja"></strong>?</p>
                <p class="text-muted small mb-0">
                    <i class="bi bi-info-circle"></i>
                    El perfil no se elimina de la base de datos y puede ser reactivado en cualquier momento.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a id="btnConfirmarBajaPerfil" href="#" class="btn btn-danger">
                    <i class="bi bi-dash-circle"></i> Dar de baja
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('modalBajaPerfil').addEventListener('show.bs.modal', function (e) {
    const id     = e.relatedTarget.getAttribute('data-id');
    const nombre = e.relatedTarget.getAttribute('data-nombre');
    document.getElementById('nombrePerfilBaja').textContent = nombre;
    document.getElementById('btnConfirmarBajaPerfil').href = 'index.php?action=delete_perfil&id=' + id;
});
</script>
</body>
</html>