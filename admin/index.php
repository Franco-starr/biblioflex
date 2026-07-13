<?php
require_once __DIR__ . '/../includes/app.php';
estaAutenticado();

use App\Libro;
use App\Categoria;

// Implementar un metodo para obtener libros
$libros = Libro::all();

$categorias = Categoria::all();

incluirTemplate('header');
incluirTemplate('navbar');
?>

<main>
<h1>Panel de Administración</h1>

<a href="./libros/crear.php">Agregar un Libro</a>
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
            <td><?php echo $libro->imagen; ?></td>
            
            <td>
                <a href="./libros/editar.php?id=<?php echo $libro->id; ?>">Editar</a>
                <form method="POST" class="w-100">
                    <input type="hidden" name="id" value="<?php echo $libro->id; ?>">
                    <input type="hidden" name="tipo" value="libro">
                    <input type="submit" class="boton-rojo-block" value="Eliminar">
                </form>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
</main>

<?php
incluirTemplate('footer');
?>
