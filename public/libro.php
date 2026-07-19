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

<?php if(isset($_GET['exito']) && $_GET['exito'] == 1): ?>
    <p class="alerta exito">Préstamo solicitado correctamente.</p>
<?php endif; ?>

<?php if(isset($_GET['error'])): ?>
    <?php $errores = urldecode($_GET['error']); ?>
    <?php $listaErrores = explode('|', $errores); ?>
    <div class="alerta error">
        <?php foreach($listaErrores as $error): ?>
            <p><?php echo s($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="contenido-libro">
    <img src="/imagenes/<?php echo s($libro->imagen); ?>" alt="Imagen de <?php echo s($libro->titulo); ?>">

    <div class="informacion">
        <p class="autor">Autor: <?php echo s($libro->autor); ?></p>
        <p class="categoria">Categoría: <span><?php echo s($libro->categoria_nombre); ?></span></p>
        <p class="editorial">Editorial: <?php echo s($libro->editorial); ?></p>
        <p class="descripcion"><?php echo s($libro->estado); ?></p>
        <p class="descripcion">Stock del libro: <?php echo s($libro->stock); ?></p>
    </div>
</div>

<div class="acciones-libro">
    <?php if ($libro->stock > 0): ?>
        <form action="./prestamos/crear.php" method="POST">
            <!-- Pasamos el ID del libro oculto -->
            <input type="hidden" name="libro_id" value="<?php echo s($libro->id); ?>">
            <p>Por libro solo podes pedir un prestamo</p>
            <button type="submit" class="boton-prestamo">Solicitar Préstamo</button>
        </form>
    <?php else: ?>
        <p class="error">Lo sentimos, no hay stock disponible actualmente.</p>
    <?php endif; ?>
</div>

</main>

<?php 
incluirTemplate('footer');
?>