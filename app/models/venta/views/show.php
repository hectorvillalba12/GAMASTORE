<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Comprobante #<?= $venta['id_venta'] ?> | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap estrecho">

    <!-- Encabezado -->
    <div class="page-head no-print">
        <div>
            <div class="page-label">Ventas</div>
            <h1 class="page-titulo"><i class="bi bi-receipt"></i> Comprobante #<?= $venta['id_venta'] ?></h1>
        </div>
        <div class="page-btns">
            <a href="index.php?action=ventas" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <button type="button" onclick="window.print()" class="btn btn-ghost">
                <i class="bi bi-printer"></i> Imprimir
            </button>
            <?php if ($venta['estado'] !== 'cancelada'): ?>
            <button type="button" class="btn btn-danger" id="abrirCancelar">
                <i class="bi bi-x-circle"></i> Cancelar venta
            </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Mensajes -->
    <?php if (isset($_GET['ok'])): ?>
        <div class="alerta success no-print">
            <i class="bi bi-check-circle-fill"></i> Venta registrada con éxito.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (isset($_GET['cancelada'])): ?>
        <div class="alerta warning no-print">
            <i class="bi bi-exclamation-triangle"></i> La venta fue cancelada y el stock fue restaurado.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (($_GET['error'] ?? '') === 'ya_cancelada'): ?>
        <div class="alerta danger no-print">
            <i class="bi bi-exclamation-triangle-fill"></i> Esta venta ya se encuentra cancelada.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    <?php if (($_GET['error'] ?? '') === 'error_al_cancelar'): ?>
        <div class="alerta danger no-print">
            <i class="bi bi-exclamation-triangle-fill"></i> Ocurrió un error al cancelar la venta. Intentá de nuevo.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>
    
    <?php if (($_GET['error'] ?? '') === 'caja_cerrada'): ?>
        <div class="alerta danger no-print">
            <i class="bi bi-exclamation-triangle-fill"></i> No se puede cancelar: la venta pertenece a una caja que ya fue cerrada.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <!-- Comprobante -->
    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-receipt"></i> GamaStore — Comprobante de venta</span>
            <?php if ($venta['estado'] === 'cancelada'): ?>
                <span class="estado bajo">Cancelada</span>
            <?php else: ?>
                <span class="estado normal">Completada</span>
            <?php endif; ?>
        </div>
        <div class="panel-body">

            <div class="meta-grid">
                <div class="meta"><small>N° de venta</small><b>#<?= $venta['id_venta'] ?></b></div>
                <div class="meta"><small>Fecha</small><b><?= date('d/m/Y H:i', strtotime($venta['fecha'])) ?></b></div>
                <div class="meta"><small>Cliente</small><b><?= htmlspecialchars($venta['nombre_cliente'] ?? 'Consumidor final') ?></b></div>
                <div class="meta"><small>Método de pago</small>
                    <?php if (count($pagos) > 1): ?>
                        <?php foreach ($pagos as $p): ?>
                            <b style="display:block"><?= htmlspecialchars($p['metodo_de_pago']) ?>: $<?= number_format($p['monto'], 2, ',', '.') ?></b>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <b><?= htmlspecialchars($venta['metodo_de_pago']) ?></b>
                    <?php endif; ?>
                </div>
            </div>

            <div class="tabla-wrap">
                <table class="tabla" style="min-width:760px">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Descripción</th>
                            <th class="der">Precio unit.</th>
                            <th class="der">Descuento</th>
                            <th class="der">Subtotal</th>
                            <th class="der">IVA</th>
                            <th class="der">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($detalle as $d): ?>
                        <tr>
                            <td class="nombre-prod"><?= htmlspecialchars($d['nombre_producto']) ?></td>
                            <td><?= htmlspecialchars($d['descripcion']) ?></td>
                            <td class="der">$<?= number_format($d['precio_producto'], 2, ',', '.') ?></td>
                            <td class="der">
                                <?php if ($d['descuento'] > 0): ?>
                                    <span class="desc-neg">-$<?= number_format($d['descuento'], 2, ',', '.') ?></span>
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td class="der">$<?= number_format($d['total_venta'], 2, ',', '.') ?></td>
                            <td class="der"><?= number_format($d['IVA'] * 100, 0) ?>%</td>
                            <td class="der precio">$<?= number_format($d['precio_final'], 2, ',', '.') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="der">TOTAL:</td>
                            <td class="der total-final">$<?= number_format($venta['total'], 2, ',', '.') ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>

</main>

<!-- Modal confirmar cancelación -->
<?php if ($venta['estado'] !== 'cancelada'): ?>
<div class="overlay no-print" id="modalCancelar">
    <div class="modal" role="dialog" aria-modal="true">
        <div class="modal-head">
            <h2><i class="bi bi-x-octagon"></i> Cancelar venta</h2>
            <button type="button" class="cerrar" id="cerrarCancelar" aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-body">
            <p>¿Seguro que querés cancelar la venta <strong>#<?= $venta['id_venta'] ?></strong>?</p>
            <div class="nota">
                <i class="bi bi-info-circle"></i>
                <span>Se devolverá el stock de los productos al inventario.</span>
            </div>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn btn-ghost" id="noCancelar">Volver</button>
            <a href="index.php?action=ventas_cancelar&id=<?= $venta['id_venta'] ?>" class="btn btn-danger">
                <i class="bi bi-x-circle"></i> Cancelar venta
            </a>
        </div>
    </div>
</div>

<script>
const modal  = document.getElementById('modalCancelar');
const abrir  = () => modal.classList.add('abierto');
const cerrar = () => modal.classList.remove('abierto');

document.getElementById('abrirCancelar').addEventListener('click', abrir);
document.getElementById('cerrarCancelar').addEventListener('click', cerrar);
document.getElementById('noCancelar').addEventListener('click', cerrar);
modal.addEventListener('click', e => { if (e.target === modal) cerrar(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrar(); });
</script>
<?php endif; ?>

</body>
</html>