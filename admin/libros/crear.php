<?php

require_once __DIR__ . '/../../includes/app.php';
estaAutenticado();

use App\Libro;
use App\Categoria;
use Intervention\Image\ImageManagerStatic as Image;

$libro = new Libro();

$categorias = Categoria::all();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $libro = new Libro($_POST['libro']);

    /** Subida de archivos **/
    if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $carpetaImagenes = __DIR__ . '/../../imagenes/';
        if( !is_dir($carpetaImagenes) ) {
            mkdir($carpetaImagenes, 0755, true);
        }

        $nombreImagen = md5( uniqid( rand(), true ) ) . ".jpg";
        $libro->setImagen($nombreImagen);

        Image::configure(['driver' => 'gd']);
        $image = Image::make($_FILES['imagen']['tmp_name']);
        $image->fit(800, 600);
        $image->save($carpetaImagenes . $nombreImagen);
    }

    $errores = $libro->validar();

    if(empty($errores)) {
        if(!$libro->imagen) {
            $errores[] = "Debes subir una imagen del libro.";
        }
    }

    if(empty($errores)) {
        $resultado = $libro->guardar();
        if($resultado) {
            header('Location: ./index.php?resultado=1');
            exit;
        }
    }
}

incluirTemplate('header');
incluirTemplate('navbar');
?>

<main>
    <h1>Crear Libro</h1>

    <a href="/admin/index.php">Volver</a>

    <?php if(!empty($errores)) : ?>
        <div class="errores">
            <?php foreach($errores as $error) : ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="formulario" method="POST" action="/admin/libros/crear.php" enctype="multipart/form-data">
       <?php include '../../includes/templates/formulario_libro.php'; ?>
    
        <input type="submit" value="Crear Libro" class="boton boton-verde">
    </form>

</main>

<?php
incluirTemplate('footer');
?>
