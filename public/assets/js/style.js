document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const btnToggle = document.getElementById('btnToggle');
    const overlay = document.getElementById('drawerOverlay');
    const navLinks = sidebar.querySelectorAll('.nav-item');

    const MOBILE_BREAKPOINT = 768;

    function isMobile() {
        return window.innerWidth < MOBILE_BREAKPOINT;
    }

    // ── Mobile: hamburger abre/cierra el drawer ──
    btnToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        if (isMobile()) {
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('visible');
        } else {
            sidebar.classList.toggle('expanded');
        }
    });

    // ── Mobile: cerrar drawer al tocar el overlay ──
    overlay.addEventListener('click', () => {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('visible');
    });

    // ── Mobile: cerrar drawer al tocar un link ──
    navLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (isMobile()) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('visible');
            }
        });
    });

    // ── Desktop: hover expand / collapse ──
    sidebar.addEventListener('mouseenter', () => {
        if (!isMobile()) {
            sidebar.classList.add('expanded');
        }
    });

    sidebar.addEventListener('mouseleave', () => {
        if (!isMobile()) {
            sidebar.classList.remove('expanded');
        }
    });

    // ── Al cambiar tamaño de ventana, limpiar clases que no corresponden ──
    window.addEventListener('resize', () => {
        if (isMobile()) {
            sidebar.classList.remove('expanded');
        } else {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('visible');
        }
    });
});
