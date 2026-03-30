<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<h2>Productos</h2>
<a href="index.php?action=crear" class="btn btn-success">Nuevo</a>
<a href="index.php?action=excel" class="btn btn-secondary">Excel</a>
<table class="table mt-3">
<tr><th>ID</th><th>Nombre</th><th>tipodezapatilla</th><th>precio</th><th>talle_id_talle</th><th>marca_id_marca</th><th>categoria_id_categoria</th><th>color_id_color</th><th>Acciones</th></tr>
<?php foreach($productos as $p): ?>
<tr>
<td><?= $p['id_producto'] ?></td>
<td><?= $p['nombre'] ?></td>
<td><?= $p['precio'] ?></td>
<td>
<a href="index.php?action=editar&id=<?= $p['id_producto'] ?>" class="btn btn-warning btn-sm">Editar</a>
<a href="index.php?action=eliminar&id=<?= $p['id_producto'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
</td>
</tr>
<?php endforeach; ?>
</table>
</div>
</body>
</html>
