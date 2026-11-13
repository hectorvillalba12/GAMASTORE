<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Historial de Inventario | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap">

    <div class="page-head">
        <div>
            <div class="page-label">Inventario</div>
            <h1 class="page-titulo"><i class="bi bi-clock-history"></i> Historial de movimientos</h1>
            <p class="page-sub"><strong><?= htmlspecialchars($item['nombre_producto']) ?></strong></p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=inventario" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-list-ul"></i> Movimientos</span>
            <span class="cuenta"><?= count($movimientos ?? []) ?> registros</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:860px">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th class="centro">Cantidad</th>
                        <th class="centro">Stock anterior</th>
                        <th class="centro">Stock nuevo</th>
                        <th>Motivo</th>
                        <th>Proveedor</th>
                        <th>Usuario</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($movimientos)): ?>
                    <tr>
                        <td colspan="8" class="vacio">Todavía no hay movimientos registrados para este producto.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($movimientos as $m): ?>
                    <?php
                        $claseTipo = in_array($m['tipo'], ['entrada', 'salida', 'ajuste']) ? $m['tipo'] : 'ajuste';
                    ?>
                    <tr>
                        <td><?= date('d/m/Y H:i', strtotime($m['fecha'])) ?></td>
                        <td><span class="estado <?= $claseTipo ?>"><?= htmlspecialchars($m['tipo']) ?></span></td>
                        <td class="centro"><?= $m['cantidad'] ?></td>
                        <td class="centro"><?= $m['stock_anterior'] ?></td>
                        <td class="centro"><span class="stock-num"><?= $m['stock_nuevo'] ?></span></td>
                        <td><?= htmlspecialchars($m['motivo'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($m['proveedor_nombre'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($m['usuario_email'] ?? '—') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

</body>
</html>