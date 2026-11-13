<?php
$m = fn($n) => '$' . number_format((float)$n, 2, ',', '.');

$errores = [
    'monto_invalido'        => 'Ingresá un monto contado válido (número positivo, hasta 2 decimales).',
    'observacion_requerida' => 'Hay diferencia entre lo esperado y lo contado: explicá el motivo en la observación.',
    'error_al_cerrar'       => 'Ocurrió un error al cerrar la caja. Intentá de nuevo.',
];
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cerrar caja | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap estrecho">

    <div class="page-head">
        <div>
            <div class="page-label">Caja</div>
            <h1 class="page-titulo"><i class="bi bi-lock"></i> Cerrar caja #<?= $actual['id_caja'] ?></h1>
            <p class="page-sub">Contá el efectivo y registrá el arqueo del turno.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=caja" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </div>

    <?php if (isset($errores[$error])): ?>
        <div class="alerta danger">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= $errores[$error] ?>
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Resumen del turno -->
    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-receipt"></i> Resumen del turno</span>
            <span class="cuenta">Abierta el <?= date('d/m/Y H:i', strtotime($actual['fecha_apertura'])) ?></span>
        </div>
        <div class="panel-body">
            <div class="kpis">
                <div class="kpi neutra">
                    <small>Monto inicial</small>
                    <b><?= $m($actual['monto_inicial']) ?></b>
                </div>
                <div class="kpi neutra">
                    <small>Ventas en efectivo</small>
                    <b><?= $m($resumen['efectivo']) ?></b>
                </div>
                <div class="kpi neutra">
                    <small>Ingresos / Egresos</small>
                    <b>+<?= $m($resumen['ingresos']) ?> / −<?= $m($resumen['egresos']) ?></b>
                </div>
                <div class="kpi verde">
                    <small>Efectivo esperado</small>
                    <b><?= $m($esperado) ?></b>
                </div>
            </div>
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
                        <tr>
                            <th>Método de pago</th>
                            <th class="centro">Ventas</th>
                            <th class="der">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($resumen['por_metodo'])): ?>
                        <tr><td colspan="3" class="vacio">No hubo ventas en este turno.</td></tr>
                    <?php else: ?>
                        <?php foreach ($resumen['por_metodo'] as $f): ?>
                        <tr>
                            <td><?= htmlspecialchars($f['metodo_de_pago'] ?? 'Sin definir') ?></td>
                            <td class="centro"><?= (int)$f['cantidad'] ?></td>
                            <td class="der precio"><?= $m($f['total']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
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

            <?php if ($resumen['canceladas_cant'] > 0): ?>
                <div class="nota aviso" style="margin-top:14px">
                    <i class="bi bi-info-circle"></i>
                    <?= (int)$resumen['canceladas_cant'] ?> venta(s) cancelada(s) por <?= $m($resumen['canceladas_monto']) ?>
                    (no se suman al total).
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Arqueo -->
    <div class="panel">
        <div class="panel-head"><span><i class="bi bi-calculator"></i> Arqueo de caja</span></div>
        <div class="panel-body">
            <form method="POST" action="index.php?action=caja_cerrar_guardar" id="formCierre">
                <input type="hidden" name="id_caja" value="<?= $actual['id_caja'] ?>">

                <?php
                $campos = [
                    'efectivo'      => ['Efectivo contado',                 'bi-cash-stack',          'Efectivo'],
                    'transferencia' => ['Transferencias recibidas',         'bi-bank',                'Transferencia'],
                    'credito'       => ['Tarjeta crédito', 'bi-credit-card',         'Tarjeta Crédito'],
                    'debito'        => ['Tarjeta débito',  'bi-credit-card-2-front', 'Tarjeta Débito'],
                ];
                ?>

                <div class="form-grid">
                    <?php foreach ($campos as $slug => [$etiqueta, $icono, $nombre]): ?>
                    <div class="campo col-3">
                        <label for="dec_<?= $slug ?>"><?= $etiqueta ?></label>
                        <div class="input-wrap">
                            <i class="bi <?= $icono ?> icono"></i>
                            <input type="number" id="dec_<?= $slug ?>" name="declarado[<?= $slug ?>]"
                                class="inp-declarado" data-slug="<?= $slug ?>"
                                data-esperado="<?= number_format($esperados[$nombre], 2, '.', '') ?>"
                                min="0" max="99999999" step="0.01" required>
                        </div>
                        <small>Esperado: <b><?= $m($esperados[$nombre]) ?></b>
                            · <span id="dif_<?= $slug ?>">—</span></small>
                    </div>
                    <?php endforeach; ?>

                    <div class="campo col-6">
                        <label>Diferencia total</label>
                        <div class="stock-box" style="margin-bottom:0">
                            <i class="bi bi-plus-slash-minus"></i>
                            <div>
                                <b id="difTexto">—</b>
                                <small id="difLabel">Cargá los montos de cada método</small>
                            </div>
                        </div>
                    </div>

                    <div class="campo col-6">
                        <label for="observacion">Observación <small id="obsAyuda">(opcional)</small></label>
                        <div class="input-wrap area">
                            <i class="bi bi-chat-left-text icono"></i>
                            <textarea id="observacion" name="observacion" rows="3" maxlength="255"
                                    placeholder="Obligatoria si algún método tiene faltante o sobrante."></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn btn-danger"><i class="bi bi-lock"></i> Cerrar caja</button>
                    <a href="index.php?action=caja" class="btn btn-ghost">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

</main>

<script>
const inputs = document.querySelectorAll('.inp-declarado');
const txt    = document.getElementById('difTexto');
const lbl    = document.getElementById('difLabel');
const obs    = document.getElementById('observacion');
const ayuda  = document.getElementById('obsAyuda');
const fmt    = n => '$' + Math.abs(n).toLocaleString('es-AR', {minimumFractionDigits: 2, maximumFractionDigits: 2});

function pintar(el, d) {
    if (d < 0)      { el.style.color = 'var(--bad)';  return 'Faltante ' + fmt(d); }
    else if (d > 0) { el.style.color = 'var(--warn)'; return 'Sobrante ' + fmt(d); }
    el.style.color = 'var(--ok)';
    return 'Sin diferencia';
}

function actualizar() {
    let hayDif = false, total = 0, cargados = 0;

    inputs.forEach(inp => {
        const out = document.getElementById('dif_' + inp.dataset.slug);
        const v   = parseFloat(inp.value);
        if (isNaN(v)) { out.textContent = '—'; out.style.color = ''; return; }

        const d = Math.round((v - parseFloat(inp.dataset.esperado)) * 100) / 100;
        out.textContent = pintar(out, d);
        total += d;
        cargados++;
        if (d !== 0) hayDif = true;
    });

    if (cargados === 0) {
        txt.textContent = '—'; txt.style.color = '';
        lbl.textContent = 'Cargá los montos de cada método';
    } else {
        total = Math.round(total * 100) / 100;
        txt.textContent = fmt(total);
        lbl.textContent = pintar(txt, total) + (cargados < inputs.length ? ' (parcial)' : '');
    }

    obs.required = hayDif;
    ayuda.textContent = hayDif ? '(obligatoria por la diferencia)' : '(opcional)';
}
inputs.forEach(inp => inp.addEventListener('input', actualizar));

document.getElementById('formCierre').addEventListener('submit', e => {
    if (!confirm('¿Cerrar la caja? Después del cierre no se podrán cancelar las ventas de este turno.')) {
        e.preventDefault();
    }
});
</script>

</body>
</html>