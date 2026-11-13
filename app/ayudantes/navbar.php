<?php
// Barra superior compartida de las pantallas internas.
// El nombre de usuario se completa desde localStorage (clave "gamastore_sesion", guardada por js/sesion.js).
$__u      = $_SESSION['usuario'] ?? [];
$__email  = $__u['email'] ?? '';
$__rol    = $__u['rol'] ?? '';
$__perfil = ($__rol === 'admin') ? 'Administrador' : ucfirst($__rol);
$__nombre = explode('@', $__email)[0];
?>
<header class="topbar">
    <a href="index.php?action=dashboard" class="brand">
        <div class="brand-logo">
            <svg viewBox="0 0 124 80" xmlns="http://www.w3.org/2000/svg" aria-label="Zapatilla">
                <defs>
                    <linearGradient id="gzNav" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0" stop-color="#a855f7"/>
                        <stop offset="1" stop-color="#ec4899"/>
                    </linearGradient>
                </defs>
                <path d="M12 54 L12 28 Q12 21 20 20 L34 18 Q38 27 47 30 L58 33 Q66 41 83 43 Q106 45 114 51 L114 54 Z" fill="url(#gzNav)"/>
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
    </a>

    <div class="acciones">
        <a href="index.php?action=dashboard" class="btn btn-ghost btn-sm btn-panel">
            <i class="bi bi-grid"></i> <span>Panel</span>
        </a>

        <div class="user-chip">
            <div class="avatar" id="uAvatar"><?= strtoupper(mb_substr($__nombre ?: 'U', 0, 1)) ?></div>
            <div class="user-info">
                <b id="uNombre" title="<?= htmlspecialchars($__email) ?>"><?= htmlspecialchars($__nombre) ?></b>
                <small id="uPerfil"><?= htmlspecialchars($__perfil) ?></small>
            </div>
        </div>

        <a href="index.php?action=logout" class="btn-salir" title="Cerrar sesión" aria-label="Cerrar sesión">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>
</header>

<script>
// Requisito: sesión con localStorage + nombre de usuario arriba a la derecha
document.addEventListener('DOMContentLoaded', function () {
    var sesion = window.Sesion ? Sesion.obtener() : null;
    if (!sesion) return;

    var nombre = (sesion.email || '').split('@')[0];
    var uNombre = document.getElementById('uNombre');
    var uPerfil = document.getElementById('uPerfil');
    var uAvatar = document.getElementById('uAvatar');

    if (uNombre && nombre) { uNombre.textContent = nombre; uNombre.title = sesion.email; }
    if (uPerfil && sesion.rol) {
        uPerfil.textContent = (sesion.rol === 'admin') ? 'Administrador' : sesion.rol.charAt(0).toUpperCase() + sesion.rol.slice(1);
    }
    if (uAvatar && nombre) { uAvatar.textContent = nombre.charAt(0).toUpperCase(); }
});
</script>