<?php
$totalInactivos = count($clientesInactivos ?? []);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clientes | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap">

    <!-- Encabezado -->
    <div class="page-head">
        <div>
            <div class="page-label">Módulo de gestión</div>
            <h1 class="page-titulo"><i class="bi bi-people"></i> Clientes</h1>
            <p class="page-sub">Gestionar clientes de GamaStore y su historial de compras.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=dashboard" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="index.php?action=create_cliente" class="btn btn-main">
                <i class="bi bi-plus-lg"></i> Nuevo cliente
            </a>
        </div>
    </div>

    <!-- Mensajes -->
    <?php if (isset($_GET['ok'])): ?>
        <div class="alerta success">
            <i class="bi bi-check-circle-fill"></i> Cliente guardado con éxito.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['baja'])): ?>
        <div class="alerta warning">
            <i class="bi bi-person-dash-fill"></i> Cliente dado de baja correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['reactivado'])): ?>
        <div class="alerta success">
            <i class="bi bi-person-check-fill"></i> Cliente reactivado correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Buscador (nombre, DNI o teléfono) -->
    <div class="panel">
        <div class="panel-body">
            <form method="GET" action="index.php" class="buscador">
                <input type="hidden" name="action" value="clientes">
                <div class="input-wrap">
                    <i class="bi bi-search icono"></i>
                    <input type="text" id="buscar" name="buscar" placeholder="Buscar por nombre, DNI o teléfono..."
                           autocomplete="off" value="<?= htmlspecialchars($buscar) ?>">
                </div>
                <button type="submit" class="btn btn-main"><i class="bi bi-funnel"></i> Buscar</button>
                <a href="index.php?action=clientes" class="btn btn-ghost"><i class="bi bi-x-circle"></i> Limpiar</a>
            </form>
        </div>
    </div>

    <!-- Clientes activos -->
    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-person-check"></i> Clientes activos</span>
            <span class="cuenta"><?= (int) $totalClientes ?> en total</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>DNI</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Registro</th>
                        <th class="centro">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($clientes)): ?>
                    <tr>
                        <td colspan="7" class="vacio">
                            <?= ($buscar !== '') ? 'No se encontraron clientes con esa búsqueda.' : 'No hay clientes activos.' ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($clientes as $c): ?>
                    <tr>
                        <td class="id"><?= $c['id_cliente'] ?></td>
                        <td>
                            <div class="celda-persona">
                                <div class="avatar-sm"><?= strtoupper(mb_substr($c['nombre'], 0, 1) . mb_substr($c['apellido'], 0, 1)) ?></div>
                                <?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($c['dni']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['telefono']) ?></td>
                        <td><?= $c['fecha_registro'] ? date('d/m/Y', strtotime($c['fecha_registro'])) : '-' ?></td>
                        <td>
                            <div class="acciones-fila">
                                <a href="index.php?action=edit_cliente&id=<?= $c['id_cliente'] ?>" class="btn-icon editar" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn-icon baja js-baja" title="Dar de baja"
                                        data-id="<?= $c['id_cliente'] ?>"
                                        data-nombre="<?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?>">
                                    <i class="bi bi-person-dash"></i>
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
    <?php if ($totalClientes > 0): ?>
        <?php
            $paramsBase = ['action' => 'clientes'];
            if ($buscar !== '') $paramsBase['buscar'] = $buscar;
            $inicio = max(1, $pagina - 2);
            $fin    = min($totalPaginas, $pagina + 2);
        ?>
        <div class="paginacion">
            <small>Mostrando <?= $desde ?> a <?= $hasta ?> de <?= $totalClientes ?> clientes</small>

            <?php if ($totalPaginas > 1): ?>
            <nav class="pag" aria-label="Paginación de clientes">
                <a class="<?= ($pagina <= 1) ? 'off' : '' ?>"
                   href="index.php?<?= http_build_query($paramsBase + ['pagina' => $pagina - 1]) ?>">&laquo; Anterior</a>

                <?php if ($inicio > 1): ?>
                    <a href="index.php?<?= http_build_query($paramsBase + ['pagina' => 1]) ?>">1</a>
                    <?php if ($inicio > 2): ?><span>…</span><?php endif; ?>
                <?php endif; ?>

                <?php for ($p = $inicio; $p <= $fin; $p++): ?>
                    <a class="<?= ($p == $pagina) ? 'activa' : '' ?>"
                       href="index.php?<?= http_build_query($paramsBase + ['pagina' => $p]) ?>"><?= $p ?></a>
                <?php endfor; ?>

                <?php if ($fin < $totalPaginas): ?>
                    <?php if ($fin < $totalPaginas - 1): ?><span>…</span><?php endif; ?>
                    <a href="index.php?<?= http_build_query($paramsBase + ['pagina' => $totalPaginas]) ?>"><?= $totalPaginas ?></a>
                <?php endif; ?>

                <a class="<?= ($pagina >= $totalPaginas) ? 'off' : '' ?>"
                   href="index.php?<?= http_build_query($paramsBase + ['pagina' => $pagina + 1]) ?>">Siguiente &raquo;</a>
            </nav>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Clientes inactivos (baja lógica) -->
    <?php if (!empty($clientesInactivos)): ?>
    <div class="panel inactivo">
        <div class="panel-head">
            <span><i class="bi bi-person-x"></i> Clientes inactivos (baja lógica)</span>
            <span class="cuenta"><?= $totalInactivos ?></span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>DNI</th>
                        <th>Email</th>
                        <th>Teléfono</th>
                        <th>Registro</th>
                        <th class="centro">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientesInactivos as $c): ?>
                    <tr class="apagada">
                        <td class="id"><?= $c['id_cliente'] ?></td>
                        <td>
                            <div class="celda-persona">
                                <div class="avatar-sm"><?= strtoupper(mb_substr($c['nombre'], 0, 1) . mb_substr($c['apellido'], 0, 1)) ?></div>
                                <?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?>
                            </div>
                        </td>
                        <td><?= htmlspecialchars($c['dni']) ?></td>
                        <td><?= htmlspecialchars($c['email']) ?></td>
                        <td><?= htmlspecialchars($c['telefono']) ?></td>
                        <td><?= $c['fecha_registro'] ? date('d/m/Y', strtotime($c['fecha_registro'])) : '-' ?></td>
                        <td>
                            <div class="acciones-fila">
                                <a href="index.php?action=reactivar_cliente&id=<?= $c['id_cliente'] ?>" class="btn btn-ok btn-sm" title="Reactivar cliente">
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
            <p>¿Dar de baja al cliente <strong id="nombreClienteBaja"></strong>?</p>
            <div class="nota">
                <i class="bi bi-info-circle"></i>
                <span>El cliente <strong>no se elimina</strong> de la base de datos. Queda registrado como inactivo y puede ser reactivado en cualquier momento.</span>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-ghost" id="cancelarBaja">Cancelar</button>
            <a id="btnConfirmarBajaCliente" href="#" class="btn btn-danger">
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
        document.getElementById('nombreClienteBaja').textContent = btn.dataset.nombre;
        document.getElementById('btnConfirmarBajaCliente').href = 'index.php?action=delete_cliente&id=' + btn.dataset.id;
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