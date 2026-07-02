<?php
require_once __DIR__ . '/../includes/app.php';

incluirTemplate('header');
incluirTemplate('navbar');
estaAutenticado()
?>

<main>
<h1>Panel de Administración</h1>

<a href="./libros/crear.php">Agregar un  Libro</a>
<a href="./categorias/crear.php">Agregar una Categoría</a>

<h2>Aca van los libros </h2>
<table>
    <thead>
        <tr>
            <th></th>ID</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Editorial</th>
            <th>Año de Publicación</th>
            <th>ISBN</th>
            <th>Categoría</th>
            <th>Stock</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td></td>
        </tr>
    </tbody>
</table>
</main>

<?php 
incluirTemplate('footer');
?>