<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Usuarios | GamaStore</title>
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
            <h1 class="page-titulo"><i class="bi bi-person-gear"></i> Usuarios</h1>
            <p class="page-sub">Gestionar usuarios y perfiles asignados.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=dashboard" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    <!-- Mensajes -->
    <?php if (isset($_GET['ok'])): ?>
        <div class="alerta success">
            <i class="bi bi-check-circle-fill"></i> Usuario actualizado correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['baja'])): ?>
        <div class="alerta warning">
            <i class="bi bi-person-dash-fill"></i> Usuario dado de baja correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['reactivado'])): ?>
        <div class="alerta success">
            <i class="bi bi-person-check-fill"></i> Usuario reactivado correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (($_GET['error'] ?? '') === 'unico_admin'): ?>
        <div class="alerta danger">
            <i class="bi bi-shield-lock-fill"></i>
            <div>
                No podés modificar ni dar de baja a este usuario porque es el
                <strong>único administrador activo</strong> del sistema.
            </div>
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Usuarios activos -->
    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-person-check"></i> Usuarios activos</span>
            <span class="cuenta"><?= count($usuarios ?? []) ?> en total</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:720px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Perfil asignado</th>
                        <th class="centro">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($usuarios)): ?>
                    <tr><td colspan="5" class="vacio">No hay usuarios activos.</td></tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $u): ?>
                    <?php $esUnicoAdmin = ($u['rol'] === 'admin' && $totalAdminsActivos <= 1); ?>
                    <tr>
                        <td class="id"><?= $u['id_usuario'] ?></td>
                        <td>
                            <div class="celda-persona">
                                <div class="avatar-sm"><?= strtoupper(mb_substr($u['email'], 0, 1)) ?></div>
                                <?= htmlspecialchars($u['email']) ?>
                                <?php if ($esUnicoAdmin): ?>
                                    <span class="estado ajuste" title="Único administrador activo">
                                        <i class="bi bi-shield-lock"></i> Protegido
                                    </span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><span class="chip"><?= htmlspecialchars($u['rol']) ?></span></td>
                        <td>
                            <?php if ($u['perfil_nombre']): ?>
                                <span class="estado normal"><?= htmlspecialchars($u['perfil_nombre']) ?></span>
                            <?php else: ?>
                                <span class="estado neutro">Sin perfil</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="acciones-fila">
                                <?php if ($esUnicoAdmin): ?>
                                    <button type="button" class="btn-icon editar" disabled title="Único administrador activo">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button type="button" class="btn-icon baja" disabled title="Único administrador activo">
                                        <i class="bi bi-shield-lock"></i>
                                    </button>
                                <?php else: ?>
                                    <a href="index.php?action=edit_usuario&id=<?= $u['id_usuario'] ?>" class="btn-icon editar" title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn-icon baja js-baja" title="Dar de baja"
                                            data-id="<?= $u['id_usuario'] ?>"
                                            data-email="<?= htmlspecialchars($u['email']) ?>">
                                        <i class="bi bi-person-dash"></i>
                                    </button>
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

    <!-- Usuarios inactivos -->
    <?php if (!empty($usuariosInactivos)): ?>
    <div class="panel inactivo">
        <div class="panel-head">
            <span><i class="bi bi-person-x"></i> Usuarios inactivos (baja lógica)</span>
            <span class="cuenta"><?= count($usuariosInactivos) ?></span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:720px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <th>Perfil asignado</th>
                        <th class="centro">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuariosInactivos as $u): ?>
                    <tr class="apagada">
                        <td class="id"><?= $u['id_usuario'] ?></td>
                        <td>
                            <div class="celda-persona">
                                <div class="avatar-sm"><?= strtoupper(mb_substr($u['email'], 0, 1)) ?></div>
                                <?= htmlspecialchars($u['email']) ?>
                            </div>
                        </td>
                        <td><span class="chip"><?= htmlspecialchars($u['rol']) ?></span></td>
                        <td>
                            <span class="estado neutro">
                                <?= $u['perfil_nombre'] ? htmlspecialchars($u['perfil_nombre']) : 'Sin perfil' ?>
                            </span>
                        </td>
                        <td>
                            <div class="acciones-fila">
                                <a href="index.php?action=reactivar_usuario&id=<?= $u['id_usuario'] ?>" class="btn btn-ok btn-sm">
                                    <i class="bi bi-person-check"></i> Reactivar
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
            <h2><i class="bi bi-person-dash"></i> Confirmar baja</h2>
            <button type="button" class="cerrar" id="cerrarBaja" aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-body">
            <p>¿Dar de baja al usuario <strong id="emailUsuarioBaja"></strong>?</p>
            <div class="nota">
                <i class="bi bi-info-circle"></i>
                <span>El usuario <strong>no se elimina</strong> de la base de datos y puede ser reactivado en cualquier momento.</span>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-ghost" id="cancelarBaja">Cancelar</button>
            <a id="btnConfirmarBajaUsuario" href="#" class="btn btn-danger">
                <i class="bi bi-person-dash"></i> Dar de baja
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
        document.getElementById('emailUsuarioBaja').textContent = btn.dataset.email;
        document.getElementById('btnConfirmarBajaUsuario').href = 'index.php?action=delete_usuario&id=' + btn.dataset.id;
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