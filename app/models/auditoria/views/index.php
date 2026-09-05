<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auditoría - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-clock-history"></i> Auditoría del sistema</h2>
        <a href="index.php?action=dashboard" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver al dashboard
        </a>
    </div>

    <!-- FILTRO POR MÓDULO / TABLA -->
    <form method="GET" action="index.php" class="row g-2 align-items-end mb-4">
        <input type="hidden" name="action" value="auditoria">
        <div class="col-auto">
            <label class="form-label mb-1">Módulo</label>
            <select name="tabla" class="form-select" onchange="this.form.submit()">
                <option value="">Todos los módulos</option>
                <?php foreach ($tablas as $t): ?>
                    <option value="<?php echo htmlspecialchars($t); ?>"
                        <?php echo (isset($_GET['tabla']) && $_GET['tabla'] === $t) ? 'selected' : ''; ?>>
                        <?php echo ucfirst(htmlspecialchars($t)); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if (!empty($_GET['tabla'])): ?>
            <div class="col-auto">
                <a href="index.php?action=auditoria" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Quitar filtro
                </a>
            </div>
        <?php endif; ?>
    </form>

    <div class="table-responsive">
        <table class="table table-striped table-bordered align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Fecha</th>
                    <th>Usuario</th>
                    <th>Módulo</th>
                    <th>Acción</th>
                    <th>Registro</th>
                    <th>Datos anteriores</th>
                    <th>Datos nuevos</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($movimientos)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Todavía no hay registros de auditoría<?php echo !empty($_GET['tabla']) ? ' para este módulo' : ''; ?>.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($movimientos as $m): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($m['fecha']); ?></td>
                            <td><?php echo htmlspecialchars($m['usuario_email'] ?? 'Sistema'); ?></td>
                            <td>
                                <span class="badge bg-secondary"><?php echo ucfirst(htmlspecialchars($m['tabla'])); ?></span>
                            </td>
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