<?php
require_once __DIR__ . '/../includes/app.php';
estaAutenticado();

incluirTemplate('header');
incluirTemplate('navbar');
?>

<main>
    <h1>Panel de Administración</h1>

    <div class="admin-dashboard">
        <a href="/admin/libros/index.php" class="boton boton-azul">Libros</a>
        <a href="/admin/categoria/index.php" class="boton boton-azul">Categorías</a>
        <a href="/admin/prestamos/index.php" class="boton boton-azul">Préstamos</a>
        <a href="/admin/usuario/index.php" class="boton boton-azul">Usuarios</a>
    </div>
</main>

<?php
incluirTemplate('footer');
?>
