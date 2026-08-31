<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer contraseña - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex justify-content-center align-items-center vh-100">

<div class="card p-4 shadow" style="width: 380px;">

    <h3 class="text-center mb-3">
        <i class="bi bi-key"></i> Nueva contraseña
    </h3>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'password_debil'): ?>
        <div class="alert alert-danger text-center py-2">
            <i class="bi bi-exclamation-triangle-fill"></i>
            La contraseña debe tener mínimo 8 caracteres, una mayúscula y un carácter especial.
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?action=resetPassword" id="formReset">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">

        <div class="mb-3">
            <label class="form-label">Nueva contraseña</label>
            <input type="password" name="password" id="password" class="form-control" required
                placeholder="Mínimo 8 caracteres, 1 mayúscula y 1 carácter especial"
                minlength="8"
                pattern="^(?=.*[A-Z])(?=.*[!@#$%^&amp;*(),.?&quot;:{}|&lt;&gt;_\-+=~`\[\];'/\\]).{8,}$"
                title="Debe tener mínimo 8 caracteres, una mayúscula y un carácter especial">
            <div id="feedback_reglas" class="form-text" style="display:none;"></div>
        </div>

        <button type="submit" class="btn btn-success w-100" id="btnReset">
            <i class="bi bi-check-circle"></i> Cambiar contraseña
        </button>
    </form>

    <a href="index.php?action=login" class="d-block text-center mt-3 text-muted">
        <i class="bi bi-arrow-left"></i> Volver al login
    </a>

</div>

<script>
const regexPassword  = /^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>_\-+=~`\[\];'/\\]).{8,}$/;
const pass           = document.getElementById('password');
const feedbackReglas = document.getElementById('feedback_reglas');

function validarReglasPassword() {
    const p1 = pass.value;

    if (p1.length === 0) {
        feedbackReglas.style.display = 'none';
        pass.classList.remove('is-valid', 'is-invalid');
        return true;
    }

    feedbackReglas.style.display = 'block';

    if (regexPassword.test(p1)) {
        pass.classList.remove('is-invalid');
        pass.classList.add('is-valid');
        feedbackReglas.className = 'form-text text-success';
        feedbackReglas.innerHTML = '<i class="bi bi-check-circle-fill"></i> Contraseña válida';
        return true;
    } else {
        pass.classList.remove('is-valid');
        pass.classList.add('is-invalid');
        feedbackReglas.className = 'form-text text-danger';
        feedbackReglas.innerHTML = '<i class="bi bi-x-circle-fill"></i> Debe tener mínimo 8 caracteres, una mayúscula y un carácter especial';
        return false;
    }
}

pass.addEventListener('input', validarReglasPassword);

document.getElementById('formReset').addEventListener('submit', function (e) {
    if (!validarReglasPassword()) {
        e.preventDefault();
        feedbackReglas.style.display = 'block';
    }
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>