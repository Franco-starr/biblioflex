<?php

require_once __DIR__ . '/../includes/app.php';

use App\Libro;
use App\Categoria;


$termino = $_GET['busqueda'] ?? '';
$categoria_id = $_GET['categoria_id'] ?? '';

if ($termino) {
    $libros = Libro::buscar($termino);
} elseif (!empty($categoria_id)) {
    $libros = Libro::filtrarPorCategoria($categoria_id);
}
else {
    $libros = Libro::allConCategoria();
}

// Necesitas traer las categorías para llenar el <select>
$categorias = Categoria::all();

incluirTemplate('header');
incluirTemplate('navbar');
?>

<main class="main-content">
    
    <h1>Lista de Nuestros Libros</h1>

    <form method="GET">
        <label for="busqueda">Buscar:</label>
        <input type="text" name="busqueda" value="<?php echo s($termino); ?>">

        <label for="categoria_id">Categoría:</label>
        <select name="categoria_id">
            <option value="">-- Todas las categorías --</option>
            <?php foreach($categorias as $categoria): ?>
                <option 
                    value="<?php echo s($categoria->id); ?>"
                    <?php echo $categoria_id == $categoria->id ? 'selected' : ''; ?>
                >
                    <?php echo s($categoria->nombre); ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit">Filtrar</button>
        <a href="index.php">Limpiar</a>
    </form>
    

   <div class="listado-libros">
    <!-- Cambiamos empty por !empty (si NO está vacío) -->
    <?php if (!empty($libros)): ?>
        <?php foreach($libros as $libro): ?>
            <div class="card">
                <img src="/imagenes/<?php echo s($libro->imagen); ?>" alt="Imagen de <?php echo s($libro->titulo); ?>">
                <div class="contenido-card">
                    <h3><?php echo s($libro->titulo); ?></h3>
                    <p class="autor"><?php echo s($libro->autor); ?></p>
                    <p class="categoria">Categoría: <span><?php echo s($libro->categoria_nombre); ?></span></p>
                    <p class="editorial"><?php echo s($libro->editorial); ?></p>
                    <a href="libro.php?id=<?php echo s($libro->id); ?>" class="boton-azul-block">
                        Ver Detalles
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
        <?php else: ?>
            <!-- Aquí va el mensaje si el array está vacío -->
            <p>No se encontraron resultados para: "<?php echo s($termino); ?>"</p>
        <?php endif; ?>
    </div>
   
</main>

<?php 
incluirTemplate('footer');
?>