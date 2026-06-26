<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5" style="max-width:600px">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-person-badge"></i> Editar Perfil</h2>
        <a href="index.php?action=perfiles" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'nombre_requerido'): ?>
        <div class="alert alert-danger">El nombre del perfil es obligatorio.</div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="POST" action="index.php?action=update_perfil">

                <input type="hidden" name="id_perfil" value="<?= $perfil['id_perfil'] ?>">

                <div class="mb-3">
                    <label class="form-label">Nombre del perfil <span class="text-danger">*</span></label>
                    <input type="text" name="nombre" class="form-control"
                        value="<?= htmlspecialchars($perfil['nombre']) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="descripcion" class="form-control"
                        value="<?= htmlspecialchars($perfil['descripcion'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Módulos asignados</label>
                    <div class="border rounded p-3">
                        <?php foreach ($modulos as $m): ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox"
                                name="modulos[]" value="<?= $m ?>" id="mod_<?= $m ?>"
                                <?= in_array($m, $modulosDelPerfil) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="mod_<?= $m ?>">
                                <?= ucfirst($m) ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
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