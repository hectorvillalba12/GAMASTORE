<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <title>Registro - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex justify-content-center align-items-center vh-100">

<div class="card p-4 shadow" style="width: 380px;">

    <h3 class="text-center mb-3">
        <i class="bi bi-person-plus"></i> Crear cuenta
    </h3>

    
    <?php if (isset($_GET['error'])): ?>
        <?php if ($_GET['error'] === 'passwords_no_coinciden'): ?>
            <div class="alert alert-danger text-center py-2">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Las contraseñas no coinciden.
            </div>
        <?php elseif ($_GET['error'] === 'email_duplicado'): ?>
            <div class="alert alert-danger text-center py-2">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Ese email ya está registrado.
            </div>
        <?php elseif ($_GET['error'] === 'campos_requeridos'): ?>
            <div class="alert alert-danger text-center py-2">
                <i class="bi bi-exclamation-triangle-fill"></i>
                Todos los campos son obligatorios.
            </div>
        <?php elseif ($_GET['error'] === 'password_debil'): ?>
            <div class="alert alert-danger text-center py-2">
                <i class="bi bi-exclamation-triangle-fill"></i>
                La contraseña debe tener mínimo 8 caracteres, una mayúscula y un carácter especial.
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <form method="POST" action="index.php?action=registerPost" id="formRegistro">

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required
                placeholder="ejemplo@gmail.com">
        </div>

        <div class="mb-3">
            <label class="form-label">Contraseña</label>
            <input type="password" name="password" id="password" class="form-control" required
                placeholder="Mínimo 8 caracteres, 1 mayúscula y 1 carácter especial"
                minlength="8"
                pattern="^(?=.*[A-Z])(?=.*[!@#$%^&amp;*(),.?&quot;:{}|&lt;&gt;_\-+=~`\[\];'/\\]).{8,}$"
                title="Debe tener mínimo 8 caracteres, una mayúscula y un carácter especial">
            <div id="feedback_reglas" class="form-text" style="display:none;"></div>
        </div>

        <div class="mb-3">
        
            <label class="form-label">Confirmar contraseña</label>
            <input type="password" name="confirmar_password" id="confirmar_password"
                class="form-control" required placeholder="Repetí la contraseña">
            <!-- Feedback en tiempo real -->
            <div id="feedback_password" class="form-text" style="display:none;"></div>
        </div>

        <div class="mb-3">
            <label class="form-label">Rol</label>
            <select name="rol" class="form-select" required>
                <option value="">-- Seleccioná un rol --</option>
                <option value="admin">Administrador</option>
                <option value="empleado">Empleado</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success w-100" id="btnRegistrar">
            <i class="bi bi-check-circle"></i> Registrarse
        </button>

    </form>

    <a href="index.php?action=login" class="d-block text-center mt-3 text-muted">
        <i class="bi bi-arrow-left"></i> Volver al login
    </a>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Regex: mínimo 8 caracteres, 1 mayúscula, 1 carácter especial
const regexPassword = /^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>_\-+=~`\[\];'/\\]).{8,}$/;

const pass          = document.getElementById('password');
const confirm       = document.getElementById('confirmar_password');
const feedback      = document.getElementById('feedback_password');
const feedbackReglas= document.getElementById('feedback_reglas');
const btn           = document.getElementById('btnRegistrar');

// Validación en tiempo real — reglas de la contraseña
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

// Validación en tiempo real — contraseñas coinciden
function validarPasswords() {
    const p1 = pass.value;
    const p2 = confirm.value;

    if (p2.length === 0) {
        feedback.style.display = 'none';
        confirm.classList.remove('is-valid', 'is-invalid');
        btn.disabled = false;
        return;
    }

    feedback.style.display = 'block';

    if (p1 === p2) {
        confirm.classList.remove('is-invalid');
        confirm.classList.add('is-valid');
        feedback.className = 'form-text text-success';
        feedback.innerHTML = '<i class="bi bi-check-circle-fill"></i> Las contraseñas coinciden';
        btn.disabled = false;
    } else {
        confirm.classList.remove('is-valid');
        confirm.classList.add('is-invalid');
        feedback.className = 'form-text text-danger';
        feedback.innerHTML = '<i class="bi bi-x-circle-fill"></i> Las contraseñas no coinciden';
        btn.disabled = true;
    }
}

// Validar mientras escribe en cualquiera de los dos campos
pass.addEventListener('input', function () {
    validarReglasPassword();
    validarPasswords();
});
confirm.addEventListener('input', validarPasswords);

// Validación también al intentar enviar el form
document.getElementById('formRegistro').addEventListener('submit', function(e) {
    const reglasOk = validarReglasPassword();

    if (!reglasOk) {
        e.preventDefault();
        feedbackReglas.style.display = 'block';
        return;
    }

    if (pass.value !== confirm.value) {
        e.preventDefault();
        confirm.classList.add('is-invalid');
        feedback.style.display = 'block';
        feedback.className = 'form-text text-danger';
        feedback.innerHTML = '<i class="bi bi-x-circle-fill"></i> Las contraseñas no coinciden';
    }
});
</script>

</body>
</html>