<?php
$error = $_GET['error'] ?? '';
$token = $_GET['token'] ?? '';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Restablecer contraseña | Gamastore</title>
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
    --ok:#86efac;
    --bad:#fca5a5;
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
.card{
    width:100%;
    max-width:420px;
    background:linear-gradient(180deg,#171127,var(--card));
    border:1px solid var(--border);
    border-radius:20px;
    padding:24px 30px 20px;
    position:relative;
    box-shadow:0 0 60px rgba(124,58,237,.18), 0 25px 50px rgba(0,0,0,.5);
}
.card::before{
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
    font-size:25px;
    font-weight:800;
    letter-spacing:-.5px;
}
.titulo span{color:var(--lila)}
.subtitulo{
    text-align:center;
    color:var(--muted);
    font-size:14px;
    line-height:1.45;
    margin:6px 0 18px;
}

/* ===== Alerta ===== */
.alerta{
    display:flex;gap:10px;align-items:flex-start;
    padding:10px 12px;
    border-radius:12px;
    font-size:13.5px;
    line-height:1.35;
    margin-bottom:16px;
    border:1px solid rgba(239,68,68,.35);
    background:rgba(239,68,68,.1);
    color:var(--bad);
}
.alerta i{font-size:17px;margin-top:1px}

/* ===== Campo ===== */
.campo{margin-bottom:14px}
.campo label{
    display:block;
    font-weight:600;
    font-size:14px;
    margin-bottom:6px;
}
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
    height:46px;
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
.input-wrap input.valido{border-color:rgba(34,197,94,.55)}
.input-wrap input.invalido{border-color:rgba(239,68,68,.6)}

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

.feedback{
    display:none;
    font-size:12.5px;
    margin-top:6px;
    line-height:1.3;
}
.feedback.ok{color:var(--ok)}
.feedback.bad{color:var(--bad)}

/* ===== Botón ===== */
.btn-principal{
    width:100%;
    height:46px;
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
.btn-principal:hover{transform:translateY(-2px);box-shadow:0 12px 30px rgba(192,38,211,.45)}
.btn-principal:active{transform:translateY(0)}

/* ===== Volver ===== */
.separador{
    height:1px;
    background:rgba(255,255,255,.08);
    margin:18px 0 12px;
}
.volver{
    display:block;
    text-align:center;
    color:var(--muted);
    font-size:14px;
    text-decoration:none;
}
.volver b{color:var(--lila);font-weight:600}
.volver:hover b{text-decoration:underline}

@media(max-width:480px){
    .card{padding:22px 20px 18px}
}
</style>
</head>

<body>

<div class="card">

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

    <h1 class="titulo">Nueva <span>contraseña</span></h1>
    <p class="subtitulo">Elegí una contraseña segura para tu cuenta.</p>

    <!-- Error -->
    <?php if ($error === 'password_debil'): ?>
        <div class="alerta">
            <i class="bi bi-exclamation-triangle"></i>
            <div>La contraseña debe tener mínimo 8 caracteres, una mayúscula y un carácter especial.</div>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?action=resetPassword" id="formReset">
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">

        <div class="campo">
            <label for="password">Nueva contraseña</label>
            <div class="input-wrap">
                <i class="bi bi-key icono"></i>
                <input type="password" id="password" name="password" required minlength="8"
                       placeholder="Mín. 8 caracteres, 1 mayúscula y 1 especial"
                       autocomplete="new-password" autofocus>
                <button type="button" class="ojo" id="verPass" aria-label="Mostrar contraseña">
                    <i class="bi bi-eye" id="ojoIcono"></i>
                </button>
            </div>
            <div id="feedback_reglas" class="feedback"></div>
        </div>

        <button type="submit" class="btn-principal" id="btnReset">
            <i class="bi bi-check-circle"></i> Cambiar contraseña
        </button>

    </form>

    <div class="separador"></div>

    <a href="index.php?action=login" class="volver">
        <i class="bi bi-arrow-left"></i> ¿Cambiaste de idea? <b>Volver al login</b>
    </a>

</div>

<script>
const regexPassword  = /^(?=.*[A-Z])(?=.*[!@#$%^&*(),.?":{}|<>_\-+=~`\[\];'/\\]).{8,}$/;
const pass           = document.getElementById('password');
const feedbackReglas = document.getElementById('feedback_reglas');
const icono          = document.getElementById('ojoIcono');

// Mostrar / ocultar contraseña
document.getElementById('verPass').addEventListener('click', () => {
    const oculto = pass.type === 'password';
    pass.type = oculto ? 'text' : 'password';
    icono.className = oculto ? 'bi bi-eye-slash' : 'bi bi-eye';
});

function mostrar(ok, texto) {
    feedbackReglas.style.display = 'block';
    feedbackReglas.className = 'feedback ' + (ok ? 'ok' : 'bad');
    feedbackReglas.innerHTML = '<i class="bi ' + (ok ? 'bi-check-circle-fill' : 'bi-x-circle-fill') + '"></i> ' + texto;
}

function validarReglasPassword() {
    const p1 = pass.value;

    if (p1.length === 0) {
        feedbackReglas.style.display = 'none';
        pass.classList.remove('valido', 'invalido');
        return true;
    }

    if (regexPassword.test(p1)) {
        pass.classList.remove('invalido');
        pass.classList.add('valido');
        mostrar(true, 'Contraseña válida');
        return true;
    }

    pass.classList.remove('valido');
    pass.classList.add('invalido');
    mostrar(false, 'Debe tener mínimo 8 caracteres, una mayúscula y un carácter especial');
    return false;
}

pass.addEventListener('input', validarReglasPassword);

document.getElementById('formReset').addEventListener('submit', function (e) {
    if (!validarReglasPassword()) {
        e.preventDefault();
    }
});
</script>

</body>
</html>