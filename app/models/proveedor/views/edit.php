<?php
$errores = [
    'nombre_requerido'    => 'El nombre del proveedor es obligatorio.',
    'telefono_invalido'   => 'Ingresá un teléfono válido (solo números, +, -, espacios y paréntesis).',
    'direccion_requerida' => 'La dirección es obligatoria.',
    'email_invalido'      => 'Ingresá un correo electrónico válido.',
    'email_duplicado'     => 'Ya existe otro proveedor registrado con ese correo.',
];
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Proveedor | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap estrecho">

    <div class="page-head">
        <div>
            <div class="page-label">Proveedores</div>
            <h1 class="page-titulo"><i class="bi bi-pencil-square"></i> Editar proveedor</h1>
            <p class="page-sub"><strong><?= htmlspecialchars($item['nombre']) ?></strong></p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=proveedores" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </div>

    <?php if (isset($errores[$error])): ?>
        <div class="alerta danger">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= $errores[$error] ?>
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <div class="panel">
        <div class="panel-body">
            <form method="POST" action="index.php?action=proveedores_actualizar">

                <input type="hidden" name="id_proveedor" value="<?= $item['id_proveedor'] ?>">

                <div class="form-grid">
                    <div class="campo col-3">
                        <label for="nombre">Nombre</label>
                        <div class="input-wrap">
                            <i class="bi bi-building icono"></i>
                            <input type="text" id="nombre" name="nombre" maxlength="100" required
                                value="<?= htmlspecialchars($item['nombre']) ?>">
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="telefono">Teléfono</label>
                        <div class="input-wrap">
                            <i class="bi bi-telephone icono"></i>
                            <input type="text" id="telefono" name="telefono" maxlength="30" required
                                value="<?= htmlspecialchars($item['telefono'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="direccion">Dirección</label>
                        <div class="input-wrap">
                            <i class="bi bi-geo-alt icono"></i>
                            <input type="text" id="direccion" name="direccion" maxlength="150" required
                                value="<?= htmlspecialchars($item['direccion'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="email">Correo electrónico</label>
                        <div class="input-wrap">
                            <i class="bi bi-envelope icono"></i>
                            <input type="email" id="email" name="email" maxlength="100" required
                                value="<?= htmlspecialchars($item['email'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn btn-main"><i class="bi bi-save"></i> Guardar cambios</button>
                    <a href="index.php?action=proveedores" class="btn btn-ghost">Cancelar</a>
                </div>

            </form>
        </div>
    </div>

</main>

</body>
</html>