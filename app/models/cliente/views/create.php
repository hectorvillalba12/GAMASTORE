<?php $error = $_GET['error'] ?? ''; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nuevo Cliente | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap estrecho">

    <div class="page-head">
        <div>
            <div class="page-label">Clientes</div>
            <h1 class="page-titulo"><i class="bi bi-person-plus"></i> Nuevo cliente</h1>
            <p class="page-sub">Completá los datos para registrar un cliente en GamaStore.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=clientes" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </div>

    <?php if ($error === 'dni_duplicado'): ?>
        <div class="alerta danger">
            <i class="bi bi-exclamation-triangle-fill"></i> Ya existe un cliente registrado con ese DNI.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php elseif ($error === 'email_duplicado'): ?>
        <div class="alerta danger">
            <i class="bi bi-exclamation-triangle-fill"></i> Ya existe un cliente registrado con ese email.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <div class="panel">
        <div class="panel-body">
            <form method="POST" action="index.php?action=store_cliente">

                <div class="form-grid">
                    <div class="campo col-3">
                        <label for="nombre">Nombre</label>
                        <div class="input-wrap">
                            <i class="bi bi-person icono"></i>
                            <input type="text" id="nombre" name="nombre" required autofocus>
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="apellido">Apellido</label>
                        <div class="input-wrap">
                            <i class="bi bi-person icono"></i>
                            <input type="text" id="apellido" name="apellido" required>
                        </div>
                    </div>

                    <div class="campo col-2">
                        <label for="dni">DNI</label>
                        <div class="input-wrap">
                            <i class="bi bi-card-heading icono"></i>
                            <input type="text" id="dni" name="dni" required>
                        </div>
                    </div>

                    <div class="campo col-2">
                        <label for="telefono">Teléfono</label>
                        <div class="input-wrap">
                            <i class="bi bi-telephone icono"></i>
                            <input type="text" id="telefono" name="telefono">
                        </div>
                    </div>

                    <div class="campo col-2">
                        <label for="email">Email <small>(se enviará un correo de verificación)</small></label>
                        <div class="input-wrap">
                            <i class="bi bi-envelope icono"></i>
                            <input type="email" id="email" name="email">
                        </div>
                    </div>
                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn btn-main"><i class="bi bi-save"></i> Guardar cliente</button>
                    <a href="index.php?action=clientes" class="btn btn-ghost">Cancelar</a>
                </div>

            </form>
        </div>
    </div>

</main>

</body>
</html>