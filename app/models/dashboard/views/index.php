<?php
// ---------- Usuario ----------
$usuario = $_SESSION['usuario'] ?? [];
$email   = $usuario['email'] ?? '';
$rol     = $usuario['rol'] ?? 'empleado';
$perfil  = ($rol === 'admin') ? 'Administrador' : ucfirst($rol);

$nombre = ucwords(str_replace(['.', '_', '-'], ' ', preg_replace('/\d+/', '', explode('@', $email)[0])));
if (trim($nombre) === '') $nombre = 'Operador';
$primerNombre = explode(' ', trim($nombre))[0];

// ---------- Fecha en español ----------
$dias  = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
$meses = ['','Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
$fechaHoy = $dias[date('w')] . ', ' . date('j') . ' de ' . $meses[(int) date('n')] . ' de ' . date('Y');

// ---------- Módulos del perfil + estadísticas ----------
$modulosUsuario = [];
$db = null;
try {
    $db = (new Database())->connect();
    $perfil_id = $usuario['perfil_id'] ?? null;
    if ($perfil_id) {
        $stmt = $db->prepare("SELECT modulo FROM perfil_has_modulo WHERE perfil_id = :perfil_id");
        $stmt->execute(['perfil_id' => $perfil_id]);
        $modulosUsuario = array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'modulo');
    }
} catch (Throwable $e) {}

$dato = function ($sql) use ($db) {
    try { return $db ? $db->query($sql)->fetchColumn() : 0; }
    catch (Throwable $e) { return 0; }
};

$stockTotal  = (int)   $dato("SELECT COALESCE(SUM(stock_actual),0) FROM inventario WHERE activo = 1");
$prodActivos = (int)   $dato("SELECT COUNT(*) FROM producto WHERE activo = 1");
$stockBajo   = (int)   $dato("SELECT COUNT(*) FROM inventario WHERE activo = 1 AND stock_actual <= stock_minimo");
$clientes    = (int)   $dato("SELECT COUNT(*) FROM cliente WHERE estado = 'activo'");
$promos      = (int)   $dato("SELECT COUNT(*) FROM promocion WHERE activa = 1 AND fecha_fin >= NOW()");
$usuarios    = (int)   $dato("SELECT COUNT(*) FROM usuario WHERE estado = 'activo'");
$proveedoresAct = (int) $dato("SELECT COUNT(*) FROM proveedor WHERE activo = 1");
$cajaAbierta = (int) $dato("SELECT COUNT(*) FROM caja WHERE estado = 'abierta'");
$fmt = fn($n) => number_format($n, 0, ',', '.');

// ---------- Tarjetas: [clave, icono, título, descripción, link, texto botón, badge, badge_alerta] ----------
$modulos = [
    ['clientes',        'bi-people',        'Clientes',        'Gestionar clientes de GamaStore y su historial de compras.', 'clientes',        'Ir a Clientes',        $clientes . ' activos', false],
    ['productos',       'bi-box-seam',      'Productos',       'Gestionar productos, talles, marcas y categorías.',          'productos',       'Ir a Productos',       $prodActivos . ' productos', false],
    ['inventario',      'bi-boxes',         'Inventario',      'Gestionar stock y ubicaciones de depósito.',                 'inventario',      'Ir a Inventario',      $stockBajo > 0 ? $stockBajo . ' alertas stock bajo' : 'Stock al día', $stockBajo > 0],
    ['ventas',          'bi-cart-check',    'Ventas',          'Registrar y consultar las ventas de GamaStore.',             'ventas',          'Ir a Ventas',   'Ventas',        false],
    ['promociones',     'bi-tags',          'Promociones',     'Descuentos y promociones vigentes.',                         'promociones',     'Ir a Promociones',     $promos . ' activas', false],
    ['usuarios',        'bi-person-gear',   'Usuarios',        'Gestionar usuarios y perfiles asignados.',                   'usuarios',        'Ir a Usuarios',        $usuarios . ' operadores', false],
    ['perfiles',        'bi-person-badge',  'Perfiles',        'Gestionar perfiles y módulos asignados.',                    'perfiles',        'Ir a Perfiles',        'Accesos', false],
    ['reportes',        'bi-graph-up',      'Reportes',        'Ventas, stock, clientes y promociones.',                     'reportes',        'Ir a Reportes',        'Análisis', false],
    ['auditoria',       'bi-clock-history', 'Auditoría',       'Historial de cambios en el sistema.',                        'auditoria',       'Ir a Auditoría',       'Historial', false],
    ['configuraciones', 'bi-gear',          'Configuraciones', 'Gestionar marcas, categorías y talles.',                             'configuraciones', 'Ir a Configuraciones', 'Marcas,categorías y talles', false],
    ['proveedores',     'bi-truck',         'Proveedores',     'Gestionar proveedores y sus datos de contacto.',             'proveedores',     'Ir a Proveedores',     $proveedoresAct . ' activos', false],
    ['caja',            'bi-cash-stack',    'Caja',            'Apertura y cierre de caja del local.',                       'caja',            'Ir a Caja',            $cajaAbierta ? 'Caja abierta' : 'Caja cerrada', !$cajaAbierta],
]; 

$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel de Control | GamaStore</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root{
    --bg:#0b0713;
    --panel:#14101f;
    --panel2:#100c1a;
    --border:rgba(168,85,247,.22);
    --text:#ffffff;
    --muted:#a69bbd;
    --soft:#6f6585;
    --violet:#7c3aed;
    --pink:#c026d3;
    --lila:#d28bff;
    --ok:#86efac;
    --warn:#fcd34d;
    --bad:#fca5a5;
}
*{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}

body{
    min-height:100vh;
    font-family:'Inter',system-ui,sans-serif;
    color:var(--text);
    background:
        radial-gradient(circle at 10% 0%, rgba(124,58,237,.22), transparent 40%),
        radial-gradient(circle at 95% 100%, rgba(192,38,211,.15), transparent 40%),
        var(--bg);
}
a{text-decoration:none;color:inherit}

/* ===== Barra superior ===== */
.topbar{
    position:sticky;top:0;z-index:50;
    display:flex;align-items:center;justify-content:space-between;
    gap:14px;
    padding:12px 28px;
    background:rgba(11,7,19,.85);
    backdrop-filter:blur(12px);
    border-bottom:1px solid var(--border);
}
.brand{display:flex;align-items:center;gap:12px}
.brand-logo{
    width:40px;height:40px;border-radius:11px;
    background:#0a0712;border:1px solid var(--border);
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 0 16px rgba(168,85,247,.4);
}
.brand-logo svg{width:30px;height:auto;transform:rotate(-6deg)}
.brand-name{font-size:19px;font-weight:800;letter-spacing:-.4px}
.brand-name span{color:var(--lila)}

.acciones{display:flex;align-items:center;gap:10px}
.btn-ghost{
    display:inline-flex;align-items:center;gap:7px;
    height:38px;padding:0 14px;
    background:transparent;
    border:1px solid var(--border);
    border-radius:10px;
    color:var(--muted);
    font-family:inherit;font-size:13px;font-weight:600;
    cursor:pointer;transition:.2s;
}
.btn-ghost:hover{color:#fff;border-color:var(--violet);background:rgba(124,58,237,.12)}

.user-chip{
    display:flex;align-items:center;gap:10px;
    padding:5px 12px 5px 6px;
    background:var(--panel);
    border:1px solid var(--border);
    border-radius:12px;
}
.avatar{
    width:30px;height:30px;border-radius:9px;
    background:linear-gradient(135deg,var(--violet),var(--pink));
    display:flex;align-items:center;justify-content:center;
    font-weight:800;font-size:13px;
}
.user-info{line-height:1.2}
.user-info b{display:block;font-size:12.5px;font-weight:600;max-width:150px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.user-info small{font-size:10.5px;color:var(--lila);text-transform:uppercase;letter-spacing:.6px;font-weight:700}

.btn-salir{
    width:38px;height:38px;border-radius:10px;
    display:flex;align-items:center;justify-content:center;
    border:1px solid var(--border);
    color:var(--muted);font-size:17px;
    transition:.2s;
}
.btn-salir:hover{color:var(--bad);border-color:rgba(239,68,68,.5);background:rgba(239,68,68,.08)}

/* ===== Contenedor ===== */
.wrap{max-width:1240px;margin:0 auto;padding:24px 28px 40px}

/* ===== Alertas ===== */
.alerta{
    display:flex;align-items:center;gap:10px;
    padding:11px 14px;border-radius:12px;
    font-size:13.5px;margin-bottom:18px;border:1px solid;
}
.alerta.danger {background:rgba(239,68,68,.1); border-color:rgba(239,68,68,.35); color:var(--bad)}
.alerta.warning{background:rgba(245,158,11,.1);border-color:rgba(245,158,11,.35);color:var(--warn)}
.alerta button{
    margin-left:auto;background:none;border:none;
    color:inherit;font-size:18px;cursor:pointer;opacity:.7;
}
.alerta button:hover{opacity:1}

/* ===== Hero ===== */
.hero{
    background:linear-gradient(180deg,#171127,var(--panel));
    border:1px solid var(--border);
    border-radius:22px;
    padding:28px;
    position:relative;
    box-shadow:0 0 50px rgba(124,58,237,.12);
    margin-bottom:34px;
}
.hero::before{
    content:"";position:absolute;top:0;left:26px;right:26px;height:3px;
    border-radius:0 0 4px 4px;
    background:linear-gradient(90deg,var(--violet),var(--pink),#6366f1);
}
.hero-top{
    display:flex;justify-content:space-between;align-items:flex-start;
    gap:20px;flex-wrap:wrap;margin-bottom:24px;
}
.estado-chip{
    display:inline-flex;align-items:center;gap:8px;
    padding:5px 12px;
    background:rgba(124,58,237,.14);
    border:1px solid var(--border);
    border-radius:999px;
    font-size:11px;font-weight:700;letter-spacing:.7px;
    text-transform:uppercase;color:var(--lila);
    margin-bottom:14px;
}
.punto{width:8px;height:8px;border-radius:50%;background:#a855f7;box-shadow:0 0 8px #a855f7}
.hero h1{font-size:clamp(24px,4vw,36px);font-weight:800;letter-spacing:-.8px;line-height:1.15}
.hero h1 span{color:var(--lila)}
.fecha{
    display:flex;gap:16px;flex-wrap:wrap;
    margin-top:10px;color:var(--muted);font-size:13.5px;
}
.fecha i{color:var(--lila);margin-right:5px}

.hero-btns{display:flex;gap:10px;flex-wrap:wrap}
.btn-main{
    display:inline-flex;align-items:center;gap:8px;
    height:42px;padding:0 20px;
    border-radius:11px;border:none;
    background:linear-gradient(90deg,var(--violet),var(--pink));
    color:#fff;font-family:inherit;font-size:12.5px;font-weight:700;
    letter-spacing:.5px;text-transform:uppercase;
    box-shadow:0 8px 22px rgba(124,58,237,.4);
    transition:.2s;
}
.btn-main:hover{transform:translateY(-2px);box-shadow:0 12px 28px rgba(192,38,211,.45)}

/* ===== Estadísticas ===== */
.stats{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    background:var(--panel2);
    border:1px solid rgba(255,255,255,.06);
    border-radius:16px;
    overflow:hidden;
}
.stat{padding:18px 20px;border-right:1px solid rgba(255,255,255,.06);position:relative}
.stat:last-child{border-right:none}
.stat-label{
    font-size:10.5px;font-weight:700;letter-spacing:.8px;
    text-transform:uppercase;color:var(--soft);
    display:flex;justify-content:space-between;align-items:center;
}
.stat-label i{color:var(--lila);font-size:15px}
.stat-valor{font-size:30px;font-weight:800;letter-spacing:-.8px;margin:6px 0 2px}
.stat-valor small{font-size:12px;font-weight:600;color:var(--soft);letter-spacing:.5px;margin-left:4px}
.stat-sub{font-size:12px;color:var(--muted)}
.stat-sub.up{color:var(--ok)}
.stat-sub.down{color:var(--bad)}

/* ===== Módulos ===== */
.sec-label{
    font-size:11px;font-weight:700;letter-spacing:1px;
    text-transform:uppercase;color:var(--lila);margin-bottom:4px;
}
.sec-titulo{font-size:24px;font-weight:800;letter-spacing:-.5px;margin-bottom:18px}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(290px,1fr));
    gap:18px;
}
.mod{
    background:linear-gradient(180deg,#171127,var(--panel));
    border:1px solid var(--border);
    border-radius:18px;
    padding:20px;
    display:flex;flex-direction:column;
    transition:.2s;
}
.mod:hover{
    transform:translateY(-3px);
    border-color:rgba(168,85,247,.55);
    box-shadow:0 12px 34px rgba(124,58,237,.2);
}
.mod-top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:16px}
.mod-icono{
    width:44px;height:44px;border-radius:12px;
    background:rgba(124,58,237,.16);
    border:1px solid var(--border);
    display:flex;align-items:center;justify-content:center;
    font-size:21px;color:var(--lila);
}
.badge{
    padding:4px 11px;
    background:rgba(124,58,237,.14);
    border:1px solid var(--border);
    border-radius:999px;
    font-size:10.5px;font-weight:700;letter-spacing:.4px;
    text-transform:uppercase;color:var(--lila);
}
.badge.alerta-stock{
    background:rgba(239,68,68,.12);
    border-color:rgba(239,68,68,.4);
    color:var(--bad);
}
.mod h3{font-size:19px;font-weight:700;margin-bottom:6px}
.mod p{font-size:13.5px;line-height:1.5;color:var(--muted);margin-bottom:18px;flex:1}
.mod-btn{
    display:flex;justify-content:space-between;align-items:center;
    height:42px;padding:0 16px;
    background:#0b0813;
    border:1px solid rgba(255,255,255,.07);
    border-radius:11px;
    font-size:12px;font-weight:700;letter-spacing:.7px;
    text-transform:uppercase;
    transition:.2s;
}
.mod:hover .mod-btn{
    background:linear-gradient(90deg,var(--violet),var(--pink));
    border-color:transparent;
}

.vacio{
    padding:40px;text-align:center;color:var(--muted);
    border:1px dashed var(--border);border-radius:16px;
}

/* ===== Modal de ayuda ===== */
.overlay{
    position:fixed;inset:0;z-index:100;
    background:rgba(5,3,10,.75);
    backdrop-filter:blur(4px);
    display:none;align-items:center;justify-content:center;
    padding:16px;
}
.overlay.abierto{display:flex}
.modal{
    width:100%;max-width:720px;max-height:88vh;
    display:flex;flex-direction:column;
    background:linear-gradient(180deg,#171127,var(--panel));
    border:1px solid var(--border);
    border-radius:20px;
    box-shadow:0 0 60px rgba(124,58,237,.25);
    overflow:hidden;
}
.modal-head{
    display:flex;justify-content:space-between;align-items:center;
    padding:18px 24px;
    border-bottom:1px solid rgba(255,255,255,.07);
}
.modal-head h2{font-size:18px;font-weight:700;display:flex;align-items:center;gap:10px}
.modal-head h2 i{color:var(--lila)}
.cerrar{
    background:none;border:none;color:var(--muted);
    font-size:22px;cursor:pointer;
}
.cerrar:hover{color:#fff}
.modal-body{padding:18px 24px;overflow-y:auto}
.modal-body > p{color:var(--muted);font-size:13.5px;margin-bottom:14px}

details{
    background:var(--panel2);
    border:1px solid rgba(255,255,255,.07);
    border-radius:12px;
    margin-bottom:10px;
}
details[open]{border-color:var(--border)}
summary{
    list-style:none;cursor:pointer;
    padding:14px 16px;
    font-size:14.5px;font-weight:600;
    display:flex;align-items:center;gap:10px;
}
summary::-webkit-details-marker{display:none}
summary i{color:var(--lila);font-size:17px}
summary::after{
    content:"\F282";
    font-family:"bootstrap-icons";
    margin-left:auto;color:var(--soft);font-size:13px;transition:.2s;
}
details[open] summary::after{transform:rotate(180deg)}
.det-body{padding:0 18px 16px 18px;font-size:13.5px;color:#d9d2e8;line-height:1.55}
.det-body ol{padding-left:20px;margin-bottom:10px}
.det-body ul{padding-left:18px;margin-top:4px}
.det-body li{margin-bottom:5px}
.det-body strong{color:#fff}
.nota{
    padding:10px 12px;border-radius:10px;
    font-size:12.5px;display:flex;gap:8px;
    background:rgba(124,58,237,.1);
    border:1px solid var(--border);color:var(--lila);
}
.nota.aviso{
    background:rgba(245,158,11,.1);
    border-color:rgba(245,158,11,.35);color:var(--warn);
}

/* El dashboard muestra el usuario en su propia barra (leído desde localStorage),
   por eso se oculta el cartel flotante que crea js/sesion.js */
#gamastore-usuario{display:none !important}

/* ===== Responsive ===== */
@media(max-width:900px){
    .stats{grid-template-columns:repeat(2,1fr)}
    .stat:nth-child(2){border-right:none}
    .stat:nth-child(-n+2){border-bottom:1px solid rgba(255,255,255,.06)}
}
@media(max-width:640px){
    .topbar{padding:10px 14px}
    .wrap{padding:18px 14px 30px}
    .hero{padding:22px 18px}
    .user-info{display:none}
    .user-chip{padding:5px}
    .btn-ghost span{display:none}
    .stat{padding:14px}
    .stat-valor{font-size:24px}
}
</style>
</head>

<body>

<!-- ===== Barra superior ===== -->
<header class="topbar">
    <div class="brand">
        <div class="brand-logo">
            <svg viewBox="0 0 124 80" xmlns="http://www.w3.org/2000/svg" aria-label="Zapatilla">
                <defs>
                    <linearGradient id="gz" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0" stop-color="#a855f7"/>
                        <stop offset="1" stop-color="#ec4899"/>
                    </linearGradient>
                </defs>
                <path d="M12 54 L12 28 Q12 21 20 20 L34 18 Q38 27 47 30 L58 33 Q66 41 83 43 Q106 45 114 51 L114 54 Z" fill="url(#gz)"/>
                <path d="M92 43 Q108 45 114 51 L114 54 L96 54 Q98 47 92 43 Z" fill="#fff" opacity=".18"/>
                <path d="M8 56 Q8 53 12 53 L116 53 Q120 53 120 58 L120 62 Q120 66 114 66 L14 66 Q8 66 8 62 Z" fill="#f5f3ff"/>
                <line x1="12" y1="59.5" x2="116" y2="59.5" stroke="#a855f7" stroke-width="1.5" opacity=".6"/>
                <path d="M24 46 Q46 42 66 52" stroke="#fb923c" stroke-width="4" stroke-linecap="round" fill="none"/>
                <g stroke="#fff" stroke-width="2.5" stroke-linecap="round" opacity=".9">
                    <line x1="49" y1="37" x2="56" y2="30"/>
                    <line x1="57" y1="41" x2="64" y2="34"/>
                    <line x1="66" y1="44" x2="73" y2="37"/>
                </g>
            </svg>
        </div>
        <div class="brand-name">Gama<span>Store</span></div>
    </div>

    <div class="acciones">
        <button type="button" class="btn-ghost" id="abrirAyuda">
            <i class="bi bi-question-circle"></i> <span>Ayuda</span>
        </button>

        <!-- Usuario: el nombre se completa desde localStorage (ver script al final) -->
        <div class="user-chip">
            <div class="avatar" id="uAvatar"><?= strtoupper(mb_substr($primerNombre, 0, 1)) ?></div>
            <div class="user-info">
                <b id="uNombre" title="<?= htmlspecialchars($email) ?>"><?= htmlspecialchars(explode('@', $email)[0]) ?></b>
                <small id="uPerfil"><?= htmlspecialchars($perfil) ?></small>
            </div>
        </div>

        <a href="index.php?action=logout" class="btn-salir" title="Cerrar sesión" aria-label="Cerrar sesión">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>
</header>

<main class="wrap">

    <!-- ===== Mensajes ===== -->
    <?php if ($msg === 'acceso_denegado'): ?>
        <div class="alerta danger">
            <i class="bi bi-lock-fill"></i> No tenés permisos para acceder a esa sección.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php elseif ($msg === 'sin_perfil'): ?>
        <div class="alerta warning">
            <i class="bi bi-exclamation-triangle"></i> Tu usuario no tiene un perfil asignado. Contactá al administrador.
            <button type="button" onclick="this.parentElement.remove()">&times;</button>
        </div>
    <?php endif; ?>

    <!-- ===== Hero ===== -->
    <section class="hero">
        <div class="hero-top">
            <div>
                <div class="estado-chip"><span class="punto"></span> Sistema en línea</div>
                <h1>Bienvenido de vuelta, <span><?= htmlspecialchars($primerNombre) ?></span></h1>
                <div class="fecha">
                    <span><i class="bi bi-calendar3"></i><?= $fechaHoy ?></span>
                    <span><i class="bi bi-clock"></i><b id="reloj">--:--:--</b></span>
                </div>
            </div>

            <?php if (in_array('ventas', $modulosUsuario)): ?>
            <div class="hero-btns">
                <a href="index.php?action=ventas_crear" class="btn-main">
                    <i class="bi bi-lightning-charge-fill"></i> Nueva venta
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- ===== Módulos ===== -->
    <div class="sec-label">Módulos de gestión</div>
    <h2 class="sec-titulo">Panel Central GamaStore</h2>

    <?php
    $visibles = array_filter($modulos, fn($m) => in_array($m[0], $modulosUsuario));
    ?>

    <?php if (empty($visibles)): ?>
        <div class="vacio">
            <i class="bi bi-lock" style="font-size:28px;display:block;margin-bottom:8px"></i>
            No tenés módulos habilitados. Contactá al administrador.
        </div>
    <?php else: ?>
    <div class="grid">
        <?php foreach ($visibles as $m): ?>
            <a href="index.php?action=<?= $m[4] ?>" class="mod">
                <div class="mod-top">
                    <div class="mod-icono"><i class="bi <?= $m[1] ?>"></i></div>
                    <span class="badge <?= $m[7] ? 'alerta-stock' : '' ?>"><?= htmlspecialchars($m[6]) ?></span>
                </div>
                <h3><?= $m[2] ?></h3>
                <p><?= $m[3] ?></p>
                <div class="mod-btn"><?= $m[5] ?> <i class="bi bi-arrow-right"></i></div>
            </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

</main>

<!-- ===== Modal Ayuda ===== -->
<div class="overlay" id="overlayAyuda">
    <div class="modal" role="dialog" aria-modal="true">
        <div class="modal-head">
            <h2><i class="bi bi-question-circle"></i> Ayuda - GamaStore</h2>
            <button type="button" class="cerrar" id="cerrarAyuda" aria-label="Cerrar">&times;</button>
        </div>
        <div class="modal-body">
            <p>Seleccioná una pregunta para ver la respuesta paso a paso.</p>

            <details open>
                <summary><i class="bi bi-box-seam"></i> ¿Cómo se registra un producto?</summary>
                <div class="det-body">
                    <ol>
                        <li>En el panel principal, hacé clic en <strong>Ir a Productos</strong>.</li>
                        <li>Presioná el botón de arriba a la derecha para dar de alta un producto nuevo.</li>
                        <li>Completá el formulario:
                            <ul>
                                <li><strong>Nombre del producto</strong></li>
                                <li><strong>Tipo de zapatilla</strong></li>
                                <li><strong>Descripción</strong></li>
                                <li><strong>Precio</strong></li>
                                <li><strong>Talle, Color, Marca y Categoría</strong> (se eligen de las listas desplegables)</li>
                            </ul>
                        </li>
                        <li>Presioná el botón de guardar. El producto aparecerá en el listado.</li>
                    </ol>
                    <div class="nota"><i class="bi bi-info-circle"></i> Si no encontrás una marca o categoría en las listas, primero cargala desde el módulo <strong>Configuraciones</strong>.</div>
                </div>
            </details>

            <details>
                <summary><i class="bi bi-people"></i> ¿Cómo se registra un cliente?</summary>
                <div class="det-body">
                    <ol>
                        <li>En el panel principal, hacé clic en <strong>Ir a Clientes</strong>.</li>
                        <li>Presioná el botón de arriba a la derecha para crear un cliente nuevo.</li>
                        <li>Completá los datos:
                            <ul>
                                <li><strong>Nombre</strong> y <strong>Apellido</strong></li>
                                <li><strong>DNI</strong></li>
                                <li><strong>Teléfono</strong></li>
                                <li><strong>Email</strong></li>
                            </ul>
                        </li>
                        <li>Presioná el botón de guardar. El cliente aparecerá en el listado con su fecha de registro.</li>
                    </ol>
                    <div class="nota"><i class="bi bi-info-circle"></i> Para buscar un cliente ya registrado, usá el buscador del listado.</div>
                </div>
            </details>

            <details>
                <summary><i class="bi bi-cart-check"></i> ¿Cómo se realiza una venta?</summary>
                <div class="det-body">
                    <ol>
                        <li>En el panel principal, hacé clic en <strong>Ir a Ventas</strong> (o en <strong>Nueva venta</strong>).</li>
                        <li>Presioná el botón de arriba a la derecha para crear una <strong>Nueva Venta</strong>.</li>
                        <li>En <strong>Cliente</strong>, abrí el selector y elegí el cliente (debe estar registrado previamente).</li>
                        <li>Elegí el <strong>Método de Pago</strong>: Efectivo, Tarjeta Débito, Tarjeta Crédito o Transferencia.</li>
                        <li>En la tabla de productos, seleccioná cada <strong>Producto</strong> (se muestra su talle, stock y precio) e indicá la <strong>cantidad</strong>.</li>
                        <li>Si el producto tiene una promoción vigente, podés aplicarla en la columna <strong>Promoción</strong>.</li>
                        <li>Usá el botón <strong>Agregar</strong> para sumar más productos a la venta.</li>
                        <li>Revisá el total y presioná el botón de confirmar la venta.</li>
                    </ol>
                    <div class="nota aviso"><i class="bi bi-exclamation-triangle"></i> Podés ver las ventas canceladas al lado del botón Nueva Venta, y también buscar una compra que haya hecho un cliente.</div>
                </div>
            </details>

            <details>
                <summary><i class="bi bi-tags"></i> ¿Cómo se crea una promoción?</summary>
                <div class="det-body">
                    <ol>
                        <li>En el panel principal, hacé clic en <strong>Ir a Promociones</strong>.</li>
                        <li>Presioná el botón de arriba a la derecha para crear una <strong>Nueva Promoción</strong>.</li>
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
                        <li>Presioná el botón de guardar.</li>
                    </ol>
                    <div class="nota"><i class="bi bi-info-circle"></i> Desde el listado de promociones podés editarlas, desactivarlas o reactivarlas cuando quieras.</div>
                </div>
            </details>
        </div>
    </div>
</div>

<script>
// Reloj en vivo
const reloj = document.getElementById('reloj');
function tick() {
    reloj.textContent = new Date().toLocaleTimeString('es-AR', { hour12: false });
}
tick();
setInterval(tick, 1000);

// Modal de ayuda
const overlay = document.getElementById('overlayAyuda');
const abrir   = () => overlay.classList.add('abierto');
const cerrar  = () => overlay.classList.remove('abierto');

document.getElementById('abrirAyuda').addEventListener('click', abrir);
document.getElementById('cerrarAyuda').addEventListener('click', cerrar);
overlay.addEventListener('click', e => { if (e.target === overlay) cerrar(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrar(); });

// ===== Requisito: sesión con localStorage + nombre de usuario arriba a la derecha =====
// js/sesion.js guarda los datos de la sesión en localStorage (clave "gamastore_sesion").
// Acá los leemos para mostrar el nombre del usuario en la parte superior derecha.
document.addEventListener('DOMContentLoaded', function () {
    var sesion = window.Sesion ? Sesion.obtener() : null;
    if (!sesion) return;   // si no hay datos, queda el valor que mandó el servidor

    var nombre = (sesion.email || '').split('@')[0];

    var uNombre = document.getElementById('uNombre');
    var uPerfil = document.getElementById('uPerfil');
    var uAvatar = document.getElementById('uAvatar');

    if (uNombre && nombre) {
        uNombre.textContent = nombre;
        uNombre.title = sesion.email;
    }
    if (uPerfil && sesion.rol) {
        uPerfil.textContent = (sesion.rol === 'admin') ? 'Administrador' : sesion.rol.charAt(0).toUpperCase() + sesion.rol.slice(1);
    }
    if (uAvatar && nombre) {
        uAvatar.textContent = nombre.charAt(0).toUpperCase();
    }
});
</script>

</body>
</html>