<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clientes - GamaStore</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-people"></i> Clientes</h2>
        <div>
            <a href="index.php?action=dashboard" class="btn btn-outline-secondary btn-sm me-2">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
            <a href="index.php?action=create_cliente" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> Nuevo Cliente
            </a>
        </div>
    </div>

    <?php if (isset($_GET['ok'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-check-circle-fill me-1"></i> Cliente guardado con éxito.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['baja'])): ?>
        <div class="alert alert-warning alert-dismissible fade show">
            <i class="bi bi-person-dash-fill me-1"></i> Cliente dado de baja correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['reactivado'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="bi bi-person-check-fill me-1"></i> Cliente reactivado correctamente.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- BUSCADOR DE CLIENTES (nombre, DNI o teléfono) -->
    <div class="card shadow-sm mb-3">
        <div class="card-body">
            <form method="GET" action="index.php" class="row g-2 align-items-end">
                <input type="hidden" name="action" value="clientes">

                <div class="col-12 col-md-8">
                    <label class="form-label fw-semibold" for="buscar">
                        <i class="bi bi-search"></i> Buscar cliente
                    </label>
                    <input type="text" id="buscar" name="buscar" class="form-control"
                        placeholder="Buscar por nombre, DNI o teléfono..." autocomplete="off"
                        value="<?= htmlspecialchars($buscar) ?>">
                </div>

                <div class="col-12 col-md-4">
                    <button type="submit" class="btn btn-dark">
                        <i class="bi bi-funnel"></i> Buscar
                    </button>
                    <a href="index.php?action=clientes" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Limpiar
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- TABLA CLIENTES ACTIVOS -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white">
            <i class="bi bi-person-check"></i> Clientes Activos
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>DNI</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Fecha Registro</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($clientes)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <?= ($buscar !== '') ? 'No se encontraron clientes con esa búsqueda.' : 'No hay clientes activos.' ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($clientes as $c): ?>
                        <tr>
                            <td><?= $c['id_cliente'] ?></td>
                            <td><?= htmlspecialchars($c['nombre']) ?></td>
                            <td><?= htmlspecialchars($c['apellido']) ?></td>
                            <td><?= htmlspecialchars($c['dni']) ?></td>
                            <td><?= htmlspecialchars($c['email']) ?></td>
                            <td><?= htmlspecialchars($c['telefono']) ?></td>
                            <td><?= date('d/m/Y', strtotime($c['fecha_registro'])) ?></td>
                            <td class="text-center">
                                <a href="index.php?action=edit_cliente&id=<?= $c['id_cliente'] ?>"
                                class="btn btn-warning btn-sm text-white" title="Editar">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalBajaCliente"
                                    data-id="<?= $c['id_cliente'] ?>"
                                    data-nombre="<?= htmlspecialchars($c['nombre'] . ' ' . $c['apellido']) ?>"
                                    title="Dar de baja">
                                    <i class="bi bi-person-dash"></i>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- PAGINACIÓN -->
    <?php if ($totalClientes > 0): ?>
        <?php
            // Mantiene la búsqueda en los links de cada página
            $paramsBase = ['action' => 'clientes'];
            if ($buscar !== '') $paramsBase['buscar'] = $buscar;

            // Ventana de páginas visibles: 2 antes y 2 después de la actual
            $inicio = max(1, $pagina - 2);
            $fin    = min($totalPaginas, $pagina + 2);
        ?>
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4 gap-2">
            <small class="text-muted">
                Mostrando <?= $desde ?> a <?= $hasta ?> de <?= $totalClientes ?> clientes
            </small>

            <?php if ($totalPaginas > 1): ?>
            <nav aria-label="Paginación de clientes">
                <ul class="pagination pagination-sm mb-0">

                    <!-- Anterior -->
                    <li class="page-item <?= ($pagina <= 1) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="index.php?<?= http_build_query($paramsBase + ['pagina' => $pagina - 1]) ?>">
                            &laquo; Anterior
                        </a>
                    </li>

                    <!-- Primera página + puntos suspensivos -->
                    <?php if ($inicio > 1): ?>
                        <li class="page-item">
                            <a class="page-link"
                            href="index.php?<?= http_build_query($paramsBase + ['pagina' => 1]) ?>">1</a>
                        </li>
                        <?php if ($inicio > 2): ?>
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Páginas del medio -->
                    <?php for ($p = $inicio; $p <= $fin; $p++): ?>
                        <li class="page-item <?= ($p == $pagina) ? 'active' : '' ?>">
                            <a class="page-link"
                            href="index.php?<?= http_build_query($paramsBase + ['pagina' => $p]) ?>">
                                <?= $p ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <!-- Última página + puntos suspensivos -->
                    <?php if ($fin < $totalPaginas): ?>
                        <?php if ($fin < $totalPaginas - 1): ?>
                            <li class="page-item disabled"><span class="page-link">…</span></li>
                        <?php endif; ?>
                        <li class="page-item">
                            <a class="page-link"
                            href="index.php?<?= http_build_query($paramsBase + ['pagina' => $totalPaginas]) ?>">
                                <?= $totalPaginas ?>
                            </a>
                        </li>
                    <?php endif; ?>

                    <!-- Siguiente -->
                    <li class="page-item <?= ($pagina >= $totalPaginas) ? 'disabled' : '' ?>">
                        <a class="page-link"
                        href="index.php?<?= http_build_query($paramsBase + ['pagina' => $pagina + 1]) ?>">
                            Siguiente &raquo;
                        </a>
                    </li>

                </ul>
            </nav>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- TABLA CLIENTES INACTIVOS (baja lógica) -->
    <?php if (!empty($clientesInactivos)): ?>
    <div class="card shadow-sm border-secondary mb-4">
        <div class="card-header bg-secondary text-white">
            <i class="bi bi-person-x"></i> Clientes Inactivos (baja lógica)
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-secondary">
                        <tr>
                            <th>#</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>DNI</th>
                            <th>Email</th>
                            <th>Teléfono</th>
                            <th>Fecha Registro</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientesInactivos as $c): ?>
                        <tr class="table-light text-muted">
                            <td><?= $c['id_cliente'] ?></td>
                            <td><?= htmlspecialchars($c['nombre']) ?></td>
                            <td><?= htmlspecialchars($c['apellido']) ?></td>
                            <td><?= htmlspecialchars($c['dni']) ?></td>
                            <td><?= htmlspecialchars($c['email']) ?></td>
                            <td><?= htmlspecialchars($c['telefono']) ?></td>
                            <td><?= date('d/m/Y', strtotime($c['fecha_registro'])) ?></td>
                            <td class="text-center">
                                <a href="index.php?action=reactivar_cliente&id=<?= $c['id_cliente'] ?>"
                                class="btn btn-success btn-sm" title="Reactivar cliente">
                                    <i class="bi bi-person-check"></i> Reactivar
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div>

<!-- Modal confirmar baja lógica -->
<div class="modal fade" id="modalBajaCliente" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="bi bi-person-dash"></i> Confirmar Baja</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Dar de baja al cliente <strong id="nombreClienteBaja"></strong>?</p>
                <p class="text-muted small mb-0">
                    <i class="bi bi-info-circle"></i>
                    El cliente <strong>no se elimina</strong> de la base de datos.
                    Queda registrado como inactivo y puede ser reactivado en cualquier momento.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a id="btnConfirmarBajaCliente" href="#" class="btn btn-danger">
                    <i class="bi bi-person-dash"></i> Dar de baja
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('modalBajaCliente').addEventListener('show.bs.modal', function (e) {
    const id     = e.relatedTarget.getAttribute('data-id');
    const nombre = e.relatedTarget.getAttribute('data-nombre');
    document.getElementById('nombreClienteBaja').textContent = nombre;
    document.getElementById('btnConfirmarBajaCliente').href = 'index.php?action=delete_cliente&id=' + id;
});
</script>
</body>
</html>