document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const btnToggle = document.getElementById('btnToggle');

    // 1. Clic en el botón hamburguesa (expande o contrae de forma fija)
    btnToggle.addEventListener('click', (e) => {
        e.stopPropagation();
        sidebar.classList.toggle('expanded');
    });

    // 2. Se expande automáticamente al acercar el mouse (Hover)
    sidebar.addEventListener('mouseenter', () => {
        sidebar.classList.add('expanded');
    });

    // 3. Se contrae automáticamente al alejar el mouse
    sidebar.addEventListener('mouseleave', () => {
        sidebar.classList.remove('expanded');
    });
});