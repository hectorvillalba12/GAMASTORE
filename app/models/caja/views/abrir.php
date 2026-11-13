<?php
$errores = [
    'monto_invalido'   => 'Ingresá un monto inicial válido (número positivo, hasta 2 decimales).',
    'error_al_guardar' => 'Ocurrió un error al abrir la caja. Intentá de nuevo.',
];
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Abrir caja | GamaStore</title>
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
            <h1 class="page-titulo"><i class="bi bi-unlock"></i> Abrir caja</h1>
            <p class="page-sub">Indicá el dinero con el que arranca el turno.</p>
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
            <form method="POST" action="index.php?action=caja_abrir_guardar">

                <div class="form-grid">
                    <div class="campo col-3">
                        <label for="monto_inicial">Monto inicial <small>(efectivo en caja)</small></label>
                        <div class="input-wrap">
                            <i class="bi bi-currency-dollar icono"></i>
                            <input type="number" id="monto_inicial" name="monto_inicial"
                                min="0" max="99999999" step="0.01"
                                value="<?= $montoArrastrado !== null ? number_format($montoArrastrado, 2, '.', '') : '0' ?>"
                                <?= $montoArrastrado !== null ? 'readonly' : 'autofocus' ?> required>
                        </div>
                        <?php if ($montoArrastrado !== null): ?>
                            <small>Se arrastra el efectivo contado al cerrar la caja anterior.</small>
                        <?php endif; ?>
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
                        <label for="observacion">Observación <small>(opcional)</small></label>
                        <div class="input-wrap area">
                            <i class="bi bi-chat-left-text icono"></i>
                            <textarea id="observacion" name="observacion" rows="3" maxlength="255"
                                    placeholder="Ej: turno mañana, billetes chicos para vuelto..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn btn-main"><i class="bi bi-unlock"></i> Abrir caja</button>
                    <a href="index.php?action=caja" class="btn btn-ghost">Cancelar</a>
                </div>

            </form>
        </div>
    </div>

</main>

</body>
</html>