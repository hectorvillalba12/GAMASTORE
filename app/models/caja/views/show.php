<?php
$m = fn($n) => '$' . number_format((float)$n, 2, ',', '.');
$ok = $_GET['ok'] ?? '';
$cerrada = ($caja['estado'] === 'cerrada');
$tieneArqueo = !empty($arqueo);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Caja #<?= $caja['id_caja'] ?> | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap estrecho">

    <div class="page-head no-print">
        <div>
            <div class="page-label">Caja</div>
            <h1 class="page-titulo"><i class="bi bi-cash-stack"></i> Caja #<?= $caja['id_caja'] ?></h1>
        </div>
        <div class="page-btns">
            <a href="index.php?action=caja" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Volver</a>
            <button type="button" onclick="window.print()" class="btn btn-ghost">
                <i class="bi bi-printer"></i> Imprimir
            </button>
            <?php if (!$cerrada): ?>
                <a href="index.php?action=caja_cerrar" class="btn btn-danger"><i class="bi bi-lock"></i> Cerrar caja</a>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($ok === 'cerrada'): ?>
        <div class="alerta success no-print">
            <i class="bi bi-check-circle-fill"></i> Caja cerrada correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Datos de la caja -->
    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-receipt"></i> GamaStore — Arqueo de caja</span>
            <?php if ($cerrada): ?>
                <span class="estado neutro">Cerrada</span>
            <?php else: ?>
                <span class="estado info">Abierta</span>
            <?php endif; ?>
        </div>
        <div class="panel-body">

            <div class="meta-grid">
                <div class="meta"><small>Apertura</small><b><?= date('d/m/Y H:i', strtotime($caja['fecha_apertura'])) ?></b></div>
                <div class="meta"><small>Abierta por</small><b><?= htmlspecialchars($caja['email_apertura']) ?></b></div>
                <div class="meta"><small>Cierre</small><b><?= $cerrada ? date('d/m/Y H:i', strtotime($caja['fecha_cierre'])) : '—' ?></b></div>
                <div class="meta"><small>Cerrada por</small><b><?= htmlspecialchars($caja['email_cierre'] ?? '—') ?></b></div>
                <div class="meta"><small>Obs. apertura</small><b><?= htmlspecialchars($caja['observacion_apertura'] ?? '—') ?></b></div>
                <div class="meta"><small>Obs. cierre</small><b><?= htmlspecialchars($caja['observacion_cierre'] ?? '—') ?></b></div>
            </div>

            <?php
                $esperado = $cerrada ? (float)$caja['monto_esperado'] : $esperadoActual;
            ?>

            <!-- Totales -->
            <div class="kpis">
                <div class="kpi neutra"><small>Monto inicial</small><b><?= $m($caja['monto_inicial']) ?></b></div>
                <div class="kpi neutra"><small>Total vendido (<?= (int)$resumen['cantidad'] ?>)</small><b><?= $m($resumen['total']) ?></b></div>

                <?php if ($cerrada && $tieneArqueo): $d = (float)$caja['diferencia']; ?>
                    <div class="kpi verde"><small>Total esperado</small><b><?= $m($esperado) ?></b></div>
                    <div class="kpi neutra"><small>Total contado</small><b><?= $m($caja['monto_declarado']) ?></b></div>
                    <div class="kpi <?= $d == 0 ? 'verde' : '' ?>">
                        <small>Diferencia total</small>
                        <b><?= $d < 0 ? 'Faltante ' . $m(abs($d)) : ($d > 0 ? 'Sobrante ' . $m($d) : 'Sin diferencia') ?></b>
                    </div>

                <?php elseif ($cerrada): $d = (float)$caja['diferencia']; /* caja cerrada antes del arqueo por método: solo efectivo */ ?>
                    <div class="kpi verde"><small>Efectivo esperado</small><b><?= $m($esperado) ?></b></div>
                    <div class="kpi neutra"><small>Efectivo contado</small><b><?= $m($caja['monto_declarado']) ?></b></div>
                    <div class="kpi <?= $d == 0 ? 'verde' : '' ?>">
                        <small>Diferencia</small>
                        <b><?= $d < 0 ? 'Faltante ' . $m(abs($d)) : ($d > 0 ? 'Sobrante ' . $m($d) : 'Sin diferencia') ?></b>
                    </div>

                <?php else: ?>
                    <div class="kpi verde"><small>Efectivo esperado</small><b><?= $m($esperado) ?></b></div>
                <?php endif; ?>
            </div>

            <div class="kpis">
                <div class="kpi neutra"><small>Ingresos de efectivo</small><b><?= $m($resumen['ingresos']) ?></b></div>
                <div class="kpi neutra"><small>Egresos de efectivo</small><b><?= $m($resumen['egresos']) ?></b></div>
            </div>

            <?php if (!empty($movimientos)): ?>
            <div class="tabla-wrap">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Hora</th>
                            <th>Tipo</th>
                            <th>Motivo</th>
                            <th>Usuario</th>
                            <th class="der">Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($movimientos as $mv): $esIng = ($mv['tipo'] === 'ingreso'); ?>
                        <tr>
                            <td><?= date('d/m H:i', strtotime($mv['fecha'])) ?></td>
                            <td><span class="estado <?= $esIng ? 'normal' : 'bajo' ?>"><?= $esIng ? 'Ingreso' : 'Egreso' ?></span></td>
                            <td><?= htmlspecialchars($mv['motivo']) ?></td>
                            <td><?= htmlspecialchars($mv['email_usuario']) ?></td>
                            <td class="der precio"><?= ($esIng ? '+' : '−') . $m($mv['monto']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>

            <?php if ($tieneArqueo): ?>

                <!-- Arqueo por método de pago -->
                <div class="tabla-wrap">
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th>Método de pago</th>
                                <th class="centro">Ventas</th>
                                <th class="der">Esperado</th>
                                <th class="der">Contado</th>
                                <th class="centro">Diferencia</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($arqueo as $a):
                            $d = (float)$a['diferencia'];
                            $clase  = $d < 0 ? 'bajo' : ($d > 0 ? 'sobre' : 'normal');
                            $txtDif = $d < 0 ? 'Faltante ' . $m(abs($d)) : ($d > 0 ? 'Sobrante ' . $m($d) : 'Sin diferencia');
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($a['metodo_de_pago']) ?></td>
                                <td class="centro"><?= (int)$a['cantidad_ventas'] ?></td>
                                <td class="der"><?= $m($a['monto_esperado']) ?></td>
                                <td class="der precio"><?= $m($a['monto_declarado']) ?></td>
                                <td class="centro"><span class="estado <?= $clase ?>"><?= $txtDif ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>

                <!-- Caja abierta o cerrada sin arqueo por método: tarjetas + total por método -->
                <?php $pm = array_column($resumen['por_metodo'], null, 'metodo_de_pago'); ?>
                <div class="kpis">
                    <?php
                    $iconos = [
                        'Transferencia'   => 'bi-bank',
                        'Tarjeta Crédito' => 'bi-credit-card',
                        'Tarjeta Débito'  => 'bi-credit-card-2-front'
                    ];
                    foreach ($iconos as $nombre => $icono): ?>
                    <div class="kpi neutra">
                        <small><i class="bi <?= $icono ?>"></i> <?= $nombre ?> (<?= (int)($pm[$nombre]['cantidad'] ?? 0) ?>)</small>
                        <b><?= $m($pm[$nombre]['total'] ?? 0) ?></b>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="tabla-wrap">
                    <table class="tabla">
                        <thead>
                            <tr><th>Método de pago</th><th class="centro">Ventas</th><th class="der">Total</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($resumen['por_metodo'] as $f): ?>
                            <tr>
                                <td><?= htmlspecialchars($f['metodo_de_pago'] ?? 'Sin definir') ?></td>
                                <td class="centro"><?= (int)$f['cantidad'] ?></td>
                                <td class="der precio"><?= $m($f['total']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td>Total vendido</td>
                                <td class="centro"><?= (int)$resumen['cantidad'] ?></td>
                                <td class="der total-final"><?= $m($resumen['total']) ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            <?php endif; ?>
        </div>
    </div>

    <!-- Ventas del turno -->
    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-cart-check"></i> Ventas del turno</span>
            <span class="cuenta"><?= count($ventas) ?> registradas</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>#</th><th>Fecha</th><th>Método de pago</th>
                        <th class="der">Total</th><th class="centro">Estado</th>
                        <th class="centro no-print">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($ventas)): ?>
                    <tr><td colspan="6" class="vacio">No hay ventas en esta caja.</td></tr>
                <?php else: ?>
                    <?php foreach ($ventas as $v): ?>
                    <tr>
                        <td class="id"><?= $v['id_venta'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                        <td><?= htmlspecialchars($v['metodo_de_pago'] ?? '—') ?></td>
                        <td class="der precio"><?= $m($v['total']) ?></td>
                        <td class="centro">
                            <?php if ($v['estado'] === 'cancelada'): ?>
                                <span class="estado bajo">Cancelada</span>
                            <?php else: ?>
                                <span class="estado normal">Completada</span>
                            <?php endif; ?>
                        </td>
                        <td class="no-print">
                            <div class="acciones-fila">
                                <a href="index.php?action=ventas_ver&id=<?= $v['id_venta'] ?>" class="btn-icon historial" title="Ver venta">
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

</main>

</body>
</html>