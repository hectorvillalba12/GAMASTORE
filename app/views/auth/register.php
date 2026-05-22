<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-dark d-flex justify-content-center align-items-center vh-100">

<div class="card p-4 shadow" style="width: 380px;">

    <h3 class="text-center mb-3">
        <i class="bi bi-person-plus"></i> Crear cuenta
    </h3>

    <!-- Mensajes de error del servidor -->
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
                placeholder="Mínimo 6 caracteres" minlength="6">
        </div>

        <div class="mb-3">
            <!-- ÍTEM 6: Campo confirmar contraseña -->
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
// ÍTEM 6: Validación en tiempo real — contraseñas coinciden
const pass     = document.getElementById('password');
const confirm  = document.getElementById('confirmar_password');
const feedback = document.getElementById('feedback_password');
const btn      = document.getElementById('btnRegistrar');

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
pass.addEventListener('input', validarPasswords);
confirm.addEventListener('input', validarPasswords);

// ÍTEM 6: Validación también al intentar enviar el form
document.getElementById('formRegistro').addEventListener('submit', function(e) {
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