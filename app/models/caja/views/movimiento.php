<?php
$m = fn($n) => '$' . number_format((float)$n, 2, ',', '.');

$errores = [
    'monto_invalido'     => 'Ingresá un monto válido, mayor a cero (hasta 2 decimales).',
    'motivo_requerido'   => 'Indicá el motivo del movimiento.',
    'saldo_insuficiente' => 'El egreso supera el efectivo disponible en caja.',
    'error_al_guardar'   => 'Ocurrió un error al registrar el movimiento. Intentá de nuevo.',
];
$error     = $_GET['error'] ?? '';
$esIngreso = ($tipo === 'ingreso');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $esIngreso ? 'Ingreso' : 'Egreso' ?> de efectivo | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap estrecho">

    <div class="page-head">
        <div>
            <div class="page-label">Caja #<?= $actual['id_caja'] ?></div>
            <h1 class="page-titulo">
                <i class="bi <?= $esIngreso ? 'bi-box-arrow-in-down' : 'bi-box-arrow-up' ?>"></i>
                <?= $esIngreso ? 'Ingreso' : 'Egreso' ?> de efectivo
            </h1>
            <p class="page-sub">
                <?= $esIngreso ? 'Dinero que entra a la caja fuera de una venta (ej: cambio extra).'
                               : 'Dinero que sale de la caja (ej: pago a proveedor, retiro).' ?>
            </p>
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

    <div class="panel">
        <div class="panel-body">

            <!-- Cambiar entre ingreso y egreso -->
            <div class="page-btns" style="margin-bottom:16px">
                <a href="index.php?action=caja_movimiento&tipo=ingreso"
                   class="btn <?= $esIngreso ? 'btn-main' : 'btn-ghost' ?>">
                    <i class="bi bi-box-arrow-in-down"></i> Ingreso
                </a>
                <a href="index.php?action=caja_movimiento&tipo=egreso"
                   class="btn <?= !$esIngreso ? 'btn-main' : 'btn-ghost' ?>">
                    <i class="bi bi-box-arrow-up"></i> Egreso
                </a>
            </div>

            <div class="kpis">
                <div class="kpi verde">
                    <small>Efectivo disponible en caja</small>
                    <b><?= $m($disponible) ?></b>
                </div>
            </div>

            <form method="POST" action="index.php?action=caja_movimiento_guardar">
                <input type="hidden" name="id_caja" value="<?= $actual['id_caja'] ?>">
                <input type="hidden" name="tipo" value="<?= $tipo ?>">

                <div class="form-grid">
                    <div class="campo col-3">
                        <label for="monto">Monto</label>
                        <div class="input-wrap">
                            <i class="bi bi-currency-dollar icono"></i>
                            <input type="number" id="monto" name="monto" min="0.01" max="99999999"
                                   step="0.01" required autofocus
                                   <?= !$esIngreso ? 'data-max="' . number_format($disponible, 2, '.', '') . '"' : '' ?>>
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="responsable">Responsable</label>
                        <div class="input-wrap">
                            <i class="bi bi-person icono"></i>
                            <input type="text" id="responsable" disabled
                                   value="<?= htmlspecialchars($_SESSION['usuario']['email'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="campo col-6">
                        <label for="motivo">Motivo <small>(obligatorio)</small></label>
                        <div class="input-wrap area">
                            <i class="bi bi-chat-left-text icono"></i>
                            <textarea id="motivo" name="motivo" rows="3" maxlength="150" required
                                placeholder="<?= $esIngreso ? 'Ej: cambio en billetes chicos' : 'Ej: pago a proveedor de limpieza' ?>"></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn <?= $esIngreso ? 'btn-main' : 'btn-danger' ?>">
                        <i class="bi bi-check2-circle"></i> Registrar <?= $esIngreso ? 'ingreso' : 'egreso' ?>
                    </button>
                    <a href="index.php?action=caja" class="btn btn-ghost">Cancelar</a>
                </div>
            </form>
        </div>
    </div>

</main>

<?php if (!$esIngreso): ?>
<script>
// Aviso rápido en el navegador (la validación real se hace en el servidor)
document.querySelector('form').addEventListener('submit', e => {
    const inp = document.getElementById('monto');
    if (parseFloat(inp.value) > parseFloat(inp.dataset.max)) {
        e.preventDefault();
        alert('El egreso supera el efectivo disponible en caja.');
    }
});
</script>
<?php endif; ?>

</body>
</html>