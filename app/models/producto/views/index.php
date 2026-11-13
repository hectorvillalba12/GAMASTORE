<?php
$totalInactivos = count($productosInactivos ?? []);
$hayFiltros     = !empty($filtroMarca) || !empty($filtroTalle);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Productos | GamaStore</title>
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
            <h1 class="page-titulo"><i class="bi bi-box-seam"></i> Productos</h1>
            <p class="page-sub">Gestionar productos, talles, marcas y categorías.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=dashboard" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="index.php?action=crear" class="btn btn-main">
                <i class="bi bi-plus-lg"></i> Nuevo producto
            </a>
        </div>
    </div>

    <!-- Mensajes -->
    <?php if (isset($_GET['ok'])): ?>
        <div class="alerta success">
            <i class="bi bi-check-circle-fill"></i> Producto guardado correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['baja'])): ?>
        <div class="alerta warning">
            <i class="bi bi-box-arrow-down"></i> Producto dado de baja correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['reactivado'])): ?>
        <div class="alerta success">
            <i class="bi bi-box-arrow-up"></i> Producto reactivado correctamente.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Filtros: marca y talle -->
    <div class="panel">
        <div class="panel-body">
            <form method="GET" action="index.php" class="buscador">
                <input type="hidden" name="action" value="productos">

                <div class="input-wrap">
                    <i class="bi bi-tag icono"></i>
                    <select name="marca">
                        <option value="">Todas las marcas</option>
                        <?php foreach ($marcas as $m): ?>
                            <option value="<?= $m['id_marca'] ?>" <?= ($filtroMarca == $m['id_marca']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($m['marcas_disponibles']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <i class="bi bi-chevron-down flecha"></i>
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

                <button type="submit" class="btn btn-main"><i class="bi bi-funnel"></i> Filtrar</button>
                <a href="index.php?action=productos" class="btn btn-ghost"><i class="bi bi-x-circle"></i> Limpiar</a>
            </form>
        </div>
    </div>

    <!-- Productos activos -->
    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-box-seam"></i> Productos activos</span>
            <span class="cuenta"><?= (int) $totalProductos ?> en total</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:940px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Marca</th>
                        <th>Talle</th>
                        <th>Color</th>
                        <th>Categoría</th>
                        <th class="der">Precio</th>
                        <th class="centro">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($productos)): ?>
                    <tr>
                        <td colspan="9" class="vacio">
                            <?= $hayFiltros ? 'No se encontraron productos con esos filtros.' : 'No hay productos activos.' ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($productos as $p): ?>
                    <tr>
                        <td class="id"><?= $p['id_producto'] ?></td>
                        <td class="nombre-prod"><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= htmlspecialchars($p['tipodezapatillas']) ?></td>
                        <td><?= htmlspecialchars($p['marca'] ?? '—') ?></td>
                        <td><span class="chip"><?= htmlspecialchars($p['talle'] ?? '—') ?></span></td>
                        <td><?= htmlspecialchars($p['color'] ?? '—') ?></td>
                        <td><?= htmlspecialchars($p['categoria'] ?? '—') ?></td>
                        <td class="der precio">$<?= number_format($p['precio'], 2, ',', '.') ?></td>
                        <td>
                            <div class="acciones-fila">
                                <button type="button" class="btn-icon ver js-ver" title="Ver imagen"
                                        data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                                        data-imagen="<?= !empty($p['imagen']) ? 'uploads/productos/' . htmlspecialchars($p['imagen']) : '' ?>">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <a href="index.php?action=editar&id=<?= $p['id_producto'] ?>" class="btn-icon editar" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button type="button" class="btn-icon baja js-baja" title="Dar de baja"
                                        data-id="<?= $p['id_producto'] ?>"
                                        data-nombre="<?= htmlspecialchars($p['nombre']) ?>">
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
    <?php if ($totalProductos > 0): ?>
        <?php
            $paramsBase = ['action' => 'productos'];
            if (!empty($filtroMarca)) $paramsBase['marca'] = $filtroMarca;
            if (!empty($filtroTalle)) $paramsBase['talle'] = $filtroTalle;
            $inicio = max(1, $pagina - 2);
            $fin    = min($totalPaginas, $pagina + 2);
        ?>
        <div class="paginacion">
            <small>Mostrando <?= $desde ?> a <?= $hasta ?> de <?= $totalProductos ?> productos</small>

            <?php if ($totalPaginas > 1): ?>
            <nav class="pag" aria-label="Paginación de productos">
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

    <!-- Productos inactivos (baja lógica) -->
    <?php if (!empty($productosInactivos)): ?>
    <div class="panel inactivo">
        <div class="panel-head">
            <span><i class="bi bi-box"></i> Productos inactivos (baja lógica)</span>
            <span class="cuenta"><?= $totalInactivos ?></span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:940px">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Marca</th>
                        <th>Talle</th>
                        <th>Imagen</th>
                        <th>Categoría</th>
                        <th class="der">Precio</th>
                        <th class="centro">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productosInactivos as $p): ?>
                    <tr class="apagada">
                        <td class="id"><?= $p['id_producto'] ?></td>
                        <td class="nombre-prod"><?= htmlspecialchars($p['nombre']) ?></td>
                        <td><?= htmlspecialchars($p['tipodezapatillas']) ?></td>
                        <td><?= htmlspecialchars($p['marca'] ?? '—') ?></td>
                        <td><span class="chip"><?= htmlspecialchars($p['talle'] ?? '—') ?></span></td>
                        <td>
                            <?php if (!empty($p['imagen'])): ?>
                                <img class="thumb-prod" src="uploads/productos/<?= htmlspecialchars($p['imagen']) ?>" alt="<?= htmlspecialchars($p['nombre']) ?>">
                            <?php else: ?>
                                <span class="thumb-prod thumb-vacio"><i class="bi bi-image"></i></span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p['categoria'] ?? '—') ?></td>
                        <td class="der precio">$<?= number_format($p['precio'], 2, ',', '.') ?></td>
                        <td>
                            <div class="acciones-fila">
                                <button type="button" class="btn-icon ver js-ver" title="Ver imagen"
                                        data-nombre="<?= htmlspecialchars($p['nombre']) ?>"
                                        data-imagen="<?= !empty($p['imagen']) ? 'uploads/productos/' . htmlspecialchars($p['imagen']) : '' ?>">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <a href="index.php?action=reactivar_producto&id=<?= $p['id_producto'] ?>" class="btn btn-ok btn-sm" title="Reactivar producto">
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
            <p>¿Dar de baja al producto <strong id="nombreProductoBaja"></strong>?</p>
            <div class="nota">
                <i class="bi bi-info-circle"></i>
                <span>El producto <strong>no se elimina</strong> de la base de datos. Queda registrado como inactivo y puede ser reactivado en cualquier momento.</span>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-ghost" id="cancelarBaja">Cancelar</button>
            <a id="btnConfirmarBajaProducto" href="#" class="btn btn-danger">
                <i class="bi bi-box-arrow-down"></i> Dar de baja
            </a>
        </div>
    </div>
</div>
<!-- Modal ver imagen -->
<div class="overlay" id="modalImagen">
    <div class="modal medio" role="dialog" aria-modal="true">
        <div class="modal-head">
            <h2><i class="bi bi-image"></i> <span id="tituloImagen"></span></h2>
            <button type="button" class="cerrar" id="cerrarImagen" aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-body img-modal-body">
            <img id="imagenGrande" src="" alt="" style="display:none">
            <div id="sinImagen" class="sin-imagen" style="display:none">
                <i class="bi bi-image"></i>
                <p>Este producto no tiene imagen cargada.</p>
            </div>
        </div>
    </div>
</div>
<script>
const modal  = document.getElementById('modalBaja');
const abrir  = () => modal.classList.add('abierto');
const cerrar = () => modal.classList.remove('abierto');

document.querySelectorAll('.js-baja').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('nombreProductoBaja').textContent = btn.dataset.nombre;
        document.getElementById('btnConfirmarBajaProducto').href = 'index.php?action=eliminar&id=' + btn.dataset.id;
        abrir();
    });
});
document.getElementById('cerrarBaja').addEventListener('click', cerrar);
document.getElementById('cancelarBaja').addEventListener('click', cerrar);
modal.addEventListener('click', e => { if (e.target === modal) cerrar(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrar(); });
// ---- Modal ver imagen ----
const modalImg = document.getElementById('modalImagen');
const imgGrande = document.getElementById('imagenGrande');
const sinImg = document.getElementById('sinImagen');
const cerrarImg = () => modalImg.classList.remove('abierto');

document.querySelectorAll('.js-ver').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('tituloImagen').textContent = btn.dataset.nombre;
        if (btn.dataset.imagen) {
            imgGrande.src = btn.dataset.imagen;
            imgGrande.alt = btn.dataset.nombre;
            imgGrande.style.display = 'block';
            sinImg.style.display = 'none';
        } else {
            imgGrande.style.display = 'none';
            sinImg.style.display = 'block';
        }
        modalImg.classList.add('abierto');
    });
});
document.getElementById('cerrarImagen').addEventListener('click', cerrarImg);
modalImg.addEventListener('click', e => { if (e.target === modalImg) cerrarImg(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarImg(); });
</script>

</body>
</html>