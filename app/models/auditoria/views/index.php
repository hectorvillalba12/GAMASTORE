<?php
$tablaSel  = $_GET['tabla'] ?? '';
$porPagina = 25;

// Paginación hecha en la vista (el controlador devuelve todos los movimientos)
$total        = count($movimientos);
$totalPaginas = max(1, (int) ceil($total / $porPagina));
$pagina       = min(max(1, (int) ($_GET['pagina'] ?? 1)), $totalPaginas);
$visibles     = array_slice($movimientos, ($pagina - 1) * $porPagina, $porPagina);
$desde        = $total ? ($pagina - 1) * $porPagina + 1 : 0;
$hasta        = min($pagina * $porPagina, $total);

// Color de cada tipo de acción
$claseAccion = [
    'crear'     => 'normal',
    'editar'    => 'ajuste',
    'baja'      => 'sobre',
    'reactivar' => 'info',
];

// Convierte el JSON guardado en una lista "campo: valor".
// Si se pasa $comparar, resalta los campos que cambiaron respecto de esos datos.
$renderDatos = function ($json, $comparar = null) {
    if (!$json) return '<span class="kv-vacio">—</span>';

    $datos = json_decode($json, true);
    if (!is_array($datos)) {
        return '<span class="kv-vacio">' . htmlspecialchars($json) . '</span>';
    }

    $otros = $comparar ? json_decode($comparar, true) : null;

    $html = '<div class="kv">';
    foreach ($datos as $campo => $valor) {
        if (is_array($valor))      $txt = json_encode($valor, JSON_UNESCAPED_UNICODE);
        elseif (is_bool($valor))   $txt = $valor ? 'sí' : 'no';
        else                       $txt = (string) $valor;
        if ($txt === '') $txt = '—';

        $cambio = is_array($otros) && array_key_exists($campo, $otros) && $otros[$campo] != $valor;

        $html .= '<div' . ($cambio ? ' class="cambio"' : '') . '><b>' . htmlspecialchars($campo) . ':</b> '
              .  '<span>' . htmlspecialchars($txt) . '</span></div>';
    }
    return $html . '</div>';
};
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Auditoría | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="css/gamastore.css?v=<?= @filemtime(__DIR__ . '/../../../../public/css/gamastore.css') ?>" rel="stylesheet">
</head>
<body>

<?php require __DIR__ . '/../../../ayudantes/navbar.php'; ?>

<main class="wrap">

    <!-- Encabezado -->
    <div class="page-head">
        <div>
            <div class="page-label">Módulo de gestión</div>
            <h1 class="page-titulo"><i class="bi bi-clock-history"></i> Auditoría del sistema</h1>
            <p class="page-sub">Historial de cambios: quién hizo qué y cuándo.</p>
        </div>
        <div class="page-btns">
            <a href="index.php?action=dashboard" class="btn btn-ghost">
                <i class="bi bi-arrow-left"></i> Volver al panel
            </a>
        </div>
    </div>

    <!-- Filtro por módulo -->
    <div class="panel">
        <div class="panel-body">
            <form method="GET" action="index.php" class="filtros">
                <input type="hidden" name="action" value="auditoria">

                <div class="filtro">
                    <label for="tabla">Módulo</label>
                    <div class="input-wrap">
                        <i class="bi bi-funnel icono"></i>
                        <select id="tabla" name="tabla" onchange="this.form.submit()">
                            <option value="">Todos los módulos</option>
                            <?php foreach ($tablas as $t): ?>
                                <option value="<?= htmlspecialchars($t) ?>" <?= ($tablaSel === $t) ? 'selected' : '' ?>>
                                    <?= ucfirst(htmlspecialchars($t)) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <i class="bi bi-chevron-down flecha"></i>
                    </div>
                </div>

                <?php if ($tablaSel !== ''): ?>
                    <a href="index.php?action=auditoria" class="btn btn-ghost">
                        <i class="bi bi-x-circle"></i> Quitar filtro
                    </a>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <!-- Historial -->
    <div class="panel">
        <div class="panel-head">
            <span><i class="bi bi-list-ul"></i> Movimientos registrados</span>
            <span class="cuenta"><?= $total ?> registros</span>
        </div>
        <div class="tabla-wrap">
            <table class="tabla" style="min-width:1100px">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Usuario</th>
                        <th>Módulo</th>
                        <th class="centro">Acción</th>
                        <th>Registro</th>
                        <th>Datos anteriores</th>
                        <th>Datos nuevos</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($visibles)): ?>
                    <tr>
                        <td colspan="7" class="vacio">
                            Todavía no hay registros de auditoría<?= $tablaSel !== '' ? ' para este módulo' : '' ?>.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($visibles as $m): ?>
                    <?php $clase = $claseAccion[$m['accion']] ?? 'neutro'; ?>
                    <tr>
                        <td class="top" style="white-space:nowrap"><?= date('d/m/Y H:i', strtotime($m['fecha'])) ?></td>
                        <td class="top"><?= htmlspecialchars($m['usuario_email'] ?? 'Sistema') ?></td>
                        <td class="top"><span class="chip"><?= ucfirst(htmlspecialchars($m['tabla'])) ?></span></td>
                        <td class="top centro"><span class="estado <?= $clase ?>"><?= htmlspecialchars($m['accion']) ?></span></td>
                        <td class="top id">#<?= htmlspecialchars($m['registro_id']) ?></td>
                        <td class="top"><?= $renderDatos($m['datos_anteriores']) ?></td>
                        <td class="top"><?= $renderDatos($m['datos_nuevos'], $m['datos_anteriores']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Paginación -->
    <?php if ($total > 0): ?>
        <?php
            $paramsBase = ['action' => 'auditoria'];
            if ($tablaSel !== '') $paramsBase['tabla'] = $tablaSel;
            $inicio = max(1, $pagina - 2);
            $fin    = min($totalPaginas, $pagina + 2);
        ?>
        <div class="paginacion">
            <small>Mostrando <?= $desde ?> a <?= $hasta ?> de <?= $total ?> registros</small>

            <?php if ($totalPaginas > 1): ?>
            <nav class="pag" aria-label="Paginación de auditoría">
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

</body>
</html>