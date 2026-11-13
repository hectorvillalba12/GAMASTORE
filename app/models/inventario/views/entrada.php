<?php
$errores = [
    'cantidad_invalida'  => 'La cantidad debe ser mayor a cero.',
    'motivo_requerido'   => 'Indicá el motivo de la entrada (ej: compra a proveedor).',
    'proveedor_requerido'=> 'Seleccioná el proveedor del que llegó el producto.',
    'proveedor_invalido' => 'El proveedor seleccionado no existe.',
];
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registrar Entrada | GamaStore</title>
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
            <h1 class="page-titulo"><i class="bi bi-box-arrow-in-down"></i> Registrar entrada</h1>
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

            <form action="index.php?action=inventario_entrada_guardar" method="POST">

                <input type="hidden" name="id_inventario" value="<?= $item['id_inventario'] ?>">

                <div class="form-grid">
                    <div class="campo" style="grid-column:span 4">
                        <label for="id_proveedor">Proveedor</label>
                        <div class="input-wrap">
                            <i class="bi bi-truck icono"></i>
                            <select id="id_proveedor" name="id_proveedor" required>
                                <option value="">— Seleccioná un proveedor —</option>
                                <?php foreach ($proveedores as $pv): ?>
                                    <option value="<?= $pv['id_proveedor'] ?>"><?= htmlspecialchars($pv['nombre']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if (empty($proveedores)): ?>
                            <small>No hay proveedores activos. <a href="index.php?action=proveedores_crear">Registrá uno primero</a>.</small>
                        <?php endif; ?>
                    </div>

                    <div class="campo col-2">
                        <label for="cantidad">Cantidad que ingresa</label>
                        <div class="input-wrap">
                            <i class="bi bi-plus-circle icono"></i>
                            <input type="number" id="cantidad" name="cantidad" min="1" required autofocus>
                        </div>
                    </div>

                    <div class="campo" style="grid-column:span 4">
                        <label for="motivo">Motivo</label>
                        <div class="input-wrap">
                            <i class="bi bi-chat-left-text icono"></i>
                            <input type="text" id="motivo" name="motivo"
                                placeholder="Ej: Compra a proveedor, devolución de cliente, etc." required>
                        </div>
                    </div>

                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn btn-ok"><i class="bi bi-check-circle"></i> Registrar entrada</button>
                    <a href="index.php?action=inventario" class="btn btn-ghost">Cancelar</a>
                </div>

            </form>
        </div>
    </div>

</main>

</body>
</html>