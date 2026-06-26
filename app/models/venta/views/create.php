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

    <form action="index.php?action=ventas_guardar" method="POST">

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
                            <select name="cliente_idcliente" class="form-select">
                                <option value="">Consumidor final</option>
                                <?php foreach ($clientes as $c): ?>
                                    <option value="<?= $c['id_cliente'] ?>">
                                        <?= htmlspecialchars($c['nombre_completo']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
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
                        <button type="button" class="btn btn-sm btn-light" onclick="agregarFila()">
                            <i class="bi bi-plus-lg"></i> Agregar producto
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <table class="table mb-0">
                            <thead class="table-secondary">
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center" style="width:80px">Stock</th>
                                    <th class="text-center" style="width:90px">Cant.</th>
                                    <th class="text-end" style="width:110px">P. Unit.</th>
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

<script>
const productosData = <?= json_encode($productos) ?>;

function agregarFila() {
    const tbody = document.getElementById('filas_productos');
    const idx   = Date.now(); // ID único por fila

    let options = '<option value="">-- Producto --</option>';
    productosData.forEach(p => {
        options += `<option value="${p.id_producto}" data-precio="${p.precio}" data-stock="${p.stock_actual ?? 0}">
                        ${p.nombre} (Stock: ${p.stock_actual ?? 0})
                    </option>`;
    });

    const tr = document.createElement('tr');
    tr.dataset.idx = idx;
    tr.innerHTML = `
        <td>
            <select name="producto_id[]" class="form-select form-select-sm" required onchange="alSeleccionar(this, '${idx}')">
                ${options}
            </select>
        </td>
        <td class="text-center align-middle">
            <span id="stock_${idx}">—</span>
            <input type="hidden" name="precio_unitario[]" id="precio_${idx}" value="0">
        </td>
        <td>
            <input type="number" name="cantidad[]" id="cant_${idx}"
                class="form-control form-control-sm" value="1" min="1"
                required oninput="calcularFila('${idx}')">
        </td>
        <td class="text-end align-middle" id="precio_label_${idx}">$0.00</td>
        <td class="text-end align-middle fw-bold" id="subtotal_${idx}">$0.00</td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-danger btn-sm"
                    onclick="this.closest('tr').remove(); calcularTotal()">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
}

function alSeleccionar(sel, idx) {
    const opt    = sel.options[sel.selectedIndex];
    const precio = parseFloat(opt.dataset.precio || 0);
    const stock  = parseInt(opt.dataset.stock || 0);

    document.getElementById(`precio_${idx}`).value              = precio;
    document.getElementById(`stock_${idx}`).textContent         = stock;
    document.getElementById(`precio_label_${idx}`).textContent  = '$' + precio.toFixed(2);
    document.getElementById(`cant_${idx}`).max                  = stock;
    calcularFila(idx);
}

function calcularFila(idx) {
    const cant    = parseFloat(document.getElementById(`cant_${idx}`)?.value || 0);
    const precio  = parseFloat(document.getElementById(`precio_${idx}`)?.value || 0);
    const el = document.getElementById(`subtotal_${idx}`);
    if (el) el.textContent = '$' + (cant * precio).toFixed(2);
    calcularTotal();
}

function calcularTotal() {
    let total = 0;
    document.querySelectorAll('[id^="subtotal_"]').forEach(el => {
        total += parseFloat(el.textContent.replace('$','') || 0);
    });
    document.getElementById('label_total').textContent = '$' + total.toFixed(2);
}

agregarFila();
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>