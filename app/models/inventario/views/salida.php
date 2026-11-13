<?php
$errores = [
    'cantidad_invalida'   => 'La cantidad debe ser mayor a cero.',
    'motivo_requerido'    => 'Indicá el motivo de la salida (ej: producto dañado).',
    'motivo_largo'        => 'El motivo no puede superar los 500 caracteres.',
    'stock_insuficiente'  => 'No hay suficiente stock disponible para esa cantidad.',
    'no_existe'           => 'El registro de inventario no existe.',
    'proveedor_requerido' => 'Para enviar el correo tenés que seleccionar un proveedor.',
    'proveedor_invalido'  => 'El proveedor seleccionado no existe.',
    'proveedor_sin_email' => 'Ese proveedor no tiene correo cargado. Editalo en el módulo Proveedores.',
    'proveedor_no_corresponde' => 'Solo el proveedor que realizó la entrada de este producto puede recibir la salida.',
];
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrar Salida | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap estrecho">

    <div class="page-head">
        <div>
            <div class="page-label">Inventario</div>
            <h1 class="page-titulo"><i class="bi bi-box-arrow-up"></i> Registrar salida</h1>
            <p class="page-sub"><strong><?= htmlspecialchars($item['nombre_producto']) ?></strong></p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=inventario" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Volver</a>
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

            <div class="stock-box">
                <i class="bi bi-boxes"></i>
                <div>
                    <small>Stock actual</small>
                    <b><?= $item['stock_actual'] ?></b> unidades
                </div>
            </div>

            <form action="index.php?action=inventario_salida_guardar" method="POST">

                <input type="hidden" name="id_inventario" value="<?= $item['id_inventario'] ?>">

                <div class="form-grid">

                    <div class="campo col-2">
                        <label for="cantidad">Cantidad que sale</label>
                        <div class="input-wrap">
                            <i class="bi bi-dash-circle icono"></i>
                            <input type="number" id="cantidad" name="cantidad" min="1"
                                max="<?= $item['stock_actual'] ?>" required autofocus>
                        </div>
                    </div>

                    <div class="campo" style="grid-column:span 4">
                        <label for="id_proveedor">Proveedor <small>(solo quienes hicieron la entrada de este producto; obligatorio si se envía correo)</small></label>
                        <div class="input-wrap">
                            <i class="bi bi-truck icono"></i>
                            <select id="id_proveedor" name="id_proveedor">
                                <option value="">— Sin proveedor —</option>
                                <?php foreach ($proveedores as $pv): ?>
                                    <option value="<?= $pv['id_proveedor'] ?>">
                                        <?= htmlspecialchars($pv['nombre']) ?><?= empty($pv['email']) ? ' (sin correo)' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if (empty($proveedores)): ?>
                            <small>Este producto no tiene entradas registradas con proveedor, por eso no hay proveedores disponibles.</small>
                        <?php endif; ?>
                    </div>

                    <div class="campo" style="grid-column:span 6">
                        <label for="motivo">Motivo <small>(se guarda en el historial)</small></label>
                        <div class="input-wrap">
                            <textarea id="motivo" name="motivo" rows="5" maxlength="500" required
                                    style="width:100%;resize:vertical;font:inherit;padding:.7rem .9rem;"
                                    placeholder="Redactá el motivo: producto dañado, falla de fábrica, devolución, etc."></textarea>
                        </div>
                        <small><span id="contador">0</span>/500</small>
                    </div>

                    <div class="campo" style="grid-column:span 6">
                        <label style="display:flex;align-items:center;gap:.5rem;cursor:pointer;">
                            <input type="checkbox" id="enviar_correo" name="enviar_correo" value="1">
                            <span><i class="bi bi-envelope"></i> Enviar este motivo por correo al proveedor</span>
                        </label>
                    </div>

                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn btn-danger"><i class="bi bi-check-circle"></i> Registrar salida</button>
                    <a href="index.php?action=inventario" class="btn btn-ghost">Cancelar</a>
                </div>

            </form>
        </div>
    </div>

</main>
<script>
const motivo   = document.getElementById('motivo');
const contador = document.getElementById('contador');
const chk      = document.getElementById('enviar_correo');
const sel      = document.getElementById('id_proveedor');

motivo.addEventListener('input', () => contador.textContent = motivo.value.length);

// Si se marca "enviar correo", el proveedor pasa a ser obligatorio
chk.addEventListener('change', () => { sel.required = chk.checked; });
</script>
</body>
</html>