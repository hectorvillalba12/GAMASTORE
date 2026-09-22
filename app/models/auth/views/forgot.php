<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex justify-content-center align-items-center vh-100">

<div class="card p-4 shadow" style="width: 380px;">

    <h3 class="text-center mb-3">
        <i class="bi bi-envelope-lock"></i> Recuperar contraseña
    </h3>

    <p class="text-muted text-center small mb-3">
        Ingresá tu email y te enviaremos un enlace para restablecer tu contraseña.
    </p>

    <?php if (isset($_GET['msg'])): ?>

        <?php if ($_GET['msg'] === 'enviado'): ?>
            <div class="alert alert-success text-center py-2">
                <i class="bi bi-check-circle-fill"></i>
                Te enviamos un correo con el enlace de recuperación.
            </div>
        <?php endif; ?>

        <?php if ($_GET['msg'] === 'no_encontrado'): ?>
            <div class="alert alert-warning text-center py-2">
                <i class="bi bi-exclamation-triangle-fill"></i>
                No encontramos una cuenta con ese email.
            </div>
        <?php endif; ?>

        <?php if ($_GET['msg'] === 'error'): ?>
            <div class="alert alert-danger text-center py-2">
                <i class="bi bi-x-circle-fill"></i>
                No pudimos enviar el correo. Intentá de nuevo más tarde.
            </div>
        <?php endif; ?>

    <?php endif; ?>

    <form method="POST" action="index.php?action=sendReset" id="formForgot">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control"
                placeholder="tu@email.com" required>
        </div>

        <button type="submit" class="btn btn-primary w-100" id="btnRecuperar">
            <i class="bi bi-send"></i> Recuperar
        </button>
    </form>

    <a href="index.php?action=login" class="d-block text-center mt-3 text-muted">
        <i class="bi bi-arrow-left"></i> Volver al login
    </a>

</div>

<script>
document.getElementById('formForgot').addEventListener('submit', function () {
    const btn = document.getElementById('btnRecuperar');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Enviando...';
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>