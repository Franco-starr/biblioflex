<?php

require_once __DIR__ . '/../includes/app.php';

use App\Libro;

$libros = Libro::allConCategoria();

incluirTemplate('header');
incluirTemplate('navbar');
?>

<main>

    <h1>Lista de Nuestros Libros</h1>

   <div class="listado-libros">

    <?php foreach($libros as $libro): ?>
        <div class="card">
            <img src="/imagenes/<?php echo $libro->imagen; ?>" alt="Imagen de <?php echo $libro->titulo; ?>">

            <div class="contenido-card">
                <h3><?php echo $libro->titulo; ?></h3>
                <p class="autor"><?php echo $libro->autor; ?></p>
                <p class="categoria">Categoría: <span><?php echo $libro->categoria_nombre; ?></span></p>
                <p class="editorial"><?php echo $libro->editorial; ?></p>
                <a href="libro.php?id=<?php echo $libro->id; ?>" class="boton-azul-block">
                    Ver Detalles
                </a>
            </div>

        </div>
        <?php endforeach;?>

   </div>
   
</main>

<?php 
incluirTemplate('footer');
?>