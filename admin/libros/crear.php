<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../includes/app.php';
estaAutenticado();
if (!esAdmin()) {
    header('Location: ' . base_url('/')); 
    exit;
}


use App\Libro;
use App\Categoria;
use App\ProcesadorImagen;

$libro = new Libro();

$categorias = Categoria::all();
$errores = [];

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $libro = new Libro($_POST['libro']);

    $errores = $libro->validar();

    /** Subida de archivos **/
    if(isset($_FILES['imagen'])) {
        if($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            try {
                $nombreImagen = ProcesadorImagen::procesar($_FILES['imagen']['tmp_name']);
                $libro->setImagen($nombreImagen);
            } catch (\RuntimeException $e) {
                $errores[] = $e->getMessage();
            }
        } elseif($_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
            $errores[] = 'Hubo un problema al subir la imagen. Inténtalo de nuevo.';
        }
    }

    if(!$libro->imagen) {
        $errores[] = "Debes subir una imagen del libro.";
    }

    if(empty($errores)) {
        $resultado = $libro->guardar();
        if($resultado) {
            header('Location: ' . base_url('/admin/libros?resultado=1'));
            exit;
        }
        $errores[] = 'No se pudo guardar el libro. Inténtalo de nuevo.';
    } elseif(isset($nombreImagen)) {
        @unlink(__DIR__ . '/../../imagenes/' . $nombreImagen);
        $libro->imagen = '';
    }
}

incluirTemplate('header');
?>

<main class="main-content">
    <h1>Crear Libro</h1>

    <a href="<?php echo base_url('/admin'); ?>" class="boton-azul">Volver</a>

    <?php if(!empty($errores)) : ?>
        <div class="errores">
            <?php foreach($errores as $error) : ?>
                <p><?php echo s($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="formulario" method="POST" action="<?php echo base_url('/admin/libros/crear'); ?>" enctype="multipart/form-data">
       <?php include '../../includes/templates/formulario_libro.php'; ?>
    
        <input type="submit" value="Crear Libro" class="boton-verde">
    </form>

</main>

<?php
incluirTemplate('footer');
?>
