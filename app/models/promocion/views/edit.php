<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Promoción - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-pencil-square"></i> Editar Promoción</h2>
                <a href="index.php?action=promociones" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
            </div>

            <?php
            $errores = [
                'campos_requeridos'  => 'Completá todos los campos obligatorios.',
                'porcentaje_invalido'=> 'El porcentaje debe estar entre 1 y 100.',
                'monto_invalido'     => 'El monto fijo debe ser mayor a cero.',
                'fechas_invalidas'   => 'La fecha de fin no puede ser anterior a la de inicio.',
            ];
            if (isset($_GET['error']) && isset($errores[$_GET['error']])): ?>
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill"></i> <?= $errores[$_GET['error']] ?>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST" action="index.php?action=promociones_actualizar">

                        <input type="hidden" name="id_promocion" value="<?= $promo['id_promocion'] ?>">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Nombre de la promoción</label>
                                <input type="text" name="nombre" class="form-control"
                                    value="<?= htmlspecialchars($promo['nombre']) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Descripción / motivo</label>
                                <input type="text" name="descripcion" class="form-control"
                                    value="<?= htmlspecialchars($promo['descripcion'] ?? '') ?>">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">Tipo de descuento</label>
                                <select name="tipo_descuento" id="tipo_descuento" class="form-select" required onchange="cambiarTipo()">
                                    <option value="porcentaje" <?= $promo['tipo_descuento'] === 'porcentaje' ? 'selected' : '' ?>>Porcentaje (%)</option>
                                    <option value="monto_fijo" <?= $promo['tipo_descuento'] === 'monto_fijo' ? 'selected' : '' ?>>Monto fijo ($)</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label" id="label_valor">Valor del descuento</label>
                                <input type="number" name="valor" id="input_valor" class="form-control"
                                    value="<?= $promo['tipo_descuento'] === 'porcentaje' ? $promo['descuento_porcentaje'] : $promo['monto_fijo'] ?>"
                                    min="1" step="0.01" required>
                            </div>
                            <div class="col-md-4"></div>

                            <div class="col-md-6">
                                <label class="form-label">Fecha de inicio</label>
                                <input type="date" name="fecha_inicio" class="form-control"
                                    value="<?= date('Y-m-d', strtotime($promo['fecha_inicio'])) ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Fecha de fin</label>
                                <input type="date" name="fecha_fin" class="form-control"
                                    value="<?= date('Y-m-d', strtotime($promo['fecha_fin'])) ?>" required>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h5><i class="bi bi-bullseye"></i> Ámbito de aplicación</h5>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="ambito" id="ambito_producto"
                                value="producto" checked onclick="mostrarAmbito('producto')">
                            <label class="form-check-label" for="ambito_producto">Productos específicos</label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="ambito" id="ambito_categoria"
                                value="categoria" onclick="mostrarAmbito('categoria')">
                            <label class="form-check-label" for="ambito_categoria">Toda una categoría (reemplaza la selección actual)</label>
                        </div>

                        <div id="bloque_producto" class="mb-3 border rounded p-3" style="max-height:220px; overflow-y:auto;">
                            <?php foreach ($productos as $p): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="productos[]"
                                        value="<?= $p['id_producto'] ?>" id="prod_<?= $p['id_producto'] ?>"
                                        <?= in_array($p['id_producto'], $productosAsignados) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="prod_<?= $p['id_producto'] ?>">
                                        <?= htmlspecialchars($p['nombre']) ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div id="bloque_categoria" class="mb-3" style="display:none;">
                            <select name="categoria_id" class="form-select">
                                <option value="">-- Seleccionar categoría --</option>
                                <?php foreach ($categorias as $c): ?>
                                    <option value="<?= $c['id_categoria'] ?>">
                                        <?= htmlspecialchars($c['tipodezapatilla']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <hr class="my-4">
                        <h5><i class="bi bi-people"></i> Clientes (opcional)</h5>
                        <select name="clientes[]" class="form-select" multiple size="5">
                            <?php foreach ($clientes as $c): ?>
                                <option value="<?= $c['id_cliente'] ?>"
                                    <?= in_array($c['id_cliente'], $clientesAsignados) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($c['nombre_completo']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <div class="mt-4 d-flex justify-content-between">
                            <a href="index.php?action=promociones" class="btn btn-outline-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-warning text-white">
                                <i class="bi bi-save"></i> Guardar Cambios
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function cambiarTipo() {
    const tipo  = document.getElementById('tipo_descuento').value;
    const input = document.getElementById('input_valor');
    const label = document.getElementById('label_valor');
    if (tipo === 'porcentaje') {
        label.textContent = 'Valor del descuento (%)';
        input.max = 100;
    } else {
        label.textContent = 'Valor del descuento ($)';
        input.removeAttribute('max');
    }
}
function mostrarAmbito(tipo) {
    document.getElementById('bloque_producto').style.display  = tipo === 'producto'  ? 'block' : 'none';
    document.getElementById('bloque_categoria').style.display = tipo === 'categoria' ? 'block' : 'none';
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>