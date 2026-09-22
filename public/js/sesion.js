
(function () {
    'use strict';

    var CLAVE = 'gamastore_sesion';

    // ---------- 1. Funciones para manejar el localStorage ----------
    var Sesion = {
        guardar: function (datos) {
            try { localStorage.setItem(CLAVE, JSON.stringify(datos)); } catch (e) {}
        },
        obtener: function () {
            try {
                var texto = localStorage.getItem(CLAVE);
                return texto ? JSON.parse(texto) : null;
            } catch (e) { return null; }
        },
        cerrar: function () {
            try { localStorage.removeItem(CLAVE); } catch (e) {}
        }
    };
    window.Sesion = Sesion;

    // ---------- 2. Sincronizar con la sesión real del servidor ----------
    var datosServidor = window.GAMASTORE_USUARIO || null;

    if (datosServidor) {
        var anterior = Sesion.obtener();
        var mismoUsuario = anterior && anterior.inicio &&
                        String(anterior.id) === String(datosServidor.id);
        datosServidor.inicio = mismoUsuario ? anterior.inicio : new Date().toISOString();
        Sesion.guardar(datosServidor);
    } else {
        Sesion.cerrar();
    }

    // ---------- 3. Mostrar el nombre de usuario arriba a la derecha ----------
    function mostrarUsuario() {
        var sesion = Sesion.obtener();
        if (!sesion || document.getElementById('gamastore-usuario')) return;

        var nombre = (sesion.email || 'usuario').split('@')[0];

        var caja = document.createElement('div');
        caja.id = 'gamastore-usuario';
        caja.className = 'd-flex align-items-center gap-2 bg-dark text-white rounded-pill px-3 py-1 shadow small';
        caja.style.cssText = 'position:fixed;top:8px;right:16px;z-index:1080;';
        caja.title = (sesion.email || '') + (sesion.perfil ? ' - ' + sesion.perfil : '');

        var icono = document.createElement('i');
        icono.className = 'bi bi-person-circle';

        var spanNombre = document.createElement('span');
        spanNombre.className = 'fw-semibold';
        spanNombre.textContent = nombre;          // textContent evita inyección de HTML (XSS)

        caja.appendChild(icono);
        caja.appendChild(spanNombre);

        if (sesion.rol) {
            var spanRol = document.createElement('span');
            spanRol.className = 'badge text-bg-primary';
            spanRol.textContent = sesion.rol;
            caja.appendChild(spanRol);
        }

        // No mostrar al imprimir / exportar a PDF
        var estilo = document.createElement('style');
        estilo.textContent = '@media print { #gamastore-usuario { display: none !important; } }';
        document.head.appendChild(estilo);

        document.body.appendChild(caja);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', mostrarUsuario);
    } else {
        mostrarUsuario();
    }

    // ---------- 4. Cerrar sesión: limpiar localStorage al hacer click en "Cerrar sesión" ----------
    document.addEventListener('click', function (e) {
        var enlace = e.target && e.target.closest ? e.target.closest('a') : null;
        if (enlace && /action=logout/.test(enlace.getAttribute('href') || '')) {
            Sesion.cerrar();
        }
    });

    // ---------- 5. Si se cierra la sesión en otra pestaña, redirigir al login ----------
    window.addEventListener('storage', function (e) {
        if (e.key === CLAVE && e.newValue === null) {
            window.location.href = 'index.php?action=login';
        }
    });
})();