<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-dark d-flex justify-content-center align-items-center vh-100">

<div class="card p-4" style="width:300px">

<h3 class="text-center">Gamastore</h3>

<?php if(isset($_GET['msg'])): ?>

    <?php if($_GET['msg'] == 'ok'): ?>
        <div class="alert alert-success text-center">
            Contraseña actualizada correctamente
        </div>
    <?php endif; ?>

    <?php if($_GET['msg'] == 'error'): ?>
        <div class="alert alert-danger text-center">
            Error al cambiar la contraseña
        </div>
    <?php endif; ?>

    <?php if($_GET['msg'] == 'email_verificado'): ?>
        <div class="alert alert-success text-center">
            <strong>¡Email verificado!</strong><br>
            Tu cuenta fue confirmada correctamente.
        </div>
    <?php endif; ?>

    <?php if($_GET['msg'] == 'token_invalido'): ?>
        <div class="alert alert-danger text-center">
            El enlace de verificación no es válido o ya fue usado.
        </div>
    <?php endif; ?>

    <?php if($_GET['msg'] == 'registro_ok'): ?>
        <div class="alert alert-success text-center">
            <strong>¡Cuenta creada!</strong><br>
            Ya podés iniciar sesión.
        </div>
    <?php endif; ?>

    <?php if($_GET['msg'] == 'sesion_requerida'): ?>
        <div class="alert alert-warning text-center">
            Necesitás iniciar sesión para acceder.
        </div>
    <?php endif; ?>

    <?php if($_GET['msg'] == 'acceso_denegado'): ?>
        <div class="alert alert-danger text-center">
            No tenés permisos para acceder a esa sección.
        </div>
    <?php endif; ?>

    <?php if($_GET['msg'] == 'error_login'): ?>
        <div class="alert alert-danger text-center">
            <i class="bi bi-exclamation-circle me-1"></i>
            Email o contraseña incorrectos.
        </div>
    <?php endif; ?>

    <?php if($_GET['msg'] == 'usuario_inactivo'): ?>
        <div class="alert alert-warning text-center">
            <i class="bi bi-person-x me-1"></i>
            Tu cuenta está inactiva. Contactá al administrador.
        </div>
    <?php endif; ?>

<?php endif; ?>

<form method="POST" action="index.php?action=loginPost">

<input class="form-control mb-2" name="email" placeholder="Email">
<input class="form-control mb-2" type="password" name="password" placeholder="Password">

<button class="btn btn-primary w-100">Ingresar</button>

<a href="index.php?action=forgot" class="d-block text-center mt-2">
    ¿Olvidaste tu contraseña?
</a>

<hr>
<a href="index.php?action=register" class="d-block text-center text-success">
    <i class="bi bi-person-plus"></i> Crear cuenta nueva
</a>

</form>

</div>

</body>
</html>