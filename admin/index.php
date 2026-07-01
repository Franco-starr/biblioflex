<?php
require_once __DIR__ . '/../includes/app.php';

incluirTemplate('header');
incluirTemplate('navbar');
estaAutenticado()
?>

<main>
<h1>Panel de Administración</h1>

<a href="./libros/crear.php">Agregar un  Libro</a>
<a href="#">Agregar un Usuario</a>

<table>
    <h2>Aca van los libros </h2>
</table>
</main>

<?php 
incluirTemplate('footer');
?>