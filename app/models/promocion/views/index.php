<?php
$claseEstado = [
    'vigente'  => 'normal',
    'proxima'  => 'ajuste',
    'vencida'  => 'neutro',
    'inactiva' => 'neutro',
];
$textoEstado = [
    'vigente'  => 'Vigente',
    'proxima'  => 'Próxima',
    'vencida'  => 'Vencida',
    'inactiva' => 'Inactiva',
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Promociones | GamaStore</title>
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
            <h1 class="page-titulo"><i class="bi bi-tags"></i> Promociones y descuentos</h1>
            <p class="page-sub">Descuentos y promociones vigentes de GamaStore.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=dashboard" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="index.php?action=promociones_crear" class="btn btn-main">
                <i class="bi bi-plus-lg"></i> Nueva promoción
            </a>
        </div>
    </div>

    <!-- Mensajes -->
    <?php if (isset($_GET['ok'])): ?>
        <div class="alerta success">
            <i class="bi bi-check-circle-fill"></i> Promoción guardada correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['baja'])): ?>
        <div class="alerta warning">
            <i class="bi bi-x-circle"></i> Promoción desactivada.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['reactivado'])): ?>
        <div class="alerta success">
            <i class="bi bi-check-circle"></i> Promoción reactivada.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Tabla -->
    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-percent"></i> Listado de promociones</span>
            <span class="cuenta"><?= count($promociones ?? []) ?> en total</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:860px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th class="centro">Descuento</th>
                        <th>Vigencia</th>
                        <th class="centro">Estado</th>
                        <th class="centro">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($promociones)): ?>
                    <tr><td colspan="7" class="vacio">No hay promociones cargadas.</td></tr>
                <?php else: ?>
                    <?php foreach ($promociones as $p): ?>
                    <tr class="<?= $p['activa'] ? '' : 'apagada' ?>">
                        <td class="id"><?= $p['id_promocion'] ?></td>
                        <td>
                            <span class="nombre-prod"><?= htmlspecialchars($p['nombre']) ?></span>
                            <?php if ($p['descripcion']): ?>
                                <span class="sub-celda"><?= htmlspecialchars($p['descripcion']) ?></span>
                            <?php endif; ?>
                        </td>
                        <td><?= $p['tipo_descuento'] === 'porcentaje' ? 'Porcentaje' : 'Monto fijo' ?></td>
                        <td class="centro">
                            <span class="chip">
                                <?= $p['tipo_descuento'] === 'porcentaje'
                                    ? number_format($p['descuento_porcentaje'], 0) . '%'
                                    : '$' . number_format($p['monto_fijo'], 2, ',', '.') ?>
                            </span>
                        </td>
                        <td style="white-space:nowrap">
                            <?= date('d/m/Y', strtotime($p['fecha_inicio'])) ?>
                            al
                            <?= date('d/m/Y', strtotime($p['fecha_fin'])) ?>
                        </td>
                        <td class="centro">
                            <span class="estado <?= $claseEstado[$p['estado']] ?>">
                                <?= $textoEstado[$p['estado']] ?>
                            </span>
                        </td>
                        <td>
                            <div class="acciones-fila">
                                <a href="index.php?action=promociones_editar&id=<?= $p['id_promocion'] ?>" class="btn-icon editar" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php if ($p['activa']): ?>
                                    <button type="button" class="btn-icon baja js-baja" title="Desactivar"
                                            data-id="<?= $p['id_promocion'] ?>"
                                            data-nombre="<?= htmlspecialchars($p['nombre']) ?>">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                <?php else: ?>
                                    <a href="index.php?action=promociones_reactivar&id=<?= $p['id_promocion'] ?>" class="btn-icon ok" title="Reactivar">
                                        <i class="bi bi-check-circle"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

</main>

<!-- Modal confirmar desactivación -->
<div class="overlay" id="modalBaja">
    <div class="modal" role="dialog" aria-modal="true">
        <div class="modal-head">
            <h2><i class="bi bi-x-circle"></i> Desactivar promoción</h2>
            <button type="button" class="cerrar" id="cerrarBaja" aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-body">
            <p>¿Desactivar la promoción <strong id="nombrePromoBaja"></strong>?</p>
            <div class="nota">
                <i class="bi bi-info-circle"></i>
                <span>No se elimina: queda inactiva y la podés reactivar cuando quieras.</span>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-ghost" id="cancelarBaja">Cancelar</button>
            <a id="btnConfirmarBaja" href="#" class="btn btn-danger">
                <i class="bi bi-x-circle"></i> Desactivar
            </a>
        </div>
    </div>
</div>

<script>
const modal  = document.getElementById('modalBaja');
const abrir  = () => modal.classList.add('abierto');
const cerrar = () => modal.classList.remove('abierto');

document.querySelectorAll('.js-baja').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('nombrePromoBaja').textContent = btn.dataset.nombre;
        document.getElementById('btnConfirmarBaja').href = 'index.php?action=promociones_eliminar&id=' + btn.dataset.id;
        abrir();
    });
});
document.getElementById('cerrarBaja').addEventListener('click', cerrar);
document.getElementById('cancelarBaja').addEventListener('click', cerrar);
modal.addEventListener('click', e => { if (e.target === modal) cerrar(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrar(); });
</script>

</body>
</html>