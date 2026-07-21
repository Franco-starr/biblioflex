<?php
require_once __DIR__ . '/../../includes/app.php';
estaAutenticado();
if (!esAdmin()) {
    header('Location: /public/index.php'); 
    exit;
}

use App\Libro;

$libros = Libro::allConCategoria();

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

<main class="main-content">
<h1>Panel de Administración</h1>

<?php
    $mensaje = mostrarNotificacion( intval($resultado) );
    if($mensaje) { ?>
    <p class="alerta exito"><?php echo s($mensaje); ?></p>
<?php } ?>

<a href="/admin/index.php">Volver</a>

<a href="./crear.php">Agregar un Libro</a>


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
            <td><?php echo s($libro->id); ?></td>
            <td><?php echo s($libro->titulo); ?></td>
            <td><?php echo s($libro->autor); ?></td>
            <td><?php echo s($libro->editorial); ?></td>
            <td><?php echo s($libro->anio_publicacion); ?></td>
            <td><?php echo s($libro->isbn); ?></td>
            <td><?php echo s($libro->categoria_nombre); ?></td>
            <td><?php echo s($libro->stock); ?></td>
            <td> <img src="/imagenes/<?php echo s($libro->imagen); ?>" alt="Imagen del libro" width="100"> </td>
            <td>
                <a href="/admin/libros/editar.php?id=<?php echo s($libro->id); ?>">Editar</a>
                <form method="POST" class="w-100">
                    <input type="hidden" name="id" value="<?php echo s($libro->id); ?>">
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
