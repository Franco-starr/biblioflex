<?php
require_once __DIR__ . '/../includes/app.php';
estaAutenticado();

if (!esAdmin()) {
    header('Location: /public/index.php'); // Acceso denegado: afuera al index
    exit;
}

incluirTemplate('header');
?>

<main class="main-content">
    <h1>Panel de Administración</h1>

    <div class="dashboard-grid">
        <a href="/admin/libros/index.php" class="panel-card">
            <span class="panel-icono">📚</span>
            <span class="panel-texto">Libros</span>
        </a>
        <a href="/admin/categoria/index.php" class="panel-card">
            <span class="panel-icono">🏷️</span>
            <span class="panel-texto">Categorías</span>
        </a>
        <a href="/admin/prestamos/index.php" class="panel-card">
            <span class="panel-icono">📖</span>
            <span class="panel-texto">Préstamos</span>
        </a>
        <a href="/admin/usuario/index.php" class="panel-card">
            <span class="panel-icono">👤</span>
            <span class="panel-texto">Usuarios</span>
        </a>
    </div>
</main>

<?php
incluirTemplate('footer');
?>
