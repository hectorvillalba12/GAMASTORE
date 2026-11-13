<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ventas | GamaStore</title>
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
            <h1 class="page-titulo"><i class="bi bi-cart-check"></i> Ventas</h1>
            <p class="page-sub">Registrar y consultar las ventas de GamaStore.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=dashboard" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="index.php?action=ventas_canceladas" class="btn btn-danger">
                <i class="bi bi-x-octagon"></i> Ventas canceladas
            </a>
            <a href="index.php?action=ventas_crear" class="btn btn-main">
                <i class="bi bi-plus-lg"></i> Nueva venta
            </a>
        </div>
    </div>

    <!-- Mensajes -->
    <?php if (isset($_GET['ok'])): ?>
        <div class="alerta success">
            <i class="bi bi-check-circle-fill"></i> Venta registrada con éxito.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Filtro por cliente -->
    <div class="panel">
        <div class="panel-body">
            <form method="GET" action="index.php" class="buscador">
                <input type="hidden" name="action" value="ventas">
                <div class="input-wrap">
                    <i class="bi bi-search icono"></i>
                    <input type="text" id="cliente" name="cliente" placeholder="Buscar cliente por nombre o apellido..."
                           autocomplete="off" value="<?= htmlspecialchars($filtros['cliente']) ?>">
                </div>
                <button type="submit" class="btn btn-main"><i class="bi bi-funnel"></i> Filtrar</button>
                <?php if ($hayFiltros): ?>
                    <a href="index.php?action=ventas" class="btn btn-ghost"><i class="bi bi-x-lg"></i> Limpiar</a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Tabla de ventas -->
    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-receipt"></i> Listado de ventas</span>
            <span class="cuenta"><?= (int) $totalVentas ?> en total</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:980px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Productos</th>
                        <th class="der">Total</th>
                        <th>Método de pago</th>
                        <th class="centro">Estado</th>
                        <th class="centro">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($ventas)): ?>
                    <tr>
                        <td colspan="8" class="vacio">
                            <?= $hayFiltros ? 'No se encontraron ventas con esos filtros.' : 'No hay ventas registradas.' ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($ventas as $v): ?>
                    <tr>
                        <td class="id"><?= $v['id_venta'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
                        <td class="nombre-prod"><?= htmlspecialchars($v['nombre_cliente'] ?? 'Consumidor final') ?></td>
                        <td class="wrap-td"><?= htmlspecialchars($v['productos'] ?? '—') ?></td>
                        <td class="der precio">$<?= number_format($v['total'], 2, ',', '.') ?></td>
                        <td><?= htmlspecialchars($v['metodo_de_pago']) ?></td>
                        <td class="centro">
                            <?php if ($v['estado'] === 'cancelada'): ?>
                                <span class="estado bajo">Cancelada</span>
                            <?php else: ?>
                                <span class="estado normal">Completada</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="acciones-fila">
                                <a href="index.php?action=ventas_ver&id=<?= $v['id_venta'] ?>" class="btn-icon historial" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if ($v['estado'] !== 'cancelada'): ?>
                                <button type="button" class="btn-icon baja js-cancelar" title="Cancelar venta"
                                        data-id="<?= $v['id_venta'] ?>">
                                    <i class="bi bi-x-circle"></i>
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

    <!-- Paginación -->
    <?php if ($totalVentas > 0): ?>
        <?php
            // Conserva los filtros activos al cambiar de página
            $paramsBase = ['action' => 'ventas'] + array_filter($filtros, function ($v) { return $v !== ''; });
            $inicio = max(1, $pagina - 2);
            $fin    = min($totalPaginas, $pagina + 2);
        ?>
        <div class="paginacion">
            <small>Mostrando <?= $desde ?> a <?= $hasta ?> de <?= $totalVentas ?> ventas</small>

            <?php if ($totalPaginas > 1): ?>
            <nav class="pag" aria-label="Paginación de ventas">
                <a class="<?= ($pagina <= 1) ? 'off' : '' ?>"
                   href="index.php?<?= http_build_query($paramsBase + ['pagina' => $pagina - 1]) ?>">&laquo; Anterior</a>

                <?php if ($inicio > 1): ?>
                    <a href="index.php?<?= http_build_query($paramsBase + ['pagina' => 1]) ?>">1</a>
                    <?php if ($inicio > 2): ?><span>…</span><?php endif; ?>
                <?php endif; ?>

                <?php for ($i = $inicio; $i <= $fin; $i++): ?>
                    <a class="<?= ($i == $pagina) ? 'activa' : '' ?>"
                       href="index.php?<?= http_build_query($paramsBase + ['pagina' => $i]) ?>"><?= $i ?></a>
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

</main>

<!-- Modal confirmar cancelación -->
<div class="overlay" id="modalCancelar">
    <div class="modal" role="dialog" aria-modal="true">
        <div class="modal-head">
            <h2><i class="bi bi-x-octagon"></i> Cancelar venta</h2>
            <button type="button" class="cerrar" id="cerrarCancelar" aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-body">
            <p>¿Seguro que querés cancelar la venta <strong id="numVentaCancelar"></strong>?</p>
            <div class="nota">
                <i class="bi bi-info-circle"></i>
                <span>Se devolverá el stock de los productos al inventario.</span>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-ghost" id="noCancelar">Volver</button>
            <a id="btnConfirmarCancelar" href="#" class="btn btn-danger">
                <i class="bi bi-x-circle"></i> Cancelar venta
            </a>
        </div>
    </div>
</div>

<script>
const modal  = document.getElementById('modalCancelar');
const abrir  = () => modal.classList.add('abierto');
const cerrar = () => modal.classList.remove('abierto');

document.querySelectorAll('.js-cancelar').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('numVentaCancelar').textContent = '#' + btn.dataset.id;
        document.getElementById('btnConfirmarCancelar').href = 'index.php?action=ventas_cancelar&id=' + btn.dataset.id;
        abrir();
    });
});
document.getElementById('cerrarCancelar').addEventListener('click', cerrar);
document.getElementById('noCancelar').addEventListener('click', cerrar);
modal.addEventListener('click', e => { if (e.target === modal) cerrar(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrar(); });
</script>

</body>
</html>