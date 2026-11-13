<?php
$errores = [
    'campos_requeridos'   => 'Completá todos los campos obligatorios.',
    'porcentaje_invalido' => 'El porcentaje debe estar entre 1 y 100.',
    'monto_invalido'      => 'El monto fijo debe ser mayor a cero.',
    'fechas_invalidas'    => 'La fecha de fin no puede ser anterior a la de inicio.',
];
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Promoción | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css?v=<?= @filemtime(__DIR__ . '/../../../../public/css/gamastore.css') ?>" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap estrecho">

    <div class="page-head">
        <div>
            <div class="page-label">Promociones</div>
            <h1 class="page-titulo"><i class="bi bi-pencil-square"></i> Editar promoción</h1>
            <p class="page-sub">Modificá <strong><?= htmlspecialchars($promo['nombre']) ?></strong>.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=promociones" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Volver</a>
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
            <form method="POST" action="index.php?action=promociones_actualizar">

                <input type="hidden" name="id_promocion" value="<?= $promo['id_promocion'] ?>">

                <div class="form-grid">

                    <div class="campo col-3">
                        <label for="nombre">Nombre de la promoción</label>
                        <div class="input-wrap">
                            <i class="bi bi-tag icono"></i>
                            <input type="text" id="nombre" name="nombre" required
                                   value="<?= htmlspecialchars($promo['nombre']) ?>">
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="descripcion">Descripción / motivo</label>
                        <div class="input-wrap">
                            <i class="bi bi-card-text icono"></i>
                            <input type="text" id="descripcion" name="descripcion"
                                   value="<?= htmlspecialchars($promo['descripcion'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="tipo_descuento">Tipo de descuento</label>
                        <div class="input-wrap">
                            <i class="bi bi-percent icono"></i>
                            <select id="tipo_descuento" name="tipo_descuento" required onchange="cambiarTipo()">
                                <option value="porcentaje" <?= $promo['tipo_descuento'] === 'porcentaje' ? 'selected' : '' ?>>Porcentaje (%)</option>
                                <option value="monto_fijo" <?= $promo['tipo_descuento'] === 'monto_fijo' ? 'selected' : '' ?>>Monto fijo ($)</option>
                            </select>
                            <i class="bi bi-chevron-down flecha"></i>
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="input_valor" id="label_valor">Valor del descuento</label>
                        <div class="input-wrap">
                            <i class="bi bi-percent icono" id="icono_valor"></i>
                            <input type="number" id="input_valor" name="valor" min="1" step="0.01" required
                                   value="<?= $promo['tipo_descuento'] === 'porcentaje' ? $promo['descuento_porcentaje'] : $promo['monto_fijo'] ?>">
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="fecha_inicio">Fecha de inicio</label>
                        <div class="input-wrap">
                            <i class="bi bi-calendar-event icono"></i>
                            <input type="date" id="fecha_inicio" name="fecha_inicio" required
                                   value="<?= date('Y-m-d', strtotime($promo['fecha_inicio'])) ?>">
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="fecha_fin">Fecha de fin</label>
                        <div class="input-wrap">
                            <i class="bi bi-calendar-check icono"></i>
                            <input type="date" id="fecha_fin" name="fecha_fin" required
                                   value="<?= date('Y-m-d', strtotime($promo['fecha_fin'])) ?>">
                        </div>
                    </div>

                </div>

                <div class="separador-form"></div>

                <div class="sub-titulo">
                    <span><i class="bi bi-bullseye"></i> Ámbito de aplicación</span>
                </div>

                <div class="opciones">
                    <label class="opcion">
                        <input type="radio" name="ambito" id="ambito_producto" value="producto" checked onclick="mostrarAmbito('producto')">
                        <div class="caja">
                            <i class="bi bi-box-seam"></i>
                            <div><b>Productos específicos</b><small>Elegí uno o varios productos</small></div>
                        </div>
                    </label>
                    <label class="opcion">
                        <input type="radio" name="ambito" id="ambito_categoria" value="categoria" onclick="mostrarAmbito('categoria')">
                        <div class="caja">
                            <i class="bi bi-collection"></i>
                            <div><b>Toda una categoría</b><small>Reemplaza la selección actual</small></div>
                        </div>
                    </label>
                </div>

                <div id="bloque_producto">
                    <div class="sub-titulo" style="margin-bottom:10px">
                        <span style="font-weight:500;font-size:13px;color:var(--muted)">Marcá los productos incluidos</span>
                        <span class="contador" id="contador">0 seleccionados</span>
                    </div>
                    <div class="lista-checks">
                        <?php foreach ($productos as $p): ?>
                            <label class="check" for="prod_<?= $p['id_producto'] ?>">
                                <input type="checkbox" name="productos[]"
                                       value="<?= $p['id_producto'] ?>" id="prod_<?= $p['id_producto'] ?>"
                                       <?= in_array($p['id_producto'], $productosAsignados) ? 'checked' : '' ?>>
                                <?= htmlspecialchars($p['nombre']) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div id="bloque_categoria" class="hide">
                    <div class="campo">
                        <label for="categoria_id">Categoría</label>
                        <div class="input-wrap">
                            <i class="bi bi-collection icono"></i>
                            <select id="categoria_id" name="categoria_id">
                                <option value="">Seleccionar categoría</option>
                                <?php foreach ($categorias as $c): ?>
                                    <option value="<?= $c['id_categoria'] ?>"><?= htmlspecialchars($c['tipodezapatilla']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <i class="bi bi-chevron-down flecha"></i>
                        </div>
                    </div>
                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn btn-main"><i class="bi bi-save"></i> Guardar cambios</button>
                    <a href="index.php?action=promociones" class="btn btn-ghost">Cancelar</a>
                </div>

            </form>
        </div>
    </div>

</main>

<script>
function cambiarTipo() {
    const tipo  = document.getElementById('tipo_descuento').value;
    const input = document.getElementById('input_valor');
    const label = document.getElementById('label_valor');
    const icono = document.getElementById('icono_valor');

    if (tipo === 'porcentaje') {
        label.textContent = 'Valor del descuento (%)';
        input.max = 100;
        icono.className = 'bi bi-percent icono';
    } else {
        label.textContent = 'Valor del descuento ($)';
        input.removeAttribute('max');
        icono.className = 'bi bi-currency-dollar icono';
    }
}

function mostrarAmbito(tipo) {
    document.getElementById('bloque_producto').classList.toggle('hide', tipo !== 'producto');
    document.getElementById('bloque_categoria').classList.toggle('hide', tipo !== 'categoria');
}

// Contador de productos seleccionados
const contador = document.getElementById('contador');
function actualizarContador() {
    const n = document.querySelectorAll('#bloque_producto input[type="checkbox"]:checked').length;
    contador.textContent = n + (n === 1 ? ' seleccionado' : ' seleccionados');
}
document.querySelectorAll('#bloque_producto input[type="checkbox"]').forEach(c => {
    c.addEventListener('change', actualizarContador);
});

cambiarTipo();
actualizarContador();
</script>

</body>
</html>