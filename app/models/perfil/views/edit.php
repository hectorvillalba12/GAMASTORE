<?php
$error = $_GET['error'] ?? '';

$infoModulos = [
    'dashboard'       => ['bi-grid',          'Panel principal'],
    'productos'       => ['bi-box-seam',      'Catálogo, talles y marcas'],
    'clientes'        => ['bi-people',        'Registro de clientes'],
    'inventario'      => ['bi-boxes',         'Stock y movimientos'],
    'ventas'          => ['bi-cart-check',    'Registro y consulta de ventas'],
    'usuarios'        => ['bi-person-gear',   'Roles y perfiles de usuarios'],
    'perfiles'        => ['bi-person-badge',  'Módulos por perfil'],
    'promociones'     => ['bi-tags',          'Descuentos y promociones'],
    'reportes'        => ['bi-graph-up',      'Ventas, stock y clientes'],
    'auditoria'       => ['bi-clock-history', 'Historial de cambios'],
    'configuraciones' => ['bi-gear',          'Marcas y categorías'],
    'proveedores'     => ['bi-truck',         'Proveedores de mercadería'],
    'caja'            => ['bi-cash-stack',    'Apertura y cierre de caja'],
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Perfil | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css?v=<?= @filemtime(__DIR__ . '/../../../../public/css/gamastore.css') ?>" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap estrecho">

    <div class="page-head">
        <div>
            <div class="page-label">Perfiles</div>
            <h1 class="page-titulo"><i class="bi bi-pencil-square"></i> Editar perfil</h1>
            <p class="page-sub">Modificá <strong><?= htmlspecialchars($perfil['nombre']) ?></strong> y sus módulos.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=perfiles" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </div>

    <?php if ($error === 'nombre_requerido'): ?>
        <div class="alerta danger">
            <i class="bi bi-exclamation-triangle-fill"></i> El nombre del perfil es obligatorio.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <div class="panel">
        <div class="panel-body">
            <form method="POST" action="index.php?action=update_perfil">

                <input type="hidden" name="id_perfil" value="<?= $perfil['id_perfil'] ?>">

                <div class="form-grid">
                    <div class="campo col-3">
                        <label for="nombre">Nombre del perfil <small>obligatorio</small></label>
                        <div class="input-wrap">
                            <i class="bi bi-person-badge icono"></i>
                            <input type="text" id="nombre" name="nombre" required
                                   value="<?= htmlspecialchars($perfil['nombre']) ?>">
                        </div>
                    </div>

                    <div class="campo col-3">
                        <label for="descripcion">Descripción</label>
                        <div class="input-wrap">
                            <i class="bi bi-card-text icono"></i>
                            <input type="text" id="descripcion" name="descripcion"
                                   value="<?= htmlspecialchars($perfil['descripcion'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="separador-form"></div>

                <div class="sub-titulo">
                    <span><i class="bi bi-grid-3x3-gap"></i> Módulos asignados</span>
                    <div class="grupo">
                        <span class="contador" id="contador">0 seleccionados</span>
                        <button type="button" class="btn btn-ghost btn-sm" onclick="marcarTodos(true)">Todos</button>
                        <button type="button" class="btn btn-ghost btn-sm" onclick="marcarTodos(false)">Ninguno</button>
                    </div>
                </div>

                <div class="opciones" id="listaModulos">
                    <?php foreach ($modulos as $m): ?>
                        <?php [$icono, $desc] = $infoModulos[$m] ?? ['bi-puzzle', 'Módulo del sistema']; ?>
                        <label class="opcion" for="mod_<?= $m ?>">
                            <input type="checkbox" name="modulos[]" value="<?= $m ?>" id="mod_<?= $m ?>"
                                   <?= in_array($m, $modulosDelPerfil) ? 'checked' : '' ?>>
                            <div class="caja">
                                <i class="bi <?= $icono ?>"></i>
                                <div class="txt"><b><?= ucfirst($m) ?></b><small><?= $desc ?></small></div>
                                <i class="bi bi-check-circle-fill tilde"></i>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>

                <div class="form-acciones">
                    <button type="submit" class="btn btn-main"><i class="bi bi-save"></i> Guardar cambios</button>
                    <a href="index.php?action=perfiles" class="btn btn-ghost">Cancelar</a>
                </div>

            </form>
        </div>
    </div>

</main>

<script>
const checks   = document.querySelectorAll('#listaModulos input[type="checkbox"]');
const contador = document.getElementById('contador');

function actualizarContador() {
    const n = document.querySelectorAll('#listaModulos input[type="checkbox"]:checked').length;
    contador.textContent = n + (n === 1 ? ' seleccionado' : ' seleccionados');
}
function marcarTodos(valor) {
    checks.forEach(c => c.checked = valor);
    actualizarContador();
}
checks.forEach(c => c.addEventListener('change', actualizarContador));
actualizarContador();
</script>

</body>
</html>