<?php
// Una sola conexión para obtener los módulos de todos los perfiles
$db           = (new Database())->connect();
$perfilModelo = new Perfil($db);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Perfiles | GamaStore</title>
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
            <h1 class="page-titulo"><i class="bi bi-person-badge"></i> Perfiles</h1>
            <p class="page-sub">Gestionar perfiles y los módulos a los que accede cada uno.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=dashboard" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="index.php?action=create_perfil" class="btn btn-main">
                <i class="bi bi-plus-lg"></i> Nuevo perfil
            </a>
        </div>
    </div>

    <!-- Mensajes -->
    <?php if (isset($_GET['ok'])): ?>
        <div class="alerta success">
            <i class="bi bi-check-circle-fill"></i> Perfil guardado correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['baja'])): ?>
        <div class="alerta warning">
            <i class="bi bi-dash-circle"></i> Perfil dado de baja correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['reactivado'])): ?>
        <div class="alerta success">
            <i class="bi bi-check-circle"></i> Perfil reactivado correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Perfiles activos -->
    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-person-badge"></i> Perfiles activos</span>
            <span class="cuenta"><?= count($perfiles ?? []) ?> en total</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:860px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Módulos asignados</th>
                        <th class="centro">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($perfiles)): ?>
                    <tr><td colspan="5" class="vacio">No hay perfiles activos.</td></tr>
                <?php else: ?>
                    <?php foreach ($perfiles as $p): ?>
                    <?php $modulosPerfil = $perfilModelo->obtenerModulos($p['id_perfil']); ?>
                    <tr>
                        <td class="id"><?= $p['id_perfil'] ?></td>
                        <td class="nombre-prod"><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= htmlspecialchars($p['descripcion'] ?? '—') ?></td>
                        <td>
                            <div class="mods">
                                <?php if (empty($modulosPerfil)): ?>
                                    <span class="estado neutro">Sin módulos</span>
                                <?php else: ?>
                                    <?php foreach ($modulosPerfil as $m): ?>
                                        <span class="chip"><?= ucfirst($m) ?></span>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div class="acciones-fila">
                                <a href="index.php?action=edit_perfil&id=<?= $p['id_perfil'] ?>" class="btn-icon editar" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn-icon baja js-baja" title="Dar de baja"
                                        data-id="<?= $p['id_perfil'] ?>"
                                        data-nombre="<?= htmlspecialchars($p['nombre']) ?>">
                                    <i class="bi bi-dash-circle"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Perfiles inactivos -->
    <?php if (!empty($perfilesInactivos)): ?>
    <div class="panel inactivo">
        <div class="panel-head">
            <span><i class="bi bi-person-x"></i> Perfiles inactivos (baja lógica)</span>
            <span class="cuenta"><?= count($perfilesInactivos) ?></span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:640px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th class="centro">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($perfilesInactivos as $p): ?>
                    <tr class="apagada">
                        <td class="id"><?= $p['id_perfil'] ?></td>
                        <td class="nombre-prod"><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= htmlspecialchars($p['descripcion'] ?? '—') ?></td>
                        <td>
                            <div class="acciones-fila">
                                <a href="index.php?action=reactivar_perfil&id=<?= $p['id_perfil'] ?>" class="btn btn-ok btn-sm">
                                    <i class="bi bi-check-circle"></i> Reactivar
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>

</main>

<!-- Modal confirmar baja lógica -->
<div class="overlay" id="modalBaja">
    <div class="modal" role="dialog" aria-modal="true">
        <div class="modal-head">
            <h2><i class="bi bi-dash-circle"></i> Confirmar baja</h2>
            <button type="button" class="cerrar" id="cerrarBaja" aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-body">
            <p>¿Dar de baja al perfil <strong id="nombrePerfilBaja"></strong>?</p>
            <div class="nota">
                <i class="bi bi-info-circle"></i>
                <span>El perfil <strong>no se elimina</strong> de la base de datos y puede ser reactivado en cualquier momento.</span>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-ghost" id="cancelarBaja">Cancelar</button>
            <a id="btnConfirmarBajaPerfil" href="#" class="btn btn-danger">
                <i class="bi bi-dash-circle"></i> Dar de baja
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
        document.getElementById('nombrePerfilBaja').textContent = btn.dataset.nombre;
        document.getElementById('btnConfirmarBajaPerfil').href = 'index.php?action=delete_perfil&id=' + btn.dataset.id;
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