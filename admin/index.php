<?php
require_once __DIR__ . '/../includes/app.php';

$db = conectarDB();
$query = "SELECT l.*, c.nombre AS categoria_nombre 
          FROM libros l 
          INNER JOIN categoria c 
          ON l.categoria_id = c.id";
$resultadoConsulta = mysqli_query($db, $query);

incluirTemplate('header');
incluirTemplate('navbar');
estaAutenticado();
?>

<main>
<h1>Panel de Administración</h1>

<a href="./libros/crear.php">Agregar un  Libro</a>
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
        <!--Mostrar los resultados-->
        <?php while($libro = mysqli_fetch_assoc($resultadoConsulta)): ?>
        <tr>
            <td><?php echo $libro['id']; ?></td>
            <td><?php echo $libro['titulo']; ?></td>
            <td><?php echo $libro['autor']; ?></td>
            <td><?php echo $libro['editorial']; ?></td>
            <td><?php echo $libro['anio_publicacion']; ?></td>
            <td><?php echo $libro['isbn']; ?></td>
            <td><?php echo $libro['categoria_nombre']; ?></td> <!-- Nota sobre esto abajo -->
            <td><?php echo $libro['stock']; ?></td>
            <td><img src="<?php echo $libro['imagen']; ?>" alt="<?php echo $libro['titulo']; ?>" width="50"></td> <!--Hacer que la imagen funcione bien-->
            <td>
                <a href="./libros/editar.php?id=<?php echo $libro['id']; ?>">Editar</a>
                <a href="./libros/eliminar.php?id=<?php echo $libro['id']; ?>">Eliminar</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
</main>

<?php 
incluirTemplate('footer');
?>