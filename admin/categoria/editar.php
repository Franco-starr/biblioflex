<?php
require_once __DIR__ . '/../../includes/app.php';
estaAutenticado();
if (!esAdmin()) {
    header('Location: /public/index.php'); 
    exit;
}

use App\Categoria;

$id = $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);

if (!$id) {
    header('LOCATION: ./index.php');
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
            header('Location: ./index.php?resultado=2');
            exit;
        }
    }
}

incluirTemplate('header');
?>

<main class="main-content">
    <h1>Actualizar Categoría</h1>

    <a href="./index.php">Volver</a>

    <?php if(!empty($errores)) : ?>
        <div class="errores">
            <?php foreach($errores as $error) : ?>
                <p><?php echo s($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="formulario" method="POST" action="./editar.php?id=<?php echo s($id); ?>">
        <?php include '../../includes/templates/formulario_categoria.php'; ?>
        <input type="submit" value="Actualizar Categoría" class="boton boton-verde">
    </form>

</main>

<?php
incluirTemplate('footer');
?>
