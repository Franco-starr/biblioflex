<?php
require_once __DIR__ . '/../../includes/app.php';
estaAutenticado();

use App\Libro;
use App\Categoria;

// Implementar un metodo para obtener libros
$libros = Libro::all();

$categorias = Categoria::all();

// Muestra mensaje condicional
$resultado = $_GET['resultado'] ?? null; 

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if($id){
        $tipo = $_POST['tipo'];
        if(validarTipoContenido($tipo)) {
               
            if($tipo === 'libro') {
                $libro = Libro::find($id);
                $libro->borrarImagen();
                $libro->eliminar();
                header('location: ./index.php?resultado=3');
                exit;
            }
        }

    }
}

incluirTemplate('header');
incluirTemplate('navbar');
?>

<main>
<h1>Panel de Administración</h1>

<?php
    $mensaje = mostrarNotificacion( intval($resultado) );
    if($mensaje) { ?>
    <p class="alerta exito"><?php echo s($mensaje); ?></p>
<?php } ?>

<a href="./crear.php">Agregar un Libro</a>
<a href="./categorias/crear.php">Agregar una Categoría</a>

<h2>Panel de administracion</h2>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Autor</th>
            <th>Editorial</th>
            <th>Año de Publicación</th>
            <th>ISBN</th>
            <th>Categoría</th>
            <th>Stock</th>
            <th>Imagen</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($libros as $libro): ?>
        <tr>
            <td><?php echo $libro->id; ?></td>
            <td><?php echo $libro->titulo; ?></td>
            <td><?php echo $libro->autor; ?></td>
            <td><?php echo $libro->editorial; ?></td>
            <td><?php echo $libro->anio_publicacion; ?></td>
            <td><?php echo $libro->isbn; ?></td>
            <td><?php foreach($categorias as $categoria): ?>
                <?php if($categoria->id === $libro->categoria_id): ?>
                    <?php echo $categoria->nombre; ?>
                <?php endif; ?>
            <?php endforeach; ?></td>
            <td><?php echo $libro->stock; ?></td>
            <td> <img src="/imagenes/<?php echo $libro->imagen; ?>" alt="Imagen del libro" width="100"> </td>
            <td>
                <a href="./editar.php?id=<?php echo $libro->id; ?>">Editar</a>
                <form method="POST" class="w-100">
                    <input type="hidden" name="id" value="<?php echo $libro->id; ?>">
                    <input type="hidden" name="tipo" value="libro">
                    <input type="submit" class="boton-rojo-block" value="Eliminar">
                </form>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table> <!-- Fin de la tabla -->



</main>

<?php
incluirTemplate('footer');
?>
