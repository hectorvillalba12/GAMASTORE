<?php
$errores = [
    'producto_requerido' => 'Seleccioná un producto.',
    'stock_invalido'     => 'El stock máximo no puede ser menor al stock mínimo.',
    'producto_duplicado' => 'Ese producto ya tiene una fila de inventario activa. Usá Entrada para sumar stock.',
];
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cargar Stock | GamaStore</title>
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
            <h1 class="page-titulo"><i class="bi bi-plus-circle"></i> Cargar stock manualmente</h1>
            <p class="page-sub">Registrá un producto en el inventario con su ubicación y límites de stock.</p>
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
            <form action="index.php?action=inventario_guardar" method="POST">

                <div class="form-grid">

                    <div class="campo col-3">
                        <label for="producto">Producto</label>
                        <div class="input-wrap">
                            <i class="bi bi-box-seam icono"></i>
                            <select id="producto" name="producto_id_producto" required>
                                <option value="">Seleccionar producto</option>
                                <?php foreach ($productos as $p): ?>
                                    <option value="<?= $p['id_producto'] ?>">
                                        <?= htmlspecialchars($p['nombre']) ?> — talle <?= htmlspecialchars($p['talle'] ?? '-') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <i class="bi bi-chevron-down flecha"></i>
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="ubicacion">Ubicación</label>
                        <div class="input-wrap">
                            <i class="bi bi-geo-alt icono"></i>
                            <input type="text" id="ubicacion" name="ubicacion" placeholder="Ej: Estante A3">
                        </div>
                    </div>

                    <div class="campo col-2">
                        <label for="stock_actual">Stock actual</label>
                        <div class="input-wrap">
                            <i class="bi bi-stack icono"></i>
                            <input type="number" id="stock_actual" name="stock_actual" value="0" min="0" required>
                        </div>
                    </div>

                    <div class="campo col-2">
                        <label for="stock_minimo">Stock mínimo</label>
                        <div class="input-wrap">
                            <i class="bi bi-arrow-down-circle icono"></i>
                            <input type="number" id="stock_minimo" name="stock_minimo" value="0" min="0" required>
                        </div>
                    </div>

                    <div class="campo col-2">
                        <label for="stock_maximo">Stock máximo</label>
                        <div class="input-wrap">
                            <i class="bi bi-arrow-up-circle icono"></i>
                            <input type="number" id="stock_maximo" name="stock_maximo" value="0" min="0" required>
                        </div>
                    </div>

                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn btn-main"><i class="bi bi-save"></i> Guardar</button>
                    <a href="index.php?action=inventario" class="btn btn-ghost">Cancelar</a>
                </div>

            </form>
        </div>
    </div>

</main>

</body>
</html>