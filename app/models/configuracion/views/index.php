<?php
$errores = [
    'marca_vacia'         => 'El nombre de la marca es obligatorio.',
    'marca_duplicada'     => 'Ya existe una marca con ese nombre.',
    'categoria_vacia'     => 'El nombre de la categoría es obligatorio.',
    'categoria_duplicada' => 'Ya existe una categoría con ese nombre.',
    'talle_vacio'         => 'El talle es obligatorio.',
    'talle_invalido'      => 'Ingresá un talle numérico válido (por ejemplo 38 o 38.5).',
    'talle_duplicado'     => 'Ya existe ese talle.',
];
$error = $_GET['error'] ?? '';

// Si hubo un error, se vuelve a abrir el modal que corresponde
$abrirModal = '';
if ($error !== '') {
    if (strpos($error, 'marca') !== false)          $abrirModal = 'modalNuevaMarca';
    elseif (strpos($error, 'categoria') !== false)  $abrirModal = 'modalNuevaCategoria';
    elseif (strpos($error, 'talle') !== false)      $abrirModal = 'modalNuevoTalle';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Configuraciones | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css?v=<?= @filemtime(__DIR__ . '/../../../../public/css/gamastore.css') ?>" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap">

    <!-- Encabezado -->
    <div class="page-head">
        <div>
            <div class="page-label">Módulo de gestión</div>
            <h1 class="page-titulo"><i class="bi bi-gear"></i> Configuraciones</h1>
            <p class="page-sub">Gestionar las marcas y categorías que se usan en los productos.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=dashboard" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Mensajes -->
    <?php if (isset($errores[$error])): ?>
        <div class="alerta danger">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= $errores[$error] ?>
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['ok_marca'])): ?>
        <div class="alerta success">
            <i class="bi bi-check-circle-fill"></i> Marca creada correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['ok_categoria'])): ?>
        <div class="alerta success">
            <i class="bi bi-check-circle-fill"></i> Categoría creada correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['ok_talle'])): ?>
        <div class="alerta success">
            <i class="bi bi-check-circle-fill"></i> Talle creado correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <div class="graf-grid">

        <!-- ===================== MARCAS ===================== -->
        <div class="panel">
            <div class="panel-head">
                <span><i class="bi bi-award"></i> Marcas
                    <span class="cuenta" style="margin-left:8px"><?= count($marcas ?? []) ?></span>
                </span>
                <button type="button" class="btn btn-main btn-sm" data-abrir="modalNuevaMarca">
                    <i class="bi bi-plus-lg"></i> Nueva marca
                </button>
            </div>
            <div class="tabla-wrap">
                <table class="tabla" style="min-width:0">
                    <thead>
                        <tr>
                            <th style="width:70px">#</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($marcas)): ?>
                        <tr><td colspan="2" class="vacio">No hay marcas cargadas.</td></tr>
                    <?php else: ?>
                        <?php foreach ($marcas as $m): ?>
                        <tr>
                            <td class="id"><?= $m['id_marca'] ?></td>
                            <td class="nombre-prod"><?= htmlspecialchars($m['marcas_disponibles']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- ===================== CATEGORÍAS ===================== -->
        <div class="panel">
            <div class="panel-head">
                <span><i class="bi bi-tags"></i> Categorías
                    <span class="cuenta" style="margin-left:8px"><?= count($categorias ?? []) ?></span>
                </span>
                <button type="button" class="btn btn-main btn-sm" data-abrir="modalNuevaCategoria">
                    <i class="bi bi-plus-lg"></i> Nueva categoría
                </button>
            </div>
            <div class="tabla-wrap">
                <table class="tabla" style="min-width:0">
                    <thead>
                        <tr>
                            <th style="width:70px">#</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($categorias)): ?>
                        <tr><td colspan="2" class="vacio">No hay categorías cargadas.</td></tr>
                    <?php else: ?>
                        <?php foreach ($categorias as $c): ?>
                        <tr>
                            <td class="id"><?= $c['id_categoria'] ?></td>
                            <td class="nombre-prod"><?= htmlspecialchars($c['tipodezapatilla']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
<!-- ===================== TALLES ===================== -->
        <div class="panel">
            <div class="panel-head">
                <span><i class="bi bi-rulers"></i> Talles
                    <span class="cuenta" style="margin-left:8px"><?= count($talles ?? []) ?></span>
                </span>
                <button type="button" class="btn btn-main btn-sm" data-abrir="modalNuevoTalle">
                    <i class="bi bi-plus-lg"></i> Nuevo talle
                </button>
            </div>
            <div class="tabla-wrap">
                <table class="tabla" style="min-width:0">
                    <thead>
                        <tr>
                            <th style="width:70px">#</th>
                            <th>Talle</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($talles)): ?>
                        <tr><td colspan="2" class="vacio">No hay talles cargados.</td></tr>
                    <?php else: ?>
                        <?php foreach ($talles as $t): ?>
                        <tr>
                            <td class="id"><?= $t['id_talle'] ?></td>
                            <td class="nombre-prod"><?= htmlspecialchars($t['talles_disponibles']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>

<!-- MODAL: Nueva marca -->
<div class="overlay" id="modalNuevaMarca">
    <form class="modal form" method="POST" action="index.php?action=guardar_marca">
        <div class="modal-head">
            <h2><i class="bi bi-award"></i> Nueva marca</h2>
            <button type="button" class="cerrar" data-cerrar aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-body">
            <div class="campo">
                <label for="marcas_disponibles">Nombre de la marca</label>
                <div class="input-wrap">
                    <i class="bi bi-award icono"></i>
                    <input type="text" id="marcas_disponibles" name="marcas_disponibles" placeholder="Ej: Reebok" required>
                </div>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-ghost" data-cerrar>Cancelar</button>
            <button type="submit" class="btn btn-main"><i class="bi bi-save"></i> Guardar</button>
        </div>
    </form>
</div>

<!-- MODAL: Nueva categoría -->
<div class="overlay" id="modalNuevaCategoria">
    <form class="modal form" method="POST" action="index.php?action=guardar_categoria">
        <div class="modal-head">
            <h2><i class="bi bi-tags"></i> Nueva categoría</h2>
            <button type="button" class="cerrar" data-cerrar aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-body">
            <div class="campo">
                <label for="tipodezapatilla">Nombre de la categoría</label>
                <div class="input-wrap">
                    <i class="bi bi-tags icono"></i>
                    <input type="text" id="tipodezapatilla" name="tipodezapatilla" placeholder="Ej: Skate" required>
                </div>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-ghost" data-cerrar>Cancelar</button>
            <button type="submit" class="btn btn-main"><i class="bi bi-save"></i> Guardar</button>
        </div>
    </form>
</div>
<!-- MODAL: Nuevo talle -->
<div class="overlay" id="modalNuevoTalle">
    <form class="modal form" method="POST" action="index.php?action=guardar_talle">
        <div class="modal-head">
            <h2><i class="bi bi-rulers"></i> Nuevo talle</h2>
            <button type="button" class="cerrar" data-cerrar aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-body">
            <div class="campo">
                <label for="talles_disponibles">Talle</label>
                <div class="input-wrap">
                    <i class="bi bi-rulers icono"></i>
                    <input type="text" id="talles_disponibles" name="talles_disponibles"
                        placeholder="Ej: 46" maxlength="4" required>
                </div>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-ghost" data-cerrar>Cancelar</button>
            <button type="submit" class="btn btn-main"><i class="bi bi-save"></i> Guardar</button>
        </div>
    </form>
</div>
<script>
function abrirModal(id) {
    const ov = document.getElementById(id);
    ov.classList.add('abierto');
    setTimeout(() => { const i = ov.querySelector('input[type="text"]'); if (i) i.focus(); }, 50);
}
function cerrarModal(ov) { ov.classList.remove('abierto'); }

document.querySelectorAll('[data-abrir]').forEach(b => {
    b.addEventListener('click', () => abrirModal(b.dataset.abrir));
});
document.querySelectorAll('[data-cerrar]').forEach(b => {
    b.addEventListener('click', () => cerrarModal(b.closest('.overlay')));
});
document.querySelectorAll('.overlay').forEach(ov => {
    ov.addEventListener('click', e => { if (e.target === ov) cerrarModal(ov); });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.querySelectorAll('.overlay.abierto').forEach(cerrarModal);
});

<?php if ($abrirModal !== ''): ?>
// Hubo un error al guardar: se vuelve a abrir el modal correspondiente
window.addEventListener('DOMContentLoaded', () => abrirModal('<?= $abrirModal ?>'));
<?php endif; ?>
</script>

</body>
</html>