<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Usuario | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css?v=<?= @filemtime(__DIR__ . '/../../../../public/css/gamastore.css') ?>" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap estrecho" style="max-width:600px">

    <div class="page-head">
        <div>
            <div class="page-label">Usuarios</div>
            <h1 class="page-titulo"><i class="bi bi-person-gear"></i> Editar usuario</h1>
            <p class="page-sub">Cambiá el rol o el perfil asignado.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=usuarios" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </div>

    <div class="panel">
        <div class="panel-body">
            <form method="POST" action="index.php?action=update_usuario">

                <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">

                <div class="campo" style="margin-bottom:18px">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <i class="bi bi-envelope icono"></i>
                        <input type="text" id="email" value="<?= htmlspecialchars($usuario['email']) ?>" disabled>
                    </div>
                    <small class="ayuda-form">El email no se puede modificar.</small>
                </div>

                <div class="campo" style="margin-bottom:18px">
                    <label for="rol">Rol</label>
                    <div class="input-wrap">
                        <i class="bi bi-person-badge icono"></i>
                        <select id="rol" name="rol">
                            <option value="admin"    <?= $usuario['rol'] === 'admin'    ? 'selected' : '' ?>>Admin</option>
                            <option value="empleado" <?= $usuario['rol'] === 'empleado' ? 'selected' : '' ?>>Empleado</option>
                        </select>
                        <i class="bi bi-chevron-down flecha"></i>
                    </div>
                </div>

                <div class="campo">
                    <label for="perfil_id">Perfil asignado</label>
                    <div class="input-wrap">
                        <i class="bi bi-shield-check icono"></i>
                        <select id="perfil_id" name="perfil_id">
                            <option value="">Sin perfil</option>
                            <?php foreach ($perfiles as $p): ?>
                                <option value="<?= $p['id_perfil'] ?>"
                                    <?= $usuario['perfil_id'] == $p['id_perfil'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($p['nombre']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <i class="bi bi-chevron-down flecha"></i>
                    </div>
                    <small class="ayuda-form">El perfil define a qué módulos puede acceder el usuario.</small>
                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn btn-main"><i class="bi bi-save"></i> Guardar cambios</button>
                    <a href="index.php?action=usuarios" class="btn btn-ghost">Cancelar</a>
                </div>

            </form>
        </div>
    </div>

</main>

</body>
</html>