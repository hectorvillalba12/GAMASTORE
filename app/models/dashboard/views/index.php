<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">
            <i class="bi bi-shop"></i> GamaStore
        </h1>

        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAyuda">
                <i class="bi bi-question-circle"></i> Ayuda
            </button>
            <a href="index.php?action=logout" class="btn btn-outline-danger btn-sm">
                <i class="bi bi-box-arrow-right"></i> Cerrar sesión
            </a>
        </div>
    </div>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'acceso_denegado'): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="bi bi-lock-fill me-1"></i> No tenés permisos para acceder a esa sección.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'sin_perfil'): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="bi bi-exclamation-triangle me-1"></i> Tu usuario no tiene un perfil asignado. Contactá al administrador.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php
    $modulosUsuario = [];
    $perfil_id = $_SESSION['usuario']['perfil_id'] ?? null;
    if ($perfil_id) {
        $db   = (new Database())->connect();
        $stmt = $db->prepare("SELECT modulo FROM perfil_has_modulo WHERE perfil_id = :perfil_id");
        $stmt->execute(['perfil_id' => $perfil_id]);
        $modulosUsuario = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'modulo');
    }
    ?>

    <div class="row g-4">

        <!-- CLIENTES -->
        <?php if (in_array('clientes', $modulosUsuario)): ?>
        <div class="col-md-4">
            <div class="card shadow text-center h-100">
                <div class="card-body p-4">
                    <i class="bi bi-people-fill fs-1 text-primary mb-3"></i>
                    <h4>Clientes</h4>
                    <p class="text-muted">Gestionar clientes de GamaStore</p>
                    <a href="index.php?action=clientes" class="btn btn-primary w-100">Ir a Clientes</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- PRODUCTOS -->
        <?php if (in_array('productos', $modulosUsuario)): ?>
        <div class="col-md-4">
            <div class="card shadow text-center h-100">
                <div class="card-body p-4">
                    <i class="bi bi-box-seam fs-1 text-success mb-3"></i>
                    <h4>Productos</h4>
                    <p class="text-muted">Gestionar productos de GamaStore</p>
                    <a href="index.php?action=productos" class="btn btn-success w-100">Ir a Productos</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- INVENTARIO -->
        <?php if (in_array('inventario', $modulosUsuario)): ?>
        <div class="col-md-4">
            <div class="card shadow text-center h-100">
                <div class="card-body p-4">
                    <i class="bi bi-boxes fs-1 text-warning mb-3"></i>
                    <h4>Inventario</h4>
                    <p class="text-muted">Gestionar stock y ubicaciones</p>
                    <div class="d-flex flex-column gap-2">
                        <a href="index.php?action=inventario" class="btn btn-warning w-100">Ir a Inventario</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- VENTAS -->
        <?php if (in_array('ventas', $modulosUsuario)): ?>
        <div class="col-md-4">
            <div class="card shadow text-center h-100">
                <div class="card-body p-4">
                    <i class="bi bi-cart-check fs-1 text-info mb-3"></i>
                    <h4>Ventas</h4>
                    <p class="text-muted">Ventas de GamaStore</p>
                    <a href="index.php?action=ventas" class="btn btn-info w-100 text-white">Ir a Ventas</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- PROMOCIONES -->
        <?php if (in_array('promociones', $modulosUsuario)): ?>
        <div class="col-md-4">
            <div class="card shadow text-center h-100">
                <div class="card-body p-4">
                    <i class="bi bi-tags-fill fs-1 text-primary mb-3"></i>
                    <h4>Promociones</h4>
                    <p class="text-muted">Descuentos y promociones vigentes</p>
                    <a href="index.php?action=promociones" class="btn btn-primary w-100">Ir a Promociones</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- USUARIOS -->
        <?php if (in_array('usuarios', $modulosUsuario)): ?>
        <div class="col-md-4">
            <div class="card shadow text-center h-100">
                <div class="card-body p-4">
                    <i class="bi bi-person-gear fs-1 text-secondary mb-3"></i>
                    <h4>Usuarios</h4>
                    <p class="text-muted">Gestionar usuarios y perfiles asignados</p>
                    <a href="index.php?action=usuarios" class="btn btn-secondary w-100">Ir a Usuarios</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- PERFILES -->
        <?php if (in_array('perfiles', $modulosUsuario)): ?>
        <div class="col-md-4">
            <div class="card shadow text-center h-100">
                <div class="card-body p-4">
                    <i class="bi bi-person-badge fs-1 text-danger mb-3"></i>
                    <h4>Perfiles</h4>
                    <p class="text-muted">Gestionar perfiles y módulos asignados</p>
                    <a href="index.php?action=perfiles" class="btn btn-danger w-100">Ir a Perfiles</a>
                </div>
            </div>
        </div>
        <?php endif; ?>

            <!-- REPORTES -->
        <?php if (in_array('reportes', $modulosUsuario)): ?>
        <div class="col-md-4">
            <div class="card shadow text-center h-100">
                <div class="card-body p-4">
                    <i class="bi bi-graph-up fs-1 text-dark mb-3"></i>
                    <h4>Reportes</h4>
                    <p class="text-muted">Ventas, stock, clientes y promociones</p>
                    <a href="index.php?action=reportes" class="btn btn-dark w-100">Ir a Reportes</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
                <!-- AUDITORÍA -->
        <?php if (in_array('auditoria', $modulosUsuario)): ?>
        <div class="col-md-4">
            <div class="card shadow text-center h-100">
                <div class="card-body p-4">
                    <i class="bi bi-clock-history fs-1 text-dark mb-3"></i>
                    <h4>Auditoría</h4>
                    <p class="text-muted">Historial de cambios en el sistema</p>
                    <a href="index.php?action=auditoria" class="btn btn-dark w-100">Ir a Auditoría</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
                <!-- CONFIGURACIONES -->
        <?php if (in_array('configuraciones', $modulosUsuario)): ?>
        <div class="col-md-4">
            <div class="card shadow text-center h-100">
                <div class="card-body p-4">
                    <i class="bi bi-gear-fill fs-1 text-muted mb-3"></i>
                    <h4>Configuraciones</h4>
                    <p class="text-muted">Gestionar marcas y categorías</p>
                    <a href="index.php?action=configuraciones" class="btn btn-secondary w-100">Ir a Configuraciones</a>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

</div>
<!--AYUDA -->
<div class="modal fade" id="modalAyuda" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="bi bi-question-circle"></i> Ayuda - GamaStore</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted">Seleccioná una pregunta para ver la respuesta paso a paso.</p>

                <div class="accordion" id="acordeonAyuda">

                    <!-- 1. PRODUCTO -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#ayudaProducto">
                                <i class="bi bi-box-seam text-success me-2"></i> ¿Cómo se registra un producto?
                            </button>
                        </h2>
                        <div id="ayudaProducto" class="accordion-collapse collapse show" data-bs-parent="#acordeonAyuda">
                            <div class="accordion-body">
                                <ol class="mb-2">
                                    <li>En el panel principal, hacé clic en <strong>Ir a Productos</strong>.</li>
                                    <li>Presioná el botón verde de arriba a la derecha para dar de alta un producto nuevo.</li>
                                    <li>Completá el formulario:
                                        <ul>
                                            <li><strong>Nombre del producto</strong></li>
                                            <li><strong>Tipo de zapatilla</strong></li>
                                            <li><strong>Descripción</strong></li>
                                            <li><strong>Precio</strong></li>
                                            <li><strong>Talle, Color, Marca y Categoría</strong> (se eligen de las listas desplegables)</li>
                                        </ul>
                                    </li>
                                    <li>Presioná el botón verde de guardar. El producto aparecerá en el listado.</li>
                                </ol>
                                <div class="alert alert-info py-2 mb-0">
                                    <i class="bi bi-info-circle"></i> Si no encontrás una marca o categoría en las listas, primero cargala desde el módulo <strong>Configuraciones</strong>.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. CLIENTE -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ayudaCliente">
                                <i class="bi bi-people-fill text-primary me-2"></i> ¿Cómo se registra un cliente?
                            </button>
                        </h2>
                        <div id="ayudaCliente" class="accordion-collapse collapse" data-bs-parent="#acordeonAyuda">
                            <div class="accordion-body">
                                <ol class="mb-2">
                                    <li>En el panel principal, hacé clic en <strong>Ir a Clientes</strong>.</li>
                                    <li>Presioná el botón verde de arriba a la derecha para crear un cliente nuevo.</li>
                                    <li>Completá los datos:
                                        <ul>
                                            <li><strong>Nombre</strong> y <strong>Apellido</strong></li>
                                            <li><strong>DNI</strong></li>
                                            <li><strong>Teléfono</strong></li>
                                            <li><strong>Email</strong></li>
                                        </ul>
                                    </li>
                                    <li>Presioná el botón verde de guardar. El cliente aparecerá en el listado con su fecha de registro.</li>
                                </ol>
                                <div class="alert alert-info py-2 mb-0">
                                    <i class="bi bi-info-circle"></i> Para buscar un cliente ya registrado, usá el buscador del listado.
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. VENTA -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ayudaVenta">
                                <i class="bi bi-cart-check text-info me-2"></i> ¿Cómo se realiza una venta?
                            </button>
                        </h2>
                        <div id="ayudaVenta" class="accordion-collapse collapse" data-bs-parent="#acordeonAyuda">
                            <div class="accordion-body">
                                <ol class="mb-2">
                                    <li>En el panel principal, hacé clic en <strong>Ir a Ventas</strong>.</li>
                                    <li>Presioná el botón verde de arriba a la derecha para crear una <strong>Nueva Venta</strong>.</li>
                                    <li>En <strong>Cliente</strong>, abrí el selector y elegí el cliente (el cliente debe estar registrado previamente).</li>
                                    <li>Elegí el <strong>Método de Pago</strong>: Efectivo, Tarjeta Débito, Tarjeta Crédito o Transferencia.</li>
                                    <li>En la tabla de productos, seleccioná cada <strong>Producto</strong> (se muestra su talle, stock y precio) e indicá la <strong>cantidad</strong>.</li>
                                    <li>Si el producto tiene una promoción vigente, podés aplicarla en la columna <strong>Promoción</strong>.</li>
                                    <li>Usá el botón <strong>Agregar</strong> para sumar más productos a la venta.</li>
                                    <li>Revisá el total y presioná el botón verde para confirmar la venta.</li>
                                </ol>
                                <div class="alert alert-warning py-2 mb-0">
                                    <i class="bi bi-exclamation-triangle"></i> Podes ver las ventas canceladas (si la cancelas) alado del boton nueva venta, Y tambien Podes buscar una compra que haya echo un cliente .
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 4. PROMOCION -->
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#ayudaPromo">
                                <i class="bi bi-tags-fill text-primary me-2"></i> ¿Cómo se crea una promoción?
                            </button>
                        </h2>
                        <div id="ayudaPromo" class="accordion-collapse collapse" data-bs-parent="#acordeonAyuda">
                            <div class="accordion-body">
                                <ol class="mb-2">
                                    <li>En el panel principal, hacé clic en <strong>Ir a Promociones</strong>.</li>
                                    <li>Presioná el botón verde de arriba a la derecha para crear una <strong>Nueva Promoción</strong>.</li>
                                    <li>Completá los datos generales:
                                        <ul>
                                            <li><strong>Nombre de la promoción</strong></li>
                                            <li><strong>Descripción / motivo</strong></li>
                                            <li><strong>Tipo de descuento:</strong> Porcentaje (%) o Monto fijo ($)</li>
                                            <li><strong>Valor del descuento</strong></li>
                                            <li><strong>Fecha de inicio</strong> y <strong>Fecha de fin</strong></li>
                                        </ul>
                                    </li>
                                    <li>En <strong>Ámbito de aplicación</strong> elegí a qué se aplica:
                                        <ul>
                                            <li><strong>Productos específicos:</strong> tildá los productos que querés incluir.</li>
                                            <li><strong>Toda una categoría:</strong> seleccioná la categoría completa.</li>
                                        </ul>
                                    </li>
                                    <li>Presioná el botón verde de guardar.</li>
                                </ol>
                                <div class="alert alert-info py-2 mb-0">
                                    <i class="bi bi-info-circle"></i> Desde el listado de promociones podés editarlas, desactivarlas o reactivarlas cuando quieras.
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>