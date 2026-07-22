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
?>

<main class="main-content">
    <h1>Crear Categoría</h1>

    <a href="./index.php" class="boton-azul">Volver</a>

    <?php if(!empty($errores)) : ?>
        <div class="errores">
            <?php foreach($errores as $error) : ?>
                <p><?php echo s($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form class="formulario" method="POST" action="./crear.php">
        <?php include '../../includes/templates/formulario_categoria.php'; ?>
        <input type="submit" value="Crear Categoría" class="boton-verde">
    </form>

</main>

<?php
incluirTemplate('footer');
?>
