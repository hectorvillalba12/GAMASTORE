<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nueva Venta - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-cart-plus"></i> Nueva Venta</h2>
        <a href="index.php?action=ventas" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>
    <?php
    $errores = [
        'sin_productos'      => 'Agregá al menos un producto a la venta.',
        'stock_insuficiente' => 'Uno de los productos seleccionados no tiene stock suficiente.',
        'error_al_guardar'   => 'Ocurrió un error al registrar la venta. Intentá de nuevo.',
    ];
    if (isset($_GET['error']) && isset($errores[$_GET['error']])): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= $errores[$_GET['error']] ?>
        </div>
    <?php endif; ?>

    <form action="index.php?action=ventas_guardar" method="POST" id="form_venta">

        <div class="row g-4">

            <!-- Datos generales -->
            <div class="col-md-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header bg-success text-white">
                        <strong><i class="bi bi-info-circle"></i> Datos de la Venta</strong>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">Cliente</label>
                            <!-- Vacío = Consumidor final -->
                            <input type="hidden" name="cliente_idcliente" id="cliente_id" value="">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-secondary text-start flex-grow-1"
                                        id="btn_cliente" onclick="abrirSelectorCliente()">
                                    <i class="bi bi-person"></i> Consumidor final
                                </button>
                                <button type="button" class="btn btn-outline-danger d-none"
                                        id="btn_quitar_cliente" title="Volver a Consumidor final"
                                        onclick="elegirCliente('')">
                                    <i class="bi bi-x-lg"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Método de Pago</label>
                            <select name="metodo_de_pago" class="form-select" required>
                                <option value="">-- Seleccionar --</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta Débito">Tarjeta Débito</option>
                                <option value="Tarjeta Crédito">Tarjeta Crédito</option>
                                <option value="Transferencia">Transferencia</option>
                            </select>
                        </div>

                        <hr>
                        <div class="mb-1 d-flex justify-content-between fs-5 fw-bold text-success">
                            <span>TOTAL:</span>
                            <span id="label_total">$0.00</span>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-save"></i> Confirmar Venta
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Productos -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                        <strong><i class="bi bi-box-seam"></i> Productos</strong>
                        <button type="button" class="btn btn-sm btn-light" onclick="agregarFila(true)">
                            <i class="bi bi-plus-lg"></i> Agregar producto
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-secondary">
                                <tr>
                                    <th style="min-width:170px">Producto</th>
                                    <th class="text-center" style="width:70px">Talle</th>
                                    <th class="text-center" style="width:80px">Stock</th>
                                    <th class="text-center" style="width:90px">Cant.</th>
                                    <th class="text-end" style="width:110px">P. Unit.</th>
                                    <th class="text-center" style="width:150px">Promoción</th>
                                    <th class="text-end" style="width:110px">Subtotal</th>
                                    <th style="width:50px"></th>
                                </tr>
                            </thead>
                            <tbody id="filas_productos"></tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<!-- MODAL: selector de clientes -->
<div class="modal fade" id="modalClientes" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-lines-fill"></i> Seleccionar cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="px-3 pt-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="buscador_clientes" class="form-control"
                        placeholder="Buscar por nombre, DNI o teléfono..." autocomplete="off">
                </div>
                <div class="form-text">Tocá un cliente para elegirlo. Enter elige el primero de la lista.</div>
            </div>
            <div class="modal-body">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th>Cliente</th>
                            <th style="width:140px">DNI</th>
                            <th style="width:160px">Teléfono</th>
                        </tr>
                    </thead>
                    <tbody id="lista_clientes"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL: selector de productos -->
<div class="modal fade" id="modalProductos" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-box-seam"></i> Seleccionar producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="px-3 pt-3">
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" id="buscador_productos" class="form-control"
                        placeholder="Buscar por nombre o talle (ej: nike 45)..." autocomplete="off">
                </div>
                <div class="form-text">Tocá un producto para agregarlo a la venta. Enter elige el primero de la lista.</div>
            </div>
            <div class="modal-body">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light sticky-top">
                        <tr>
                            <th>Producto</th>
                            <th class="text-center" style="width:90px">Talle</th>
                            <th class="text-center" style="width:110px">Stock</th>
                            <th class="text-end" style="width:130px">Precio</th>
                            <th class="text-center" style="width:150px">Promo</th>
                        </tr>
                    </thead>
                    <tbody id="lista_productos"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
const productosData      = <?= json_encode($productos) ?>;
const promosPorProducto   = <?= json_encode($promosPorProducto) ?>;
const clientesData       = <?= json_encode($clientes) ?>;

let filaActual      = null;   // fila (idx) para la que se está eligiendo producto
let modalProductos  = null;
let modalClientes   = null;
let ultimaLista     = [];     // resultados actuales del buscador de productos
let ultimaListaCli  = [];     // resultados actuales del buscador de clientes

// ---------- Utilidades ----------
function esc(t) {
    return String(t ?? '').replace(/[&<>"']/g, c => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[c]));
}

function normalizar(t) {
    return String(t ?? '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
}

function textoPromo(promo) {
    return promo.tipo_descuento === 'porcentaje'
        ? `-${promo.descuento_porcentaje}%`
        : `-$${parseFloat(promo.monto_fijo).toFixed(2)}`;
}

// =====================================================
//  CLIENTES
// =====================================================
function abrirSelectorCliente() {
    if (!modalClientes) {
        modalClientes = new bootstrap.Modal(document.getElementById('modalClientes'));
    }
    document.getElementById('buscador_clientes').value = '';
    renderClientes('');
    modalClientes.show();
}

function renderClientes(filtro) {
    const terminos = normalizar(filtro).trim().split(/\s+/).filter(Boolean);
    const tbody    = document.getElementById('lista_clientes');

    ultimaListaCli = clientesData.filter(c => {
        const texto = normalizar(c.nombre_completo + ' ' + (c.dni ?? '') + ' ' + (c.telefono ?? ''));
        return terminos.every(t => texto.includes(t));
    });

    // Fila fija: Consumidor final (sin cliente)
    let html = `<tr class="table-success" style="cursor:pointer" onclick="elegirCliente('')">
                    <td colspan="3" class="fw-semibold">
                        <i class="bi bi-person"></i> Consumidor final
                        <span class="text-muted fw-normal small">(sin cliente)</span>
                    </td>
                </tr>`;

    if (!ultimaListaCli.length) {
        html += `<tr><td colspan="3" class="text-center text-muted py-4">
                No se encontraron clientes
                </td></tr>`;
    } else {
        html += ultimaListaCli.map(c => `
            <tr style="cursor:pointer" onclick="elegirCliente('${c.id_cliente}')">
                <td class="fw-semibold">${esc(c.nombre_completo)}</td>
                <td>${esc(c.dni ?? '-')}</td>
                <td>${esc(c.telefono ?? '-')}</td>
            </tr>`).join('');
    }

    tbody.innerHTML = html;
}

function elegirCliente(id) {
    const hidden = document.getElementById('cliente_id');
    const btn    = document.getElementById('btn_cliente');
    const quitar = document.getElementById('btn_quitar_cliente');

    if (!id) {
        hidden.value  = '';
        btn.className = 'btn btn-outline-secondary text-start flex-grow-1';
        btn.innerHTML = '<i class="bi bi-person"></i> Consumidor final';
        quitar.classList.add('d-none');
    } else {
        const c = clientesData.find(x => x.id_cliente == id);
        if (!c) return;
        hidden.value  = c.id_cliente;
        btn.className = 'btn btn-outline-success text-start flex-grow-1';
        btn.innerHTML = `<i class="bi bi-person-check-fill"></i> ${esc(c.nombre_completo)}` +
                        (c.dni ? ` <span class="text-muted small">· DNI ${esc(c.dni)}</span>` : '');
        quitar.classList.remove('d-none');
    }

    if (modalClientes) modalClientes.hide();
}

const buscadorCli = document.getElementById('buscador_clientes');
buscadorCli.addEventListener('input', () => renderClientes(buscadorCli.value));
buscadorCli.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
        e.preventDefault();
        // Enter solo elige si hay algo escrito (evita elegir un cliente por accidente)
        if (buscadorCli.value.trim() && ultimaListaCli.length) {
            elegirCliente(ultimaListaCli[0].id_cliente);
        }
    }
});
document.getElementById('modalClientes').addEventListener('shown.bs.modal', () => buscadorCli.focus());

// =====================================================
//  FILAS DE LA VENTA
// =====================================================
function agregarFila(abrirSelectorAuto = false) {
    const tbody = document.getElementById('filas_productos');
    const idx   = Date.now(); // ID único por fila

    const tr = document.createElement('tr');
    tr.dataset.idx = idx;
    tr.innerHTML = `
        <td class="align-middle">
            <input type="hidden" name="producto_id[]" id="prod_${idx}" value="">
            <button type="button" class="btn btn-outline-secondary btn-sm w-100 text-start"
                    id="btn_prod_${idx}" onclick="abrirSelector('${idx}')">
                <i class="bi bi-search"></i> Seleccionar producto
            </button>
        </td>
        <td class="text-center align-middle">
            <span id="talle_${idx}">—</span>
        </td>
        <td class="text-center align-middle">
            <span id="stock_${idx}">—</span>
            <input type="hidden" name="precio_unitario[]" id="precio_${idx}" value="0">
            <input type="hidden" name="nombre_producto[]" id="nombre_${idx}" value="">
        </td>
        <td>
            <input type="number" name="cantidad[]" id="cant_${idx}"
                class="form-control form-control-sm" value="1" min="1"
                required oninput="calcularFila('${idx}')">
        </td>
        <td class="text-end align-middle" id="precio_label_${idx}">$0.00</td>
        <td class="text-center align-middle" id="promo_celda_${idx}">
            <input type="hidden" name="aplicar_promo[]" id="aplica_promo_${idx}" value="0">
            <div class="form-check form-switch d-flex justify-content-center d-none" id="promo_toggle_${idx}">
                <input class="form-check-input" type="checkbox" role="switch" id="chk_promo_${idx}"
                    title="Aplicar promoción a esta línea"
                    onchange="document.getElementById('aplica_promo_${idx}').value = this.checked ? '1' : '0'; calcularFila('${idx}')">
            </div>
            <div class="small text-muted" id="promo_label_${idx}"></div>
        </td>
        <td class="text-end align-middle fw-bold" id="subtotal_${idx}">$0.00</td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-danger btn-sm"
                    onclick="this.closest('tr').remove(); calcularTotal()">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);

    if (abrirSelectorAuto) abrirSelector(idx);
}

// =====================================================
//  PRODUCTOS (modal selector)
// =====================================================
function abrirSelector(idx) {
    filaActual = idx;
    if (!modalProductos) {
        modalProductos = new bootstrap.Modal(document.getElementById('modalProductos'));
    }
    document.getElementById('buscador_productos').value = '';
    renderLista('');
    modalProductos.show();
}

function renderLista(filtro) {
    const terminos = normalizar(filtro).trim().split(/\s+/).filter(Boolean);
    const tbody    = document.getElementById('lista_productos');

    ultimaLista = productosData.filter(p => {
        const texto = normalizar(p.nombre + ' ' + (p.talle ?? ''));
        return terminos.every(t => texto.includes(t));
    });

    if (!ultimaLista.length) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center text-muted py-4">
                            No se encontraron productos
                        </td></tr>`;
        return;
    }

    tbody.innerHTML = ultimaLista.map(p => {
        const stock    = parseInt(p.stock_actual ?? 0);
        const talle    = p.talle ? p.talle : '-';
        const promo    = promosPorProducto[p.id_producto];
        const sinStock = stock <= 0;

        const badgeStock = sinStock
            ? '<span class="badge bg-secondary">Sin stock</span>'
            : `<span class="badge ${stock <= 3 ? 'bg-warning text-dark' : 'bg-success'}">${stock}</span>`;

        const badgePromo = promo
            ? `<span class="badge bg-danger">🏷️ ${esc(textoPromo(promo))}</span>`
            : '';

        return `<tr class="${sinStock ? 'text-muted' : ''}"
                    style="${sinStock ? 'opacity:.55' : 'cursor:pointer'}"
                    ${sinStock ? '' : `onclick="elegirProducto('${p.id_producto}')"`}>
                    <td class="fw-semibold">${esc(p.nombre)}</td>
                    <td class="text-center">${esc(talle)}</td>
                    <td class="text-center">${badgeStock}</td>
                    <td class="text-end">$${parseFloat(p.precio).toFixed(2)}</td>
                    <td class="text-center">${badgePromo}</td>
                </tr>`;
    }).join('');
}

function elegirProducto(idProducto) {
    const p = productosData.find(x => x.id_producto == idProducto);
    if (!p || filaActual === null) return;
    seleccionarProducto(filaActual, p);
    modalProductos.hide();
}

// Buscador: filtra mientras se escribe, Enter elige el primero con stock
const buscador = document.getElementById('buscador_productos');
buscador.addEventListener('input', () => renderLista(buscador.value));
buscador.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
        e.preventDefault();
        const primero = ultimaLista.find(p => parseInt(p.stock_actual ?? 0) > 0);
        if (primero) elegirProducto(primero.id_producto);
    }
});
document.getElementById('modalProductos').addEventListener('shown.bs.modal', () => buscador.focus());

// ---------- Aplicar el producto elegido a la fila ----------
function seleccionarProducto(idx, p) {
    const precio = parseFloat(p.precio || 0);
    const stock  = parseInt(p.stock_actual ?? 0);
    const talle  = p.talle ? p.talle : '—';

    document.getElementById(`prod_${idx}`).value               = p.id_producto;
    document.getElementById(`precio_${idx}`).value             = precio;
    document.getElementById(`nombre_${idx}`).value             = p.nombre;
    document.getElementById(`talle_${idx}`).textContent        = talle;
    document.getElementById(`stock_${idx}`).textContent        = stock;
    document.getElementById(`precio_label_${idx}`).textContent = '$' + precio.toFixed(2);

    const cant = document.getElementById(`cant_${idx}`);
    cant.max   = stock;
    cant.value = 1;

    const btn = document.getElementById(`btn_prod_${idx}`);
    btn.className = 'btn btn-outline-dark btn-sm w-100 text-start';
    btn.innerHTML = `<i class="bi bi-pencil-square"></i> ${esc(p.nombre)}`;

    // Promoción
    const promo        = promosPorProducto[p.id_producto];
    const toggleDiv    = document.getElementById(`promo_toggle_${idx}`);
    const labelDiv     = document.getElementById(`promo_label_${idx}`);
    const chk          = document.getElementById(`chk_promo_${idx}`);
    const hiddenAplica = document.getElementById(`aplica_promo_${idx}`);

    if (promo) {
        toggleDiv.classList.remove('d-none');
        labelDiv.textContent = `${promo.nombre} (${textoPromo(promo)})`;
    } else {
        toggleDiv.classList.add('d-none');
        labelDiv.textContent = '';
    }
    chk.checked        = false;   // por defecto la promo viene DESACTIVADA
    hiddenAplica.value = '0';

    calcularFila(idx);
}

// =====================================================
//  CÁLCULOS
// =====================================================
function calcularFila(idx) {
    const cant    = parseFloat(document.getElementById(`cant_${idx}`)?.value || 0);
    const precio  = parseFloat(document.getElementById(`precio_${idx}`)?.value || 0);
    const el = document.getElementById(`subtotal_${idx}`);
    if (!el) { calcularTotal(); return; }

    let subtotal = cant * precio;

    const idProducto = document.getElementById(`prod_${idx}`)?.value || null;
    const promo   = idProducto ? promosPorProducto[idProducto] : null;
    const aplica  = document.getElementById(`aplica_promo_${idx}`)?.value === '1';

    if (promo && aplica) {
        const descuento = promo.tipo_descuento === 'porcentaje'
            ? subtotal * (parseFloat(promo.descuento_porcentaje) / 100)
            : Math.min(parseFloat(promo.monto_fijo), subtotal);
        subtotal = subtotal - descuento;
    }

    el.textContent = '$' + subtotal.toFixed(2);
    calcularTotal();
}

function calcularTotal() {
    let total = 0;
    document.querySelectorAll('[id^="subtotal_"]').forEach(el => {
        total += parseFloat(el.textContent.replace('$','') || 0);
    });
    document.getElementById('label_total').textContent = '$' + total.toFixed(2);
}

// ---------- Validación al confirmar ----------
document.getElementById('form_venta').addEventListener('submit', e => {
    const ids = document.querySelectorAll('input[name="producto_id[]"]');
    if (!ids.length || [...ids].some(i => !i.value)) {
        e.preventDefault();
        alert('Seleccioná un producto en cada fila (o eliminá las filas vacías).');
    }
});

agregarFila();
</script>

</body>
</html>