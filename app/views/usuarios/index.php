<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Usuarios - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-people"></i> Usuarios</h2>
        <a href="index.php?action=dashboard" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-1"></i> Usuario actualizado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['baja'])): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="bi bi-person-dash-fill me-1"></i> Usuario dado de baja correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['reactivado'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-person-check-fill me-1"></i> Usuario reactivado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'unico_admin'): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-shield-lock-fill me-1"></i>
            No podés modificar ni dar de baja a este usuario porque es el <strong>único administrador activo</strong> del sistema.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- USUARIOS ACTIVOS -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-person-check"></i> Usuarios Activos
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Perfil asignado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($usuarios)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No hay usuarios activos.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($usuarios as $u): ?>
                        <?php $esUnicoAdmin = ($u['rol'] === 'admin' && $totalAdminsActivos <= 1); ?>
                        <tr>
                            <td><?= $u['id_usuario'] ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($u['rol']) ?></span></td>
                            <td>
                                <?php if ($u['perfil_nombre']): ?>
                                    <span class="badge bg-success"><?= htmlspecialchars($u['perfil_nombre']) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Sin perfil</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($esUnicoAdmin): ?>
                                    <button class="btn btn-warning btn-sm text-white" disabled title="Único administrador activo">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-danger btn-sm" disabled title="Único administrador activo">
                                        <i class="bi bi-shield-lock"></i>
                                    </button>
                                <?php else: ?>
                                    <a href="index.php?action=edit_usuario&id=<?= $u['id_usuario'] ?>"
                                    class="btn btn-warning btn-sm text-white" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalBajaUsuario"
                                        data-id="<?= $u['id_usuario'] ?>"
                                        data-email="<?= htmlspecialchars($u['email']) ?>"
                                        title="Dar de baja">
                                        <i class="bi bi-person-dash"></i>
                                    </button>
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

    <!-- USUARIOS INACTIVOS -->
    <?php if (!empty($usuariosInactivos)): ?>
    <div class="card shadow-sm border-secondary mb-4">
        <div class="card-header bg-secondary text-white">
            <i class="bi bi-person-x"></i> Usuarios Inactivos (baja lógica)
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Perfil asignado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuariosInactivos as $u): ?>
                        <tr class="table-light text-muted">
                            <td><?= $u['id_usuario'] ?></td>
                            <td><?= htmlspecialchars($u['email']) ?></td>
                            <td><span class="badge bg-secondary"><?= htmlspecialchars($u['rol']) ?></span></td>
                            <td>
                                <?php if ($u['perfil_nombre']): ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($u['perfil_nombre']) ?></span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Sin perfil</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="index.php?action=reactivar_usuario&id=<?= $u['id_usuario'] ?>"
                                class="btn btn-success btn-sm">
                                    <i class="bi bi-person-check"></i> Reactivar
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
<div class="modal fade" id="modalBajaUsuario" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-person-dash"></i> Confirmar Baja</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Dar de baja al usuario <strong id="emailUsuarioBaja"></strong>?</p>
                <p class="text-muted small mb-0">
                    <i class="bi bi-info-circle"></i>
                    El usuario no se elimina de la base de datos y puede ser reactivado en cualquier momento.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a id="btnConfirmarBajaUsuario" href="#" class="btn btn-danger">
                    <i class="bi bi-person-dash"></i> Dar de baja
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('modalBajaUsuario').addEventListener('show.bs.modal', function (e) {
    const id    = e.relatedTarget.getAttribute('data-id');
    const email = e.relatedTarget.getAttribute('data-email');
    document.getElementById('emailUsuarioBaja').textContent = email;
    document.getElementById('btnConfirmarBajaUsuario').href = 'index.php?action=delete_usuario&id=' + id;
});
</script>
</body>
</html>