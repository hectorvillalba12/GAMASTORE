<?php
// Mensajes que llegan por ?msg=...
$mensajes = [
    'ok'               => ['success', 'bi-check-circle',       'Contraseña actualizada correctamente'],
    'error'            => ['danger',  'bi-x-circle',          'Error al cambiar la contraseña'],
    'email_verificado' => ['success', 'bi-patch-check',       '<strong>¡Email verificado!</strong> Tu cuenta fue confirmada correctamente.'],
    'token_invalido'   => ['danger',  'bi-x-circle',          'El enlace de verificación no es válido o ya fue usado.'],
    'registro_ok'      => ['success', 'bi-person-check',      '<strong>¡Cuenta creada!</strong> Ya podés iniciar sesión.'],
    'sesion_requerida' => ['warning', 'bi-lock',              'Necesitás iniciar sesión para acceder.'],
    'acceso_denegado'  => ['danger',  'bi-shield-exclamation','No tenés permisos para acceder a esa sección.'],
    'error_login'      => ['danger',  'bi-exclamation-circle','Email o contraseña incorrectos.'],
    'usuario_inactivo' => ['warning', 'bi-person-x',          'Tu cuenta está inactiva. Contactá al administrador.'],
];
$msg = $_GET['msg'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gamastore | Ingresar</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root{
    --bg:#0b0713;
    --card:#14101f;
    --input:#0e0a18;
    --border:rgba(168,85,247,.28);
    --text:#ffffff;
    --muted:#a69bbd;
    --violet:#7c3aed;
    --pink:#c026d3;
    --lila:#d28bff;
}
*{box-sizing:border-box;margin:0;padding:0}

body{
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:12px;
    font-family:'Inter',system-ui,sans-serif;
    color:var(--text);
    background:
        radial-gradient(circle at 15% 10%, rgba(124,58,237,.25), transparent 45%),
        radial-gradient(circle at 90% 90%, rgba(192,38,211,.18), transparent 45%),
        var(--bg);
}

/* ===== Tarjeta ===== */
.login-card{
    width:100%;
    max-width:420px;
    background:linear-gradient(180deg,#171127,var(--card));
    border:1px solid var(--border);
    border-radius:20px;
    padding:24px 30px 20px;
    position:relative;
    box-shadow:0 0 60px rgba(124,58,237,.18), 0 25px 50px rgba(0,0,0,.5);
}
.login-card::before{
    content:"";
    position:absolute;
    top:0;left:22px;right:22px;
    height:3px;
    border-radius:0 0 4px 4px;
    background:linear-gradient(90deg,var(--violet),var(--pink),#6366f1);
}

/* ===== Logo ===== */
.logo{
    width:68px;height:68px;
    margin:0 auto 10px;
    border-radius:16px;
    background:#0a0712;
    border:1px solid var(--border);
    display:flex;align-items:center;justify-content:center;
    box-shadow:0 0 24px rgba(168,85,247,.45);
}
.logo svg{width:52px;height:auto;transform:rotate(-6deg)}

.titulo{
    text-align:center;
    font-size:26px;
    font-weight:800;
    letter-spacing:-.5px;
}
.titulo span{color:var(--lila)}
.subtitulo{
    text-align:center;
    color:var(--muted);
    font-size:14px;
    margin:4px 0 16px;
}

/* ===== Alertas ===== */
.alerta{
    display:flex;gap:10px;align-items:flex-start;
    padding:9px 12px;
    border-radius:12px;
    font-size:13.5px;
    line-height:1.35;
    margin-bottom:14px;
    border:1px solid;
}
.alerta i{font-size:17px;margin-top:1px}
.alerta.success{background:rgba(34,197,94,.1); border-color:rgba(34,197,94,.35); color:#86efac}
.alerta.danger {background:rgba(239,68,68,.1); border-color:rgba(239,68,68,.35); color:#fca5a5}
.alerta.warning{background:rgba(245,158,11,.1);border-color:rgba(245,158,11,.35);color:#fcd34d}

/* ===== Campos ===== */
.campo{margin-bottom:12px}
.campo-top{
    display:flex;justify-content:space-between;align-items:center;
    margin-bottom:6px;
}
.campo label{font-weight:600;font-size:14px}
.campo-top a{
    color:var(--lila);
    font-size:13.5px;
    text-decoration:none;
}
.campo-top a:hover{text-decoration:underline}

.input-wrap{position:relative}
.input-wrap > i.icono{
    position:absolute;left:15px;top:50%;
    transform:translateY(-50%);
    color:var(--lila);
    font-size:18px;
    pointer-events:none;
}
.input-wrap input{
    width:100%;
    height:44px;
    padding:0 46px 0 46px;
    background:var(--input);
    border:1px solid rgba(255,255,255,.08);
    border-radius:12px;
    color:var(--text);
    font-size:15px;
    font-family:inherit;
    outline:none;
    transition:.2s;
}
.input-wrap input::placeholder{color:#5f5675}
.input-wrap input:focus{
    border-color:var(--violet);
    box-shadow:0 0 0 3px rgba(124,58,237,.25);
}
.ojo{
    position:absolute;right:8px;top:50%;
    transform:translateY(-50%);
    background:none;border:none;
    color:var(--muted);
    font-size:19px;
    cursor:pointer;
    padding:8px;
}
.ojo:hover{color:#fff}

/* ===== Botón ===== */
.btn-ingresar{
    width:100%;
    height:46px;
    margin-top:4px;
    border:none;
    border-radius:12px;
    font-family:inherit;
    font-size:14px;
    font-weight:700;
    letter-spacing:.6px;
    text-transform:uppercase;
    color:#fff;
    cursor:pointer;
    background:linear-gradient(90deg,var(--violet),var(--pink));
    box-shadow:0 8px 24px rgba(124,58,237,.4);
    display:flex;align-items:center;justify-content:center;gap:10px;
    transition:.2s;
}
.btn-ingresar:hover{transform:translateY(-2px);box-shadow:0 12px 30px rgba(192,38,211,.45)}
.btn-ingresar:active{transform:translateY(0)}

/* ===== Crear cuenta ===== */
.separador{
    height:1px;
    background:rgba(255,255,255,.08);
    margin:16px 0 12px;
}
.crear{
    display:block;
    text-align:center;
    color:var(--muted);
    font-size:14px;
    text-decoration:none;
}
.crear b{color:var(--lila);font-weight:600}
.crear:hover b{text-decoration:underline}

/* ===== Responsive ===== */
@media(max-width:480px){
    .login-card{padding:22px 20px 18px}
}
@media(max-height:680px){
    .logo{width:54px;height:54px;margin-bottom:6px}
    .logo svg{width:42px}
    .titulo{font-size:22px}
    .subtitulo{margin:2px 0 10px;font-size:13px}
    .campo{margin-bottom:9px}
    .input-wrap input{height:40px}
    .btn-ingresar{height:42px}
    .separador{margin:12px 0 10px}
}
</style>
</head>

<body>

<div class="login-card">

    <!-- Logo: zapatilla -->
    <div class="logo">
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

    <h1 class="titulo">Gama<span>store</span></h1>
    <p class="subtitulo">Ingresá con tu cuenta para continuar</p>

    <!-- Mensajes -->
    <?php if ($msg !== '' && isset($mensajes[$msg])): ?>
        <?php [$tipo, $icono, $texto] = $mensajes[$msg]; ?>
        <div class="alerta <?= $tipo ?>">
            <i class="bi <?= $icono ?>"></i>
            <div><?= $texto ?></div>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?action=loginPost">

        <div class="campo">
            <div class="campo-top">
                <label for="email">Email</label>
            </div>
            <div class="input-wrap">
                <i class="bi bi-envelope icono"></i>
                <input type="text" id="email" name="email" placeholder="nombre@gamastore.com" autocomplete="username" autofocus>
            </div>
        </div>

        <div class="campo">
            <div class="campo-top">
                <label for="password">Contraseña</label>
                <a href="index.php?action=forgot">¿Olvidaste tu contraseña?</a>
            </div>
            <div class="input-wrap">
                <i class="bi bi-lock icono"></i>
                <input type="password" id="password" name="password" placeholder="••••••••••••" autocomplete="current-password">
                <button type="button" class="ojo" id="verPass" aria-label="Mostrar contraseña">
                    <i class="bi bi-eye" id="ojoIcono"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn-ingresar">
            Ingresar a Gamastore <i class="bi bi-arrow-right"></i>
        </button>

    </form>

    <div class="separador"></div>

    <a href="index.php?action=register" class="crear">
        <i class="bi bi-person-plus"></i> ¿No tenés cuenta? <b>Crear cuenta nueva</b>
    </a>

</div>

<script>
// Mostrar / ocultar contraseña
const pass  = document.getElementById('password');
const icono = document.getElementById('ojoIcono');
document.getElementById('verPass').addEventListener('click', () => {
    const oculto = pass.type === 'password';
    pass.type = oculto ? 'text' : 'password';
    icono.className = oculto ? 'bi bi-eye-slash' : 'bi bi-eye';
});
</script>

</body>
</html>