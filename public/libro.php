<?php 

require_once __DIR__ . '/../includes/app.php';

use App\Libro;

//validar el id
$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if (!$id) header('Location: ./index.php');

$libro = Libro::findConCategoria($id);
if (!$libro) header('Location: ./index.php');

incluirTemplate('header');
incluirTemplate('navbar');

?>

<main>

<h1> <?php echo s($libro->titulo); ?></h1>

<div class="contenido-libro">
    <img src="/imagenes/<?php echo $libro->imagen; ?>" alt="Imagen de <?php echo $libro->titulo; ?>">

    <div class="informacion">
        <p class="autor">Autor: <?php echo $libro->autor; ?></p>
        <p class="categoria">Categoría: <span><?php echo $libro->categoria_nombre; ?></span></p>
        <p class="editorial">Editorial: <?php echo $libro->editorial; ?></p>
        <p class="descripcion"><?php echo $libro->estado; ?></p>
    </div>
</div>

</main>

<?php 
incluirTemplate('footer');
?>