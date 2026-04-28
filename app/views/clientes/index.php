<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
    <h2>Clientes</h2>

<a href="index.php?action=create_cliente">Nuevo Cliente</a>

<table class="table mt-3">
<tr>
    <th>Nombre</th>
    <th>DNI</th>
    <th>Email</th>
    <th>Acciones</th>
</tr>

<?php foreach ($clientes as $c): ?>
<tr>
    <td><?= $c['nombre'] ?></td>
    <td><?= $c['dni'] ?></td>
    <td><?= $c['email'] ?></td>
    <td>
        <a href="index.php?action=edit_cliente&id=<?= $c['id_cliente'] ?>"class="btn btn-warning btn-sm">Editar</a>
        <a href="index.php?action=delete_cliente&id=<?= $c['id_cliente'] ?>"class="btn btn-danger btn-sm">Eliminar</a>
    </td>
</tr>
<?php endforeach; ?>
</table>
    </div>
</body>

</html>

