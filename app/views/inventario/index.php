<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inventario - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-boxes"></i> Inventario</h2>
        <div>
            <a href="index.php?action=dashboard" class="btn btn-outline-secondary btn-sm me-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="index.php?action=inventario_crear" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> Nuevo
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover table-striped mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Producto</th>
                        <th>Ubicación</th>
                        <th class="text-center">Stock Actual</th>
                        <th class="text-center">Stock Mín.</th>
                        <th class="text-center">Stock Máx.</th>
                        <th class="text-center">Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $filas = $inventarios->fetchAll(PDO::FETCH_ASSOC);
                if (empty($filas)):
                ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No hay registros de inventario.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($filas as $i): ?>
                    <?php
                        if ($i['stock_actual'] <= $i['stock_minimo']) {
                            $badge = 'bg-danger';
                            $estado = 'Stock bajo';
                        } elseif ($i['stock_actual'] >= $i['stock_maximo']) {
                            $badge = 'bg-warning text-dark';
                            $estado = 'Sobrestock';
                        } else {
                            $badge = 'bg-success';
                            $estado = 'Normal';
                        }
                    ?>
                    <tr>
                        <td><?= $i['id_inventario'] ?></td>
                        <td><strong><?= htmlspecialchars($i['nombre_producto'] ?? '—') ?></strong></td>
                        <td><?= htmlspecialchars($i['ubicacion']) ?></td>
                        <td class="text-center"><?= $i['stock_actual'] ?></td>
                        <td class="text-center"><?= $i['stock_minimo'] ?></td>
                        <td class="text-center"><?= $i['stock_maximo'] ?></td>
                        <td class="text-center">
                            <span class="badge <?= $badge ?>"><?= $estado ?></span>
                        </td>
                        <td class="text-center">
                            <a href="index.php?action=inventario_editar&id=<?= $i['id_inventario'] ?>"
                            class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="index.php?action=inventario_eliminar&id=<?= $i['id_inventario'] ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('¿Eliminar este registro?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>