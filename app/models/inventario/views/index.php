<?php
$totalInactivos = count($inventariosInactivos ?? []);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inventario | GamaStore</title>
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
            <h1 class="page-titulo"><i class="bi bi-boxes"></i> Inventario</h1>
            <p class="page-sub">Gestionar stock y ubicaciones de depósito.</p>
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
            <?php if ($_GET['ok'] === 'entrada'): ?>
                <i class="bi bi-box-arrow-in-down"></i> Entrada de stock registrada correctamente.
            <?php elseif ($_GET['ok'] === 'salida'): ?>
                <i class="bi bi-box-arrow-up"></i> Salida de stock registrada correctamente.
            <?php else: ?>
                <i class="bi bi-check-circle-fill"></i> Inventario actualizado correctamente.
            <?php endif; ?>
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['baja'])): ?>
        <div class="alerta warning">
            <i class="bi bi-box-arrow-down"></i> Fila de inventario dada de baja correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['reactivado'])): ?>
        <div class="alerta success">
            <i class="bi bi-box-arrow-up"></i> Fila de inventario reactivada correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error']) && $_GET['error'] === 'ya_activo'): ?>
        <div class="alerta danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            No se puede reactivar: ese producto ya tiene otra fila de inventario activa.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <?php if ($stockBajo > 0): ?>
        <div class="alerta danger">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
                Hay <strong><?= $stockBajo ?></strong> producto(s) con stock igual o por debajo del mínimo.
                Revisá la columna "Estado" para identificarlos.
            </div>
        </div>
    <?php endif; ?>
    
    <?php if (($_GET['correo'] ?? '') === '1'): ?>
        <div class="alerta success">
            <i class="bi bi-envelope-check-fill"></i> El motivo se envió por correo al proveedor.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php elseif (($_GET['correo'] ?? '') === '0'): ?>
        <div class="alerta warning">
            <i class="bi bi-envelope-exclamation-fill"></i> La salida se registró, pero no se pudo enviar el correo al proveedor.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
<!-- Filtros: producto, talle y estado -->
<div class="panel">
    <div class="panel-body">
        <form method="GET" action="index.php" class="buscador">
            <input type="hidden" name="action" value="inventario">

            <div class="input-wrap">
                <i class="bi bi-search icono"></i>
                <input type="text" name="buscar" placeholder="Buscar por nombre o código..."
                       value="<?= htmlspecialchars($filtroBuscar) ?>">
            </div>

            <div class="input-wrap">
                <i class="bi bi-rulers icono"></i>
                <select name="talle">
                    <option value="">Todos los talles</option>
                    <?php foreach ($talles as $t): ?>
                        <option value="<?= $t['id_talle'] ?>" <?= ($filtroTalle == $t['id_talle']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['talles_disponibles']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <i class="bi bi-chevron-down flecha"></i>
            </div>

            <div class="input-wrap">
                <i class="bi bi-activity icono"></i>
                <select name="estado">
                    <option value="">Todos los estados</option>
                    <option value="bajo"   <?= $filtroEstado === 'bajo'   ? 'selected' : '' ?>>Stock bajo</option>
                    <option value="normal" <?= $filtroEstado === 'normal' ? 'selected' : '' ?>>Normal</option>
                    <option value="sobre"  <?= $filtroEstado === 'sobre'  ? 'selected' : '' ?>>Sobrestock</option>
                </select>
                <i class="bi bi-chevron-down flecha"></i>
            </div>

            <button type="submit" class="btn btn-main"><i class="bi bi-funnel"></i> Filtrar</button>
            <a href="index.php?action=inventario" class="btn btn-ghost"><i class="bi bi-x-circle"></i> Limpiar</a>
        </form>
    </div>
</div>
    <!-- Inventario activo -->
    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-boxes"></i> Inventario activo</span>
            <span class="cuenta"><?= (int) $totalInventarios ?> registros</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:1180px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Talle</th>
                        <th>Imagen</th>
                        <th>Ubicación</th>
                        <th class="centro">Stock</th>
                        <th class="centro">Mín.</th>
                        <th class="centro">Máx.</th>
                        <th class="centro">Estado</th>
                        <th class="centro">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($inventarios)): ?>
                    <tr>
                        <td colspan="12" class="vacio">
                            <?= $hayFiltros ? 'No se encontraron registros con esos filtros.' : 'No hay productos en el inventario.' ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($inventarios as $i): ?>
                    <?php
                        if ($i['stock_maximo'] > 0 && $i['stock_actual'] >= $i['stock_maximo']) {
                            $claseEstado = 'sobre';
                            $estado      = 'Sobrestock';
                        } elseif ($i['stock_actual'] <= $i['stock_minimo']) {
                            $claseEstado = 'bajo';
                            $estado      = 'Stock bajo';
                        } else {
                            $claseEstado = 'normal';
                            $estado      = 'Normal';
                        }
                    ?>
                    <tr>
                        <td class="id"><?= $i['id_inventario'] ?></td>
                        <td><?= htmlspecialchars($i['codigo'] ?? '—') ?></td>
                        <td class="nombre-prod"><?= htmlspecialchars($i['nombre_producto'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($i['tipodezapatillas'] ?? '—') ?></td>
                        <td><span class="chip"><?= htmlspecialchars($i['talle'] ?? '—') ?></span></td>
                        <td>
                            <?php if (!empty($i['imagen'])): ?>
                                <img class="thumb-prod" src="uploads/productos/<?= htmlspecialchars($i['imagen']) ?>" alt="">
                            <?php else: ?>
                                <span class="thumb-prod thumb-vacio"><i class="bi bi-image"></i></span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($i['ubicacion'] ?: '—') ?></td>
                        <td class="centro"><span class="stock-num <?= $claseEstado === 'normal' ? '' : $claseEstado ?>"><?= $i['stock_actual'] ?></span></td>
                        <td class="centro"><?= $i['stock_minimo'] ?></td>
                        <td class="centro"><?= $i['stock_maximo'] ?></td>
                        <td class="centro"><span class="estado <?= $claseEstado ?>"><?= $estado ?></span></td>
                        <td>
                            <div class="acciones-fila">
                                <a href="index.php?action=inventario_entrada&id=<?= $i['id_inventario'] ?>" class="btn-icon entrada" title="Registrar entrada">
                                    <i class="bi bi-box-arrow-in-down"></i>
                                </a>
                                <a href="index.php?action=inventario_salida&id=<?= $i['id_inventario'] ?>" class="btn-icon salida" title="Registrar salida">
                                    <i class="bi bi-box-arrow-up"></i>
                                </a>
                                <a href="index.php?action=inventario_editar&id=<?= $i['id_inventario'] ?>" class="btn-icon editar" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="index.php?action=inventario_historial&id=<?= $i['id_inventario'] ?>" class="btn-icon historial" title="Ver historial">
                                    <i class="bi bi-clock-history"></i>
                                </a>
                                <button type="button" class="btn-icon baja js-baja" title="Dar de baja"
                                        data-id="<?= $i['id_inventario'] ?>"
                                        data-nombre="<?= htmlspecialchars(($i['nombre_producto'] ?? '') . ' (talle ' . ($i['talle'] ?? '-') . ')') ?>">
                                    <i class="bi bi-box-arrow-down"></i>
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
    <?php if ($totalInventarios > 0): ?>
        <?php
            $paramsBase = ['action' => 'inventario'];
            if ($filtroBuscar !== '')     $paramsBase['buscar'] = $filtroBuscar;
            if (!empty($filtroTalle))     $paramsBase['talle']  = $filtroTalle;
            if ($filtroEstado !== '')     $paramsBase['estado'] = $filtroEstado;
            $inicio = max(1, $pagina - 2);
            $fin    = min($totalPaginas, $pagina + 2);
        ?>
        <div class="paginacion">
            <small>Mostrando <?= $desde ?> a <?= $hasta ?> de <?= $totalInventarios ?> registros</small>

            <?php if ($totalPaginas > 1): ?>
            <nav class="pag" aria-label="Paginación de inventario">
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

    <!-- Inventario inactivo (baja lógica) -->
    <?php if (!empty($inventariosInactivos)): ?>
    <div class="panel inactivo">
        <div class="panel-head">
            <span><i class="bi bi-box"></i> Inventario inactivo (baja lógica)</span>
            <span class="cuenta"><?= $totalInactivos ?></span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:940px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Codigo</th>
                        <th>Producto</th>
                        <th>Tipo</th>
                        <th>Talle</th>
                        <th>Imagen</th>
                        <th>Ubicación</th>
                        <th class="centro">Stock</th>
                        <th class="centro">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventariosInactivos as $inv): ?>
                    <tr class="apagada">
                        <td class="id"><?= $inv['id_inventario'] ?></td>
                        <td><?= htmlspecialchars($inv['codigo'] ?? '—') ?></td>
                        <td class="nombre-prod"><?= htmlspecialchars($inv['nombre_producto'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($inv['tipodezapatillas'] ?? '—') ?></td>
                        <td><span class="chip"><?= htmlspecialchars($inv['talle'] ?? '—') ?></span></td>
                        <td>
                            <?php if (!empty($inv['imagen'])): ?>
                                <img class="thumb-prod" src="uploads/productos/<?= htmlspecialchars($inv['imagen']) ?>" alt="">
                            <?php else: ?>
                                <span class="thumb-prod thumb-vacio"><i class="bi bi-image"></i></span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($inv['ubicacion'] ?: '—') ?></td>
                        <td class="centro"><?= $inv['stock_actual'] ?></td>
                        <td>
                            <div class="acciones-fila">
                                <a href="index.php?action=inventario_historial&id=<?= $inv['id_inventario'] ?>" class="btn-icon historial" title="Ver historial">
                                    <i class="bi bi-clock-history"></i>
                                </a>
                                <a href="index.php?action=inventario_reactivar&id=<?= $inv['id_inventario'] ?>" class="btn btn-ok btn-sm" title="Reactivar">
                                    <i class="bi bi-box-arrow-up"></i> Reactivar
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
            <h2><i class="bi bi-box-arrow-down"></i> Confirmar baja</h2>
            <button type="button" class="cerrar" id="cerrarBaja" aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-body">
            <p>¿Dar de baja la fila de inventario de <strong id="nombreInventarioBaja"></strong>?</p>
            <div class="nota">
                <i class="bi bi-info-circle"></i>
                <span>No se elimina: queda inactiva y se puede reactivar cuando quieras.</span>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-ghost" id="cancelarBaja">Cancelar</button>
            <a id="btnConfirmarBajaInventario" href="#" class="btn btn-danger">
                <i class="bi bi-box-arrow-down"></i> Dar de baja
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
        document.getElementById('nombreInventarioBaja').textContent = btn.dataset.nombre;
        document.getElementById('btnConfirmarBajaInventario').href = 'index.php?action=inventario_eliminar&id=' + btn.dataset.id;
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