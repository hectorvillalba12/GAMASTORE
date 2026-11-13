<?php $error = $_GET['error'] ?? ''; ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Inventario | GamaStore</title>
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
            <h1 class="page-titulo"><i class="bi bi-pencil-square"></i> Editar inventario</h1>
            <p class="page-sub"><strong><?= htmlspecialchars($item['nombre_producto']) ?></strong></p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=inventario" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </div>

    <?php if ($error === 'stock_invalido'): ?>
        <div class="alerta danger">
            <i class="bi bi-exclamation-triangle-fill"></i> El stock máximo no puede ser menor al stock mínimo.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <div class="aviso">
        <i class="bi bi-info-circle"></i>
        <div>
            Si modificás el "Stock actual" acá, queda registrado como un <strong>ajuste manual</strong> en el historial.
            Para entradas o salidas normales, usá los botones correspondientes desde el listado.
        </div>
    </div>

    <div class="panel">
        <div class="panel-body">
            <form action="index.php?action=inventario_actualizar" method="POST">

                <input type="hidden" name="id_inventario"        value="<?= $item['id_inventario'] ?>">
                <input type="hidden" name="producto_id_producto" value="<?= $item['producto_id_producto'] ?>">

                <div class="form-grid">

                    <div class="campo col-3">
                        <label>Producto</label>
                        <div class="input-wrap">
                            <i class="bi bi-box-seam icono"></i>
                            <input type="text" value="<?= htmlspecialchars($item['nombre_producto'] . ' — talle ' . ($item['talle'] ?? '-')) ?>" disabled>
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="ubicacion">Ubicación</label>
                        <div class="input-wrap">
                            <i class="bi bi-geo-alt icono"></i>
                            <input type="text" id="ubicacion" name="ubicacion" placeholder="Ej: Estante A3"
                                   value="<?= htmlspecialchars($item['ubicacion'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="campo col-2">
                        <label for="stock_actual">Stock actual</label>
                        <div class="input-wrap">
                            <i class="bi bi-stack icono"></i>
                            <input type="number" id="stock_actual" name="stock_actual" min="0" required
                                   value="<?= $item['stock_actual'] ?>">
                        </div>
                    </div>

                    <div class="campo col-2">
                        <label for="stock_minimo">Stock mínimo</label>
                        <div class="input-wrap">
                            <i class="bi bi-arrow-down-circle icono"></i>
                            <input type="number" id="stock_minimo" name="stock_minimo" min="0" required
                                   value="<?= $item['stock_minimo'] ?>">
                        </div>
                    </div>

                    <div class="campo col-2">
                        <label for="stock_maximo">Stock máximo</label>
                        <div class="input-wrap">
                            <i class="bi bi-arrow-up-circle icono"></i>
                            <input type="number" id="stock_maximo" name="stock_maximo" min="0" required
                                   value="<?= $item['stock_maximo'] ?>">
                        </div>
                    </div>

                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn btn-main"><i class="bi bi-save"></i> Guardar cambios</button>
                    <a href="index.php?action=inventario" class="btn btn-ghost">Cancelar</a>
                </div>

            </form>
        </div>
    </div>

</main>

</body>
</html>