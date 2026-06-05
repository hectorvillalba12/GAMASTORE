<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width:500px">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-person-gear"></i> Editar Usuario</h2>
        <a href="index.php?action=usuarios" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="index.php?action=update_usuario">

                <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control"
                        value="<?= htmlspecialchars($usuario['email']) ?>" disabled>
                    <small class="text-muted">El email no se puede modificar.</small>
                </div>

                <div class="mb-3">
                    <label class="form-label">Rol</label>
                    <select name="rol" class="form-select">
                        <option value="admin" <?= $usuario['rol'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                        <option value="vendedor" <?= $usuario['rol'] === 'vendedor' ? 'selected' : '' ?>>Vendedor</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Perfil asignado</label>
                    <select name="perfil_id" class="form-select">
                        <option value="">-- Sin perfil --</option>
                        <?php foreach ($perfiles as $p): ?>
                        <option value="<?= $p['id_perfil'] ?>"
                            <?= $usuario['perfil_id'] == $p['id_perfil'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p['nombre']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-warning w-100 text-white">
                    <i class="bi bi-save"></i> Guardar Cambios
                </button>

            </form>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>