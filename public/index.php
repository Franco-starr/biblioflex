<?php

require_once __DIR__ . '/../includes/app.php';

use App\Libro;

$termino = $_GET['busqueda'] ?? '';

if ($termino) {
    $libros = Libro::buscar($termino);
} else {
    $libros = Libro::allConCategoria();
}

incluirTemplate('header');
incluirTemplate('navbar');
?>

<main>

    <h1>Lista de Nuestros Libros</h1>

    <form method="GET">
        <label for="busqueda">Buscar libro:</label>
        <input type="text" placeholder="Buscar libro" name="busqueda" id="busqueda" value="<?php echo s($termino); ?>">
        <button type="submit">Buscar</button>
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