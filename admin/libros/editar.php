<?php

require_once __DIR__ . '/../../includes/app.php';
estaAutenticado();
if (!esAdmin()) {
    header('Location: ' . base_url('/')); 
    exit;
}


use App\Libro;
use App\Categoria;
use App\ProcesadorImagen;

$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('LOCATION: ' . base_url('/admin/libros'));
    exit;
}



$libro = Libro::find($id);
$categorias = Categoria::all();
$errores = Libro::getErrores();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    $libroOriginal = clone $libro;

    $args = $_POST['libro']; 
    $libro->sincronizar($args);

    $errores = $libro->validar();

    /** Subida de archivos **/
    if(isset($_FILES['imagen'])) {
        if($_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            try {
                $nuevaImagen = ProcesadorImagen::procesar($_FILES['imagen']['tmp_name']);
            } catch (\RuntimeException $e) {
                $errores[] = $e->getMessage();
            }
        } elseif($_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
            $errores[] = 'Hubo un problema al subir la imagen. Inténtalo de nuevo.';
        }
    }

    if(!empty($errores)) {
        // No se guarda nada: se conservan los datos y la portada anteriores
        if(isset($nuevaImagen)) {
            @unlink(__DIR__ . '/../../imagenes/' . $nuevaImagen);
        }
        $libro = $libroOriginal;
    } else {
        if(isset($nuevaImagen)) {
            $libro->setImagen($nuevaImagen); // Borra la portada anterior al reemplazarla
        }

        $resultado = $libro->guardar();
        if($resultado) {
            header('Location: ' . base_url('/admin/libros?resultado=2'));
            exit;
        }
        $errores[] = 'No se pudo guardar el libro. Inténtalo de nuevo.';
        $libro = $libroOriginal;
    }
}

incluirTemplate('header');
?>

<main class="main-content">
    <h1>Actualizar Libro</h1>

    <a href="<?php echo base_url('/admin/libros'); ?>" class="boton-azul">Volver</a>

    <?php if(!empty($errores)) : ?>
        <div class="errores">
            <?php foreach($errores as $error) : ?>
                <p><?php echo s($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="formulario" method="POST" action="<?php echo base_url('/admin/libros/editar?id=' . s($id)); ?>" enctype="multipart/form-data">
       <?php include '../../includes/templates/formulario_libro.php'; ?>
    
        <input type="submit" value="Actualizar Libro" class="boton-verde">
    </form>

</main>

<?php 
incluirTemplate('footer');
 ?>
