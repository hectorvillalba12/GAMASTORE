<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-dark d-flex justify-content-center align-items-center vh-100">

<div class="card p-4" style="width:300px">

<h3 class="text-center">Gamastore</h3>

<!-- 🔔 MENSAJES -->
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

<?php endif; ?>

<form method="POST" action="index.php?action=loginPost">

<input class="form-control mb-2" name="email" placeholder="Email">
<input class="form-control mb-2" type="password" name="password" placeholder="Password">

<button class="btn btn-primary w-100">Ingresar</button>

<a href="index.php?action=forgot" class="d-block text-center mt-2">
    ¿Olvidaste tu contraseña?
</a>

</form>

</div>

</body>
</html>