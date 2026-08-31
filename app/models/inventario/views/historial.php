<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Movimientos - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            <i class="bi bi-clock-history"></i>
            Historial — <?= htmlspecialchars($item['nombre_producto']) ?>
        </h2>
        <a href="index.php?action=inventario" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Fecha</th>
                            <th>Tipo</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-center">Stock anterior</th>
                            <th class="text-center">Stock nuevo</th>
                            <th>Motivo</th>
                            <th>Usuario</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($movimientos)): ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                Todavía no hay movimientos registrados para este producto.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($movimientos as $m): ?>
                        <?php
                            $badgeTipo = [
                                'entrada' => 'bg-success',
                                'salida'  => 'bg-danger',
                                'ajuste'  => 'bg-secondary',
                            ][$m['tipo']] ?? 'bg-secondary';
                        ?>
                        <tr>
                            <td><?= date('d/m/Y H:i', strtotime($m['fecha'])) ?></td>
                            <td><span class="badge <?= $badgeTipo ?> text-uppercase"><?= $m['tipo'] ?></span></td>
                            <td class="text-center"><?= $m['cantidad'] ?></td>
                            <td class="text-center"><?= $m['stock_anterior'] ?></td>
                            <td class="text-center fw-bold"><?= $m['stock_nuevo'] ?></td>
                            <td><?= htmlspecialchars($m['motivo'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($m['usuario_email'] ?? '—') ?></td>
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