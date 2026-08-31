<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promociones - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-tags"></i> Promociones y Descuentos</h2>
        <div>
            <a href="index.php?action=dashboard" class="btn btn-outline-secondary btn-sm me-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="index.php?action=promociones_crear" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> Nueva Promoción
            </a>
        </div>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-1"></i> Promoción guardada correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['baja'])): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="bi bi-x-circle me-1"></i> Promoción desactivada.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['reactivado'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle me-1"></i> Promoción reactivada.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th class="text-center">Descuento</th>
                            <th>Vigencia</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($promociones)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">No hay promociones cargadas.</td></tr>
                    <?php else: ?>
                        <?php
                        $badgeEstado = [
                            'vigente'  => 'bg-success',
                            'proxima'  => 'bg-info text-dark',
                            'vencida'  => 'bg-secondary',
                            'inactiva' => 'bg-dark',
                        ];
                        $textoEstado = [
                            'vigente'  => 'Vigente',
                            'proxima'  => 'Próxima',
                            'vencida'  => 'Vencida',
                            'inactiva' => 'Inactiva',
                        ];
                        ?>
                        <?php foreach ($promociones as $p): ?>
                        <tr>
                            <td><?= $p['id_promocion'] ?></td>
                            <td>
                                <strong><?= htmlspecialchars($p['nombre']) ?></strong>
                                <?php if ($p['descripcion']): ?>
                                    <div class="text-muted small"><?= htmlspecialchars($p['descripcion']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?= $p['tipo_descuento'] === 'porcentaje' ? 'Porcentaje' : 'Monto fijo' ?></td>
                            <td class="text-center">
                                <?= $p['tipo_descuento'] === 'porcentaje'
                                        ? number_format($p['descuento_porcentaje'], 0) . '%'
                                        : '$' . number_format($p['monto_fijo'], 2) ?>
                            </td>
                            <td>
                                <?= date('d/m/Y', strtotime($p['fecha_inicio'])) ?>
                                al
                                <?= date('d/m/Y', strtotime($p['fecha_fin'])) ?>
                            </td>
                            <td class="text-center">
                                <span class="badge <?= $badgeEstado[$p['estado']] ?>">
                                    <?= $textoEstado[$p['estado']] ?>
                                </span>
                            </td>
                            <td class="text-center text-nowrap">
                                <a href="index.php?action=promociones_editar&id=<?= $p['id_promocion'] ?>"
                                    class="btn btn-warning btn-sm" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php if ($p['activa']): ?>
                                    <a href="index.php?action=promociones_eliminar&id=<?= $p['id_promocion'] ?>"
                                        class="btn btn-danger btn-sm" title="Desactivar"
                                        onclick="return confirm('¿Desactivar esta promoción?')">
                                        <i class="bi bi-x-circle"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="index.php?action=promociones_reactivar&id=<?= $p['id_promocion'] ?>"
                                        class="btn btn-success btn-sm" title="Reactivar">
                                        <i class="bi bi-check-circle"></i>
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>