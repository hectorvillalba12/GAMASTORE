<?php
$totalInactivos = count($proveedoresInactivos ?? []);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Proveedores | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap">

    <div class="page-head">
        <div>
            <div class="page-label">Módulo de gestión</div>
            <h1 class="page-titulo"><i class="bi bi-truck"></i> Proveedores</h1>
            <p class="page-sub">Gestionar los proveedores que abastecen a GamaStore.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=dashboard" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Volver</a>
            <a href="index.php?action=proveedores_crear" class="btn btn-main"><i class="bi bi-plus-lg"></i> Nuevo proveedor</a>
        </div>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alerta success">
            <i class="bi bi-check-circle-fill"></i> Proveedor guardado con éxito.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['baja'])): ?>
        <div class="alerta warning">
            <i class="bi bi-dash-circle-fill"></i> Proveedor dado de baja correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['reactivado'])): ?>
        <div class="alerta success">
            <i class="bi bi-check-circle-fill"></i> Proveedor reactivado correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Buscador -->
    <div class="panel">
        <div class="panel-body">
            <form method="GET" action="index.php" class="buscador">
                <input type="hidden" name="action" value="proveedores">
                <div class="input-wrap">
                    <i class="bi bi-search icono"></i>
                    <input type="text" name="buscar" placeholder="Buscar por nombre, teléfono, dirección o email..."
                        autocomplete="off" value="<?= htmlspecialchars($buscar) ?>">
                </div>
                <button type="submit" class="btn btn-main"><i class="bi bi-funnel"></i> Buscar</button>
                <a href="index.php?action=proveedores" class="btn btn-ghost"><i class="bi bi-x-circle"></i> Limpiar</a>
            </form>
        </div>
    </div>

    <!-- Proveedores activos -->
    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-truck"></i> Proveedores activos</span>
            <span class="cuenta"><?= (int)$totalProveedores ?> en total</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Proveedor</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Email</th>
                        <th class="centro">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($proveedores)): ?>
                    <tr>
                        <td colspan="6" class="vacio">
                            <?= ($buscar !== '') ? 'No se encontraron proveedores con esa búsqueda.' : 'No hay proveedores activos.' ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($proveedores as $p): ?>
                    <tr>
                        <td class="id"><?= $p['id_proveedor'] ?></td>
                        <td>
                            <div class="celda-persona">
                                <div class="avatar-sm"><?= strtoupper(mb_substr($p['nombre'], 0, 2)) ?></div>
                                <?= htmlspecialchars($p['nombre']) ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($p['telefono'] ?? '') ?></td>
                        <td><?= htmlspecialchars($p['direccion'] ?? '') ?></td>
                        <td><?= htmlspecialchars($p['email'] ?? '') ?></td>
                        <td>
                            <div class="acciones-fila">
                                <a href="index.php?action=proveedores_editar&id=<?= $p['id_proveedor'] ?>" class="btn-icon editar" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn-icon baja js-baja" title="Dar de baja"
                                        data-id="<?= $p['id_proveedor'] ?>"
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

    <!-- Paginación -->
    <?php if ($totalProveedores > 0): ?>
        <?php
            $paramsBase = ['action' => 'proveedores'];
            if ($buscar !== '') $paramsBase['buscar'] = $buscar;
            $inicio = max(1, $pagina - 2);
            $fin    = min($totalPaginas, $pagina + 2);
        ?>
        <div class="paginacion">
            <small>Mostrando <?= $desde ?> a <?= $hasta ?> de <?= $totalProveedores ?> proveedores</small>

            <?php if ($totalPaginas > 1): ?>
            <nav class="pag" aria-label="Paginación de proveedores">
                <a class="<?= ($pagina <= 1) ? 'off' : '' ?>"
                href="index.php?<?= http_build_query($paramsBase + ['pagina' => $pagina - 1]) ?>">&laquo; Anterior</a>

                <?php for ($n = $inicio; $n <= $fin; $n++): ?>
                    <a class="<?= ($n == $pagina) ? 'activa' : '' ?>"
                    href="index.php?<?= http_build_query($paramsBase + ['pagina' => $n]) ?>"><?= $n ?></a>
                <?php endfor; ?>

                <a class="<?= ($pagina >= $totalPaginas) ? 'off' : '' ?>"
                href="index.php?<?= http_build_query($paramsBase + ['pagina' => $pagina + 1]) ?>">Siguiente &raquo;</a>
            </nav>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Proveedores inactivos -->
    <?php if (!empty($proveedoresInactivos)): ?>
    <div class="panel inactivo">
        <div class="panel-head">
            <span><i class="bi bi-x-circle"></i> Proveedores inactivos (baja lógica)</span>
            <span class="cuenta"><?= $totalInactivos ?></span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>#</th><th>Proveedor</th><th>Teléfono</th><th>Email</th>
                        <th class="centro">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proveedoresInactivos as $p): ?>
                    <tr class="apagada">
                        <td class="id"><?= $p['id_proveedor'] ?></td>
                        <td><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= htmlspecialchars($p['telefono'] ?? '') ?></td>
                        <td><?= htmlspecialchars($p['email'] ?? '') ?></td>
                        <td>
                            <div class="acciones-fila">
                                <a href="index.php?action=proveedores_reactivar&id=<?= $p['id_proveedor'] ?>" class="btn btn-ok btn-sm">
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

<!-- Modal confirmar baja -->
<div class="overlay" id="modalBaja">
    <div class="modal" role="dialog" aria-modal="true">
        <div class="modal-head">
            <h2><i class="bi bi-dash-circle"></i> Confirmar baja</h2>
            <button type="button" class="cerrar" id="cerrarBaja" aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-body">
            <p>¿Dar de baja al proveedor <strong id="nombreProveedorBaja"></strong>?</p>
            <div class="nota">
                <i class="bi bi-info-circle"></i>
                <span>El proveedor <strong>no se elimina</strong>: queda inactivo, conserva su historial y se puede reactivar.</span>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-ghost" id="cancelarBaja">Cancelar</button>
            <a id="btnConfirmarBaja" href="#" class="btn btn-danger"><i class="bi bi-dash-circle"></i> Dar de baja</a>
        </div>
    </div>
</div>

<script>
const modal  = document.getElementById('modalBaja');
const abrir  = () => modal.classList.add('abierto');
const cerrar = () => modal.classList.remove('abierto');

document.querySelectorAll('.js-baja').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('nombreProveedorBaja').textContent = btn.dataset.nombre;
        document.getElementById('btnConfirmarBaja').href = 'index.php?action=proveedores_eliminar&id=' + btn.dataset.id;
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