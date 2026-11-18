<?php
$errores = [
    'sin_productos'      => 'Agregá al menos un producto a la venta.',
    'stock_insuficiente' => 'Uno de los productos seleccionados no tiene stock suficiente.',
    'error_al_guardar'   => 'Ocurrió un error al registrar la venta. Intentá de nuevo.',
    'cliente_requerido'  => 'Seleccioná un cliente registrado para realizar la venta.',
    'cliente_invalido'   => 'El cliente seleccionado no existe o está dado de baja.',
    'metodo_invalido'    => 'El método de pago seleccionado no es válido.',
    'pago_invalido'      => 'En un pago combinado, efectivo y transferencia deben ser mayores a 0 y sumar exactamente el total de la venta.',
];
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nueva Venta | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap">

    <div class="page-head">
        <div>
            <div class="page-label">Ventas</div>
            <h1 class="page-titulo"><i class="bi bi-cart-plus"></i> Nueva venta</h1>
            <p class="page-sub">Elegí el cliente, el método de pago y los productos.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=ventas" class="btn btn-ghost"><i class="bi bi-arrow-left"></i> Volver</a>
        </div>
    </div>

    <?php if (isset($errores[$error])): ?>
        <div class="alerta danger">
            <i class="bi bi-exclamation-triangle-fill"></i> <?= $errores[$error] ?>
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <form action="index.php?action=ventas_guardar" method="POST" id="form_venta">

        <div class="venta-grid">

            <!-- Datos generales -->
            <div class="panel">
                <div class="panel-head">
                    <span><i class="bi bi-info-circle"></i> Datos de la venta</span>
                </div>
                <div class="panel-body">
                    
                    <div class="campo" style="margin-bottom:18px">
                        <label>Cliente</label>
                        <input type="hidden" name="cliente_idcliente" id="cliente_id" value="">
                        <div class="cli-row">
                            <button type="button" class="btn-select" id="btn_cliente" onclick="abrirSelectorCliente()">
                                <i class="bi bi-person"></i> Seleccionar cliente
                            </button>
                        </div>
                    </div>

                    <div class="campo">
                        <label for="metodo_de_pago">Método de pago</label>
                        <div class="input-wrap">
                            <i class="bi bi-credit-card icono"></i>
                            <select id="metodo_de_pago" name="metodo_de_pago" required>
                                <option value="">Seleccionar</option>
                                <option value="Efectivo">Efectivo</option>
                                <option value="Tarjeta Débito">Tarjeta Débito</option>
                                <option value="Tarjeta Crédito">Tarjeta Crédito</option>
                                <option value="Transferencia">Transferencia</option>
                                <option value="Mixto">Efectivo + Transferencia</option>
                            </select>
                            <i class="bi bi-chevron-down flecha"></i>
                        </div>
                    </div>

                    <!-- Pago combinado: solo se muestra si se elige "Efectivo + Transferencia" -->
                    <div id="bloque_mixto" style="display:none;margin-top:18px">
                        <div class="campo" style="margin-bottom:14px">
                            <label for="monto_efectivo">Monto en efectivo</label>
                            <div class="input-wrap">
                                <i class="bi bi-cash-coin icono"></i>
                                <input type="number" id="monto_efectivo" name="monto_efectivo" min="0" step="0.01" placeholder="0.00" disabled>
                            </div>
                        </div>
                        <div class="campo">
                            <label for="monto_transferencia">Monto por transferencia</label>
                            <div class="input-wrap">
                                <i class="bi bi-bank icono"></i>
                                <input type="number" id="monto_transferencia" name="monto_transferencia" min="0" step="0.01" placeholder="0.00" disabled>
                            </div>
                        </div>
                        <small id="msg_mixto" style="display:block;margin-top:8px;min-height:18px"></small>
                    </div>

                    <div class="total-box">
                        <span>Total</span>
                        <b id="label_total">$0.00</b>
                    </div>

                    <button type="submit" class="btn btn-main w100">
                        <i class="bi bi-save"></i> Confirmar venta
                    </button>

                </div>
            </div>

            <!-- Productos -->
            <div class="panel">
                <div class="panel-head">
                    <span><i class="bi bi-box-seam"></i> Productos</span>
                    <button type="button" class="btn btn-ghost btn-sm" onclick="agregarFila(true)">
                        <i class="bi bi-plus-lg"></i> Agregar producto
                    </button>
                </div>

                <!-- Búsqueda rápida por código (sirve también con lector de código de barras) -->
                <div class="panel-body" style="padding-bottom:6px">
                    <div class="input-wrap">
                        <i class="bi bi-upc-scan icono"></i>
                        <input type="text" id="codigo_rapido" placeholder="Escribí o escaneá el código del producto y presioná Enter" autocomplete="off">
                    </div>
                    <small id="msg_codigo" style="display:block;margin-top:8px;min-height:18px"></small>
                </div>

                <div class="tabla-wrap">
                    <table class="tabla" style="min-width:820px">
                        <thead>
                            <tr>
                                <th style="min-width:190px">Producto</th>
                                <th class="centro">Talle</th>
                                <th class="centro">Stock</th>
                                <th class="centro">Cant.</th>
                                <th class="der">P. Unit.</th>
                                <th class="centro">Promoción</th>
                                <th class="der">Subtotal</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="filas_productos"></tbody>
                    </table>
                </div>
            </div>

        </div>
    </form>

</main>

<!-- MODAL: selector de clientes -->
<div class="overlay" id="modalClientes">
    <div class="modal ancho medio" role="dialog" aria-modal="true">
        <div class="modal-head">
            <h2><i class="bi bi-person-lines-fill"></i> Seleccionar cliente</h2>
            <button type="button" class="cerrar" data-cerrar aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-buscar">
            <div class="input-wrap">
                <i class="bi bi-search icono"></i>
                <input type="text" id="buscador_clientes" placeholder="Buscar por nombre, DNI o teléfono..." autocomplete="off">
            </div>
            <small>Tocá un cliente para elegirlo. Enter elige el primero de la lista.</small>
        </div>
        <div class="modal-body scroll">
            <table class="tabla" style="min-width:0">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>DNI</th>
                        <th>Teléfono</th>
                    </tr>
                </thead>
                <tbody id="lista_clientes"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- MODAL: selector de productos -->
<div class="overlay" id="modalProductos">
    <div class="modal ancho" role="dialog" aria-modal="true">
        <div class="modal-head">
            <h2><i class="bi bi-box-seam"></i> Seleccionar producto</h2>
            <button type="button" class="cerrar" data-cerrar aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-buscar">
            <div class="input-wrap">
                <i class="bi bi-search icono"></i>
                <input type="text" id="buscador_productos" placeholder="Buscar por nombre, talle o código (ej: nike 45)..." autocomplete="off">
            </div>
            <small>Tocá un producto para agregarlo a la venta. Enter elige el primero de la lista.</small>
        </div>
        <div class="modal-body scroll">
            <table class="tabla" style="min-width:0">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="centro">Talle</th>
                        <th class="centro">Stock</th>
                        <th class="der">Precio</th>
                        <th class="centro">Promo</th>
                    </tr>
                </thead>
                <tbody id="lista_productos"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
const productosData     = <?= json_encode($productos, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
const promosPorProducto = <?= json_encode($promosPorProducto, JSON_HEX_TAG | JSON_HEX_AMP) ?>;
const clientesData      = <?= json_encode($clientes, JSON_HEX_TAG | JSON_HEX_AMP) ?>;

let filaActual     = null;   // fila (idx) para la que se está eligiendo producto
let ultimaLista    = [];     // resultados actuales del buscador de productos
let ultimaListaCli = [];     // resultados actuales del buscador de clientes

const ovClientes  = document.getElementById('modalClientes');
const ovProductos = document.getElementById('modalProductos');

// ---------- Modales propios ----------
const abrirModal  = el => el.classList.add('abierto');
const cerrarModal = el => el.classList.remove('abierto');

document.querySelectorAll('[data-cerrar]').forEach(b => {
    b.addEventListener('click', () => cerrarModal(b.closest('.overlay')));
});
[ovClientes, ovProductos].forEach(ov => {
    ov.addEventListener('click', e => { if (e.target === ov) cerrarModal(ov); });
});
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { cerrarModal(ovClientes); cerrarModal(ovProductos); }
});

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
const buscadorCli = document.getElementById('buscador_clientes');

function abrirSelectorCliente() {
    buscadorCli.value = '';
    renderClientes('');
    abrirModal(ovClientes);
    setTimeout(() => buscadorCli.focus(), 50);
}

function renderClientes(filtro) {
    const terminos = normalizar(filtro).trim().split(/\s+/).filter(Boolean);
    const tbody    = document.getElementById('lista_clientes');

    ultimaListaCli = clientesData.filter(c => {
        const texto = normalizar(c.nombre_completo + ' ' + (c.dni ?? '') + ' ' + (c.telefono ?? ''));
        return terminos.every(t => texto.includes(t));
    });

    // Fila fija: Consumidor final (sin cliente)
    let html = `<tr class="clic destacada" onclick="elegirCliente('')">
                    <td colspan="3" style="font-weight:600">
                        <i class="bi bi-person" style="color:var(--lila)"></i> Consumidor final
                        <span style="color:var(--muted);font-weight:400;font-size:12px">(sin cliente)</span>
                    </td>
                </tr>`;

    if (!ultimaListaCli.length) {
        html += `<tr><td colspan="3" class="vacio">No se encontraron clientes</td></tr>`;
    } else {
        html += ultimaListaCli.map(c => `
            <tr class="clic" onclick="elegirCliente('${c.id_cliente}')">
                <td class="nombre-prod">${esc(c.nombre_completo)}</td>
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
        btn.className = 'btn-select';
        btn.innerHTML = '<i class="bi bi-person"></i> Consumidor final';
        quitar.classList.add('hide');
    } else {
        const c = clientesData.find(x => x.id_cliente == id);
        if (!c) return;
        hidden.value  = c.id_cliente;
        btn.className = 'btn-select elegido';
        btn.innerHTML = `<i class="bi bi-person-check-fill"></i> ${esc(c.nombre_completo)}` +
                        (c.dni ? ` <small>· DNI ${esc(c.dni)}</small>` : '');
        quitar.classList.remove('hide');
    }

    cerrarModal(ovClientes);
}

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

// =====================================================
//  FILAS DE LA VENTA
// =====================================================
function agregarFila(abrirSelectorAuto = false) {
    const tbody = document.getElementById('filas_productos');
    const idx   = Date.now(); // ID único por fila

    const tr = document.createElement('tr');
    tr.dataset.idx = idx;
    tr.innerHTML = `
        <td>
            <input type="hidden" name="producto_id[]" id="prod_${idx}" value="">
            <button type="button" class="btn-select sm" id="btn_prod_${idx}" onclick="abrirSelector('${idx}')">
                <i class="bi bi-search"></i> Seleccionar producto
            </button>
        </td>
        <td class="centro"><span id="talle_${idx}">—</span></td>
        <td class="centro">
            <span id="stock_${idx}">—</span>
            <input type="hidden" name="precio_unitario[]" id="precio_${idx}" value="0">
            <input type="hidden" name="nombre_producto[]" id="nombre_${idx}" value="">
        </td>
        <td class="centro">
            <input type="number" name="cantidad[]" id="cant_${idx}" class="input-sm"
                   value="1" min="1" required oninput="calcularFila('${idx}')">
        </td>
        <td class="der" id="precio_label_${idx}">$0.00</td>
        <td class="centro" id="promo_celda_${idx}">
            <input type="hidden" name="aplicar_promo[]" id="aplica_promo_${idx}" value="0">
            <label class="switch hide" id="promo_toggle_${idx}" title="Aplicar promoción a esta línea">
                <input type="checkbox" id="chk_promo_${idx}"
                       onchange="document.getElementById('aplica_promo_${idx}').value = this.checked ? '1' : '0'; calcularFila('${idx}')">
                <span class="slider"></span>
            </label>
            <div class="promo-txt" id="promo_label_${idx}"></div>
        </td>
        <td class="der precio" id="subtotal_${idx}">$0.00</td>
        <td class="centro">
            <button type="button" class="btn-icon baja" title="Quitar producto"
                    onclick="this.closest('tr').remove(); calcularTotal()">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);

    if (abrirSelectorAuto) abrirSelector(idx);
    return idx;
}

// =====================================================
//  PRODUCTOS (modal selector)
// =====================================================
const buscador = document.getElementById('buscador_productos');

function abrirSelector(idx) {
    filaActual = idx;
    buscador.value = '';
    renderLista('');
    abrirModal(ovProductos);
    setTimeout(() => buscador.focus(), 50);
}

function renderLista(filtro) {
    const terminos = normalizar(filtro).trim().split(/\s+/).filter(Boolean);
    const tbody    = document.getElementById('lista_productos');

    ultimaLista = productosData.filter(p => {
        const texto = normalizar(p.nombre + ' ' + (p.talle ?? '') + ' ' + (p.codigo ?? ''));
        return terminos.every(t => texto.includes(t));
    });

    // Si lo escrito coincide exacto con un código, ese producto va primero y solo
    const exactos = ultimaLista.filter(p => p.codigo && normalizar(p.codigo) === normalizar(filtro).trim());
    if (exactos.length) ultimaLista = exactos;

    
    if (!ultimaLista.length) {
        tbody.innerHTML = `<tr><td colspan="5" class="vacio">No se encontraron productos</td></tr>`;
        return;
    }

    tbody.innerHTML = ultimaLista.map(p => {
        const stock    = parseInt(p.stock_actual ?? 0);
        const talle    = p.talle ? p.talle : '-';
        const promo    = promosPorProducto[p.id_producto];
        const sinStock = stock <= 0;

        const badgeStock = sinStock
            ? '<span class="estado neutro">Sin stock</span>'
            : `<span class="estado ${stock <= 3 ? 'sobre' : 'normal'}">${stock}</span>`;

        const badgePromo = promo
            ? `<span class="estado bajo"><i class="bi bi-tag-fill"></i> ${esc(textoPromo(promo))}</span>`
            : '';

        return `<tr class="${sinStock ? 'deshab' : 'clic'}"
                    ${sinStock ? '' : `onclick="elegirProducto('${p.id_producto}')"`}>
                    <td class="nombre-prod">${esc(p.nombre)}${p.codigo ? `<br><small style="color:var(--muted);font-weight:400">Cód: ${esc(p.codigo)}</small>` : ''}</td>
                    <td class="centro"><span class="chip">${esc(talle)}</span></td>
                    <td class="centro">${badgeStock}</td>
                    <td class="der precio">$${parseFloat(p.precio).toFixed(2)}</td>
                    <td class="centro">${badgePromo}</td>
                </tr>`;
    }).join('');
}

function elegirProducto(idProducto) {
    const p = productosData.find(x => x.id_producto == idProducto);
    if (!p || filaActual === null) return;
    seleccionarProducto(filaActual, p);
    cerrarModal(ovProductos);
}

// Buscador: filtra mientras se escribe, Enter elige el primero con stock
buscador.addEventListener('input', () => renderLista(buscador.value));
buscador.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
        e.preventDefault();
        const primero = ultimaLista.find(p => parseInt(p.stock_actual ?? 0) > 0);
        if (primero) elegirProducto(primero.id_producto);
    }
});
// ---------- Búsqueda rápida por código (Enter o lector de código de barras) ----------
const codigoRapido = document.getElementById('codigo_rapido');
const msgCodigo    = document.getElementById('msg_codigo');

function avisoCodigo(texto, tipo) {
    msgCodigo.textContent = texto;
    msgCodigo.style.color = tipo === 'ok' ? 'var(--ok)' : 'var(--bad)';
}

function agregarPorCodigo(valor) {
    const codigo = normalizar(valor).trim();
    if (!codigo) return;

    const p = productosData.find(x => x.codigo && normalizar(x.codigo).trim() === codigo);
    if (!p) {
        avisoCodigo(`No existe ningún producto con el código "${valor.trim()}".`, 'error');
        codigoRapido.select();
        return;
    }

    const stock = parseInt(p.stock_actual ?? 0);
    if (stock <= 0) {
        avisoCodigo(`"${p.nombre}" no tiene stock disponible.`, 'error');
        codigoRapido.select();
        return;
    }

    const inputs   = [...document.querySelectorAll('input[name="producto_id[]"]')];
    const yaEsta   = inputs.find(i => i.value == p.id_producto);

    if (yaEsta) {
        // El producto ya está en la venta: se suma 1 unidad (sin pasar el stock)
        const idx  = yaEsta.id.replace('prod_', '');
        const cant = document.getElementById(`cant_${idx}`);
        if ((parseInt(cant.value) || 0) >= stock) {
            avisoCodigo(`Ya agregaste todo el stock disponible de "${p.nombre}" (${stock}).`, 'error');
            codigoRapido.select();
            return;
        }
        cant.value = (parseInt(cant.value) || 0) + 1;
        calcularFila(idx);
    } else {
        // Usa una fila vacía si existe; si no, crea una nueva
        const vacia = inputs.find(i => !i.value);
        const idx   = vacia ? vacia.id.replace('prod_', '') : String(agregarFila(false));
        seleccionarProducto(idx, p);
    }

    avisoCodigo(`Agregado: ${p.nombre}`, 'ok');
    codigoRapido.value = '';
    codigoRapido.focus();
}

codigoRapido.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
        e.preventDefault();               // evita que Enter envíe el formulario
        agregarPorCodigo(codigoRapido.value);
    }
});
codigoRapido.addEventListener('input', () => { msgCodigo.textContent = ''; });

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
    btn.className = 'btn-select sm elegido';
    btn.innerHTML = `<i class="bi bi-pencil-square"></i> ${esc(p.nombre)}`;

    // Promoción
    const promo        = promosPorProducto[p.id_producto];
    const toggleLabel  = document.getElementById(`promo_toggle_${idx}`);
    const labelDiv     = document.getElementById(`promo_label_${idx}`);
    const chk          = document.getElementById(`chk_promo_${idx}`);
    const hiddenAplica = document.getElementById(`aplica_promo_${idx}`);

    if (promo) {
        toggleLabel.classList.remove('hide');
        labelDiv.textContent = `${promo.nombre} (${textoPromo(promo)})`;
    } else {
        toggleLabel.classList.add('hide');
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
    actualizarMixto();
}

// =====================================================
//  PAGO COMBINADO (efectivo + transferencia)
// =====================================================
function totalVenta() {
    return parseFloat(document.getElementById('label_total').textContent.replace('$', '')) || 0;
}

function esMixto() {
    return document.getElementById('metodo_de_pago').value === 'Mixto';
}

// Muestra u oculta los campos de monto según el método elegido
document.getElementById('metodo_de_pago').addEventListener('change', () => {
    const mixto = esMixto();
    const ef = document.getElementById('monto_efectivo');
    const tr = document.getElementById('monto_transferencia');
    document.getElementById('bloque_mixto').style.display = mixto ? 'block' : 'none';
    // Deshabilitados no se envían con el formulario
    ef.disabled = tr.disabled = !mixto;
    ef.required = tr.required = mixto;
    if (!mixto) { ef.value = ''; tr.value = ''; }
    actualizarMixto();
});

// Al escribir el efectivo, la transferencia se completa sola con lo que falta
document.getElementById('monto_efectivo').addEventListener('input', () => {
    const resto = Math.max(0, totalVenta() - (parseFloat(document.getElementById('monto_efectivo').value) || 0));
    document.getElementById('monto_transferencia').value = resto > 0 ? resto.toFixed(2) : '';
    actualizarMixto();
});
document.getElementById('monto_transferencia').addEventListener('input', actualizarMixto);

// Mensaje en vivo: cuánto falta, cuánto sobra o si ya está cubierto
function actualizarMixto() {
    if (!esMixto()) return;
    const ef  = parseFloat(document.getElementById('monto_efectivo').value) || 0;
    const tr  = parseFloat(document.getElementById('monto_transferencia').value) || 0;
    const dif = Math.round((totalVenta() - ef - tr) * 100) / 100;
    const msg = document.getElementById('msg_mixto');
    if (dif > 0)      { msg.textContent = 'Falta asignar $' + dif.toFixed(2);              msg.style.color = 'var(--warn)'; }
    else if (dif < 0) { msg.textContent = 'Te pasaste por $' + Math.abs(dif).toFixed(2);   msg.style.color = 'var(--bad)'; }
    else              { msg.textContent = 'El pago cubre el total ✓';                      msg.style.color = 'var(--ok)'; }
}

document.getElementById('form_venta').addEventListener('submit', e => {
    const ids = document.querySelectorAll('input[name="producto_id[]"]');
    if (!ids.length || [...ids].some(i => !i.value)) {
        e.preventDefault();
        alert('Seleccioná un producto en cada fila (o eliminá las filas vacías).');
        return;
    }

    // Pago combinado: los dos montos > 0 y la suma tiene que ser igual al total
    if (esMixto()) {
        const ef  = parseFloat(document.getElementById('monto_efectivo').value) || 0;
        const tr  = parseFloat(document.getElementById('monto_transferencia').value) || 0;
        const dif = Math.round((totalVenta() - ef - tr) * 100) / 100;
        if (ef <= 0 || tr <= 0) {
            e.preventDefault();
            alert('En un pago combinado, tanto el efectivo como la transferencia deben ser mayores a 0.');
        } else if (dif !== 0) {
            e.preventDefault();
            alert('Efectivo + transferencia debe sumar exactamente el total de la venta.');
        }
    }
});

agregarFila();
</script>

</body>
</html>