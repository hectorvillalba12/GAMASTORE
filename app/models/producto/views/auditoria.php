<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoría de Productos - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-clock-history"></i> Auditoría de Productos</h2>
        <a href="index.php?action=productos" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Producto (ID)</th>
                    <th>Datos anteriores</th>
                    <th>Datos nuevos</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($movimientos)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Todavía no hay registros de auditoría.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($movimientos as $m): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($m['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($m['usuario_email'] ?? 'Sistema'); ?></td>
                            <td>
                                <?php
                                    $badges = [
                                        'crear'      => 'success',
                                        'editar'     => 'primary',
                                        'baja'       => 'warning',
                                        'reactivar'  => 'info'
                                    ];
                                    $color = $badges[$m['accion']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?php echo $color; ?>"><?php echo htmlspecialchars($m['accion']); ?></span>
                            </td>
                            <td>#<?php echo htmlspecialchars($m['registro_id']); ?></td>
                            <td>
                                <small class="text-muted">
                                    <?php echo $m['datos_anteriores'] ? htmlspecialchars($m['datos_anteriores']) : '—'; ?>
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <?php echo $m['datos_nuevos'] ? htmlspecialchars($m['datos_nuevos']) : '—'; ?>
                                </small>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>