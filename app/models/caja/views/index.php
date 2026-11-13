<?php
$m = fn($n) => '$' . number_format((float)$n, 2, ',', '.');

$mensajes = [
    'abierta' => 'Caja abierta correctamente. Ya podés registrar ventas.',
    'cerrada' => 'Caja cerrada correctamente.',
    'ingreso' => 'Ingreso de efectivo registrado correctamente.',
    'egreso'  => 'Egreso de efectivo registrado correctamente.',
];
$errores = [
    'ya_abierta'       => 'Ya hay una caja abierta. Cerrala antes de abrir otra.',
    'sin_caja_abierta' => 'No hay ninguna caja abierta.',
    'no_abierta'       => 'Esa caja ya no está abierta.',
    'caja_requerida'   => 'Tenés que abrir la caja antes de registrar ventas.',
    'movimiento_invalido' => 'El tipo de movimiento no es válido.',
];
$ok    = $_GET['ok']    ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Caja | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap">

    <!-- Encabezado -->
    <div class="page-head">
        <div>
            <div class="page-label">Módulo de gestión</div>
            <h1 class="page-titulo"><i class="bi bi-cash-stack"></i> Caja</h1>
            <p class="page-sub">Apertura y cierre de caja del local.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=dashboard" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <?php if ($actual): ?>
                <a href="index.php?action=ventas_crear" class="btn btn-ghost">
                    <i class="bi bi-cart-plus"></i> Nueva venta
                </a>
                <a href="index.php?action=caja_movimiento&tipo=ingreso" class="btn btn-ghost">
                    <i class="bi bi-box-arrow-in-down"></i> Ingreso
                </a>
                <a href="index.php?action=caja_movimiento&tipo=egreso" class="btn btn-ghost">
                    <i class="bi bi-box-arrow-up"></i> Egreso
                </a>
                <a href="index.php?action=caja_cerrar" class="btn btn-danger">
                    <i class="bi bi-lock"></i> Cerrar caja
                </a>
            <?php else: ?>
                <a href="index.php?action=caja_abrir" class="btn btn-main">
                    <i class="bi bi-unlock"></i> Abrir caja
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Mensajes -->
    <?php if (isset($mensajes[$ok])): ?>
        <div class="alerta success">
            <i class="bi bi-check-circle-fill"></i> <?= $mensajes[$ok] ?>
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($errores[$error])): ?>
        <div class="alerta danger">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= $errores[$error] ?>
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Estado actual -->
    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-cash-coin"></i> Estado actual</span>
            <?php if ($actual): ?>
                <span class="estado normal">Caja abierta</span>
            <?php else: ?>
                <span class="estado bajo">Caja cerrada</span>
            <?php endif; ?>
        </div>
        <div class="panel-body">
        <?php if ($actual): ?>
            <div class="kpis">
                <div class="kpi neutra">
                    <small>Monto inicial</small>
                    <b><?= $m($actual['monto_inicial']) ?></b>
                </div>
                <div class="kpi neutra">
                    <small>Ventas del turno (<?= (int)$resumen['cantidad'] ?>)</small>
                    <b><?= $m($resumen['total']) ?></b>
                </div>
                <div class="kpi neutra">
                    <small>Ingresos / Egresos</small>
                    <b>+<?= $m($resumen['ingresos']) ?> / −<?= $m($resumen['egresos']) ?></b>
                </div>
                <div class="kpi verde">
                    <small>Efectivo esperado en caja</small>
                    <b><?= $m($esperado) ?></b>
                </div>
            </div>

            <div class="meta-grid">
                <div class="meta"><small>Abierta el</small><b><?= date('d/m/Y H:i', strtotime($actual['fecha_apertura'])) ?></b></div>
                <div class="meta"><small>Abierta por</small><b><?= htmlspecialchars($actual['email_apertura']) ?></b></div>
                <div class="meta"><small>Observación</small><b><?= htmlspecialchars($actual['observacion_apertura'] ?? '—') ?></b></div>
            </div>

            <a href="index.php?action=caja_ver&id=<?= $actual['id_caja'] ?>" class="btn btn-ghost">
                <i class="bi bi-eye"></i> Ver detalle del turno
            </a>
        <?php else: ?>
            <p class="page-sub">No hay una caja abierta. Abrí la caja para empezar a registrar ventas.</p>
        <?php endif; ?>
        </div>
    </div>

    <!-- Historial -->
    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-clock-history"></i> Historial de cajas</span>
            <span class="cuenta"><?= (int)$totalCajas ?> en total</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:1100px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Apertura</th>
                        <th>Cierre</th>
                        <th>Responsable</th>
                        <th class="der">Inicial</th>
                        <th class="der">Ventas</th>
                        <th class="der">Esperado</th>
                        <th class="der">Contado</th>
                        <th class="centro">Diferencia</th>
                        <th class="centro">Estado</th>
                        <th class="centro">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($cajas)): ?>
                    <tr><td colspan="11" class="vacio">Todavía no hay cajas registradas.</td></tr>
                <?php else: ?>
                    <?php foreach ($cajas as $c): ?>
                    <tr>
                        <td class="id"><?= $c['id_caja'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($c['fecha_apertura'])) ?></td>
                        <td><?= $c['fecha_cierre'] ? date('d/m/Y H:i', strtotime($c['fecha_cierre'])) : '—' ?></td>
                        <td class="wrap-td"><?= htmlspecialchars($c['email_cierre'] ?? $c['email_apertura']) ?></td>
                        <td class="der"><?= $m($c['monto_inicial']) ?></td>
                        <td class="der"><?= $c['estado'] === 'cerrada' ? $m($c['total_ventas']) : '—' ?></td>
                        <td class="der"><?= $c['estado'] === 'cerrada' ? $m($c['monto_esperado']) : '—' ?></td>
                        <td class="der"><?= $c['estado'] === 'cerrada' ? $m($c['monto_declarado']) : '—' ?></td>
                        <td class="centro">
                            <?php if ($c['estado'] === 'cerrada'):
                                $d = (float)$c['diferencia'];
                                $clase = $d < 0 ? 'bajo' : ($d > 0 ? 'sobre' : 'normal');
                                $txt   = $d < 0 ? 'Faltante ' . $m(abs($d)) : ($d > 0 ? 'Sobrante ' . $m($d) : 'Sin diferencia');
                            ?>
                                <span class="estado <?= $clase ?>"><?= $txt ?></span>
                            <?php else: ?>—<?php endif; ?>
                        </td>
                        <td class="centro">
                            <?php if ($c['estado'] === 'abierta'): ?>
                                <span class="estado info">Abierta</span>
                            <?php else: ?>
                                <span class="estado neutro">Cerrada</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="acciones-fila">
                                <a href="index.php?action=caja_ver&id=<?= $c['id_caja'] ?>" class="btn-icon historial" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <?php if ($totalCajas > 0): ?>
        <?php
            $paramsBase = ['action' => 'caja'];
            $inicio = max(1, $pagina - 2);
            $fin    = min($totalPaginas, $pagina + 2);
        ?>
        <div class="paginacion">
            <small>Mostrando <?= $desde ?> a <?= $hasta ?> de <?= $totalCajas ?> cajas</small>

            <?php if ($totalPaginas > 1): ?>
            <nav class="pag" aria-label="Paginación de cajas">
                <a class="<?= ($pagina <= 1) ? 'off' : '' ?>"
                   href="index.php?<?= http_build_query($paramsBase + ['pagina' => $pagina - 1]) ?>">&laquo; Anterior</a>

                <?php if ($inicio > 1): ?>
                    <a href="index.php?<?= http_build_query($paramsBase + ['pagina' => 1]) ?>">1</a>
                    <?php if ($inicio > 2): ?><span>…</span><?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $inicio; $i <= $fin; $i++): ?>
                    <a class="<?= ($i == $pagina) ? 'activa' : '' ?>"
                       href="index.php?<?= http_build_query($paramsBase + ['pagina' => $i]) ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($fin < $totalPaginas): ?>
                    <?php if ($fin < $totalPaginas - 1): ?><span>…</span><?php endif; ?>
                    <a href="index.php?<?= http_build_query($paramsBase + ['pagina' => $totalPaginas]) ?>"><?= $totalPaginas ?></a>
                <?php endif; ?>

                <a class="<?= ($pagina >= $totalPaginas) ? 'off' : '' ?>"
                   href="index.php?<?= http_build_query($paramsBase + ['pagina' => $pagina + 1]) ?>">Siguiente &raquo;</a>
            </nav>
            <?php endif; ?>
        </div>
    <?php endif; ?>

</main>

</body>
</html>