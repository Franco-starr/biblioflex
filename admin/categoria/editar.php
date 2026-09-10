<?php
require_once __DIR__ . '/../../includes/app.php';
estaAutenticado();
if (!esAdmin()) {
    header('Location: ' . base_url('/')); 
    exit;
}

use App\Categoria;

$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('LOCATION: ' . base_url('/admin/categoria'));
    exit;
}

$categoria = Categoria::find($id);
$errores = Categoria::getErrores();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $args = $_POST['categoria'];
    $categoria->sincronizar($args);

    $errores = $categoria->validar();

    if(empty($errores)) {
        $resultado = $categoria->guardar();
        if($resultado) {
            header('Location: ' . base_url('/admin/categoria?resultado=2'));
            exit;
        }
    }
}

incluirTemplate('header');
?>

<main class="main-content">
    <h1>Actualizar Categoría</h1>

    <a href="<?php echo base_url('/admin/categoria'); ?>" class="boton-azul">Volver</a>

    <?php if(!empty($errores)) : ?>
        <div class="errores">
            <?php foreach($errores as $error) : ?>
                <p><?php echo s($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="formulario" method="POST" action="<?php echo base_url('/admin/categoria/editar?id=' . s($id)); ?>">
        <?php include '../../includes/templates/formulario_categoria.php'; ?>
        <input type="submit" value="Actualizar Categoría" class="boton-verde">
    </form>

</main>

<?php
incluirTemplate('footer');
?>
