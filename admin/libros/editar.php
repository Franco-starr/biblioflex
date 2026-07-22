<?php

require_once __DIR__ . '/../../includes/app.php';
estaAutenticado();
if (!esAdmin()) {
    header('Location: /public/index.php'); 
    exit;
}


use App\Libro;
use App\Categoria;
use Intervention\Image\ImageManagerStatic as Image;
    
$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('LOCATION: ./index.php');
    exit;
}



$libro = Libro::find($id);
$categorias = Categoria::all();
$errores = Libro::getErrores();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    $args = $_POST['libro']; 
    $libro->sincronizar($args);

    $errores = $libro->validar();

    $carpetaImagenes = __DIR__ . '/../../imagenes/';

    if(isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        if( !is_dir($carpetaImagenes) ) {
            mkdir($carpetaImagenes, 0755, true);
        }

        $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";
        $libro->setImagen($nombreImagen);

        Image::configure(['driver' => 'gd']);
        $image = Image::make($_FILES['imagen']['tmp_name']);
        $image->fit(800, 600);
        $image->save($carpetaImagenes . $nombreImagen);
    }

    if(empty($errores)) {
        $resultado = $libro->guardar();
        if($resultado) {
            header('Location: ./index.php?resultado=2');
            exit;
        }
    }
}

incluirTemplate('header');
?>

<main class="main-content">
    <h1>Actualizar Libro</h1>

    <a href="./index.php" class="boton-azul">Volver</a>

    <?php if(!empty($errores)) : ?>
        <div class="errores">
            <?php foreach($errores as $error) : ?>
                <p><?php echo s($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="formulario" method="POST" action="/admin/libros/editar.php?id=<?php echo s($id); ?>" enctype="multipart/form-data">
       <?php include '../../includes/templates/formulario_libro.php'; ?>
    
        <input type="submit" value="Actualizar Libro" class="boton-verde">
    </form>

</main>

<?php 
incluirTemplate('footer');
 ?>
