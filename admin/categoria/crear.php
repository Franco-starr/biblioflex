<?php
require_once __DIR__ . '/../../includes/app.php';
estaAutenticado();
if (!esAdmin()) {
    header('Location: /public/index.php'); 
    exit;
}

use App\Categoria;

$categoria = new Categoria();

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $categoria = new Categoria($_POST['categoria']);

    $errores = $categoria->validar();

    if(empty($errores)) {
        $resultado = $categoria->guardar();
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
    <h1>Crear Categoría</h1>

    <a href="./index.php">Volver</a>

    <?php if(!empty($errores)) : ?>
        <div class="errores">
            <?php foreach($errores as $error) : ?>
                <p><?php echo $error; ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="formulario" method="POST" action="./crear.php">
        <?php include '../../includes/templates/formulario_categoria.php'; ?>
        <input type="submit" value="Crear Categoría" class="boton boton-verde">
    </form>

</main>

<?php
incluirTemplate('footer');
?>
