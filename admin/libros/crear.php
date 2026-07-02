<?php 
require_once __DIR__ . '/../../includes/app.php';
$db = conectarDB();

// Inicializar variables vacías para persistir los datos en el formulario
$titulo = '';
$autor = '';
$editorial = '';
$anio_publicacion = '';
$isbn = '';
$categoria_id = '';
$stock = '';
$nombreImagen = 'ejemplo.png';
$estado = '1';

// Arreglo para acumular mensajes de error
$errores = [];

// 1. Consultar las categorías reales de la base de datos para el <select>
$query_cat = "SELECT id, nombre FROM categoria WHERE estado = 1";
$resultado_categorias = mysqli_query($db, $query_cat);



if($_SERVER['REQUEST_METHOD'] === 'POST'){

    // 1. Sanitizar y guardar los datos del formulario (Previene XSS básico)
    $titulo = $_POST['titulo'];
    $autor = $_POST['autor'];
    $editorial = $_POST['editorial'];
    $anio_publicacion = $_POST['anio_publicacion'];
    $isbn = $_POST['isbn'];
    $categoria_id = $_POST['categoria_id'];
    $stock = $_POST['stock'];
    $estado = isset($_POST['estado']) ? '1' : '0'; // Checkbox seguro

    // 3. Validaciones obligatorias en el servidor
    if(!$titulo) { 
        $errores[] = "Debes añadir un título."; 
    }
    if(!$autor) { 
        $errores[] = "Debes añadir un autor."; 
    }
    if(!$anio_publicacion) 
        { $errores[] = "Debes añadir el año de publicación."; 
    }
    if(!$categoria_id) { 
        $errores[] = "Selecciona una categoría válida."; 
    }
    if($stock === '') { 
        $errores[] = "El stock no puede estar vacío."; 
    }

   
    // 4. --- GUARDAR en BD ---
    if(empty($errores)) {


        // Crear el Query usando Sentencias Preparadas (Prepared Statements) por seguridad
        $query = "INSERT INTO libros (titulo, autor, editorial, anio_publicacion, isbn, categoria_id, stock, imagen, estado) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Preparar la sentencia
        $stmt = mysqli_prepare($db, $query);

        if($stmt) {
            // Ligar los parámetros (s = string, i = entero)
            // Ajusta el orden según las columnas de tu base de datos
            mysqli_stmt_bind_param($stmt, "sssisiiis", 
                $titulo, 
                $autor, 
                $editorial, 
                $anio_publicacion, 
                $isbn, 
                $categoria_id, 
                $stock, 
                $nombreImagen, 
                $estado
            );

            // Ejecutar la consulta
            $resultado = mysqli_stmt_execute($stmt);

            if($resultado) {
                // Redireccionar al usuario para evitar doble envío del formulario
                header('Location: ../index.php?resultado=1');
                exit; // Detener la ejecución de este script
            } else {
                $errores[] = "Error al guardar el libro en la base de datos.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}

incluirTemplate('header');
incluirTemplate('navbar');
?>

<main>
    <h1>Crear Libro</h1>

    <form method="POST" enctype="multipart/form-data">

        <fieldset>

            <legend>Información del libro</legend>

            <label for="titulo">
                <span>Título del Libro</span>
                <input type="text" id="titulo" name="titulo" maxlength="255" required placeholder="Ej: Clean Code">
            </label>

            <label for="autor">
                <span>Autor</span>
                <input type="text" id="autor" name="autor" maxlength="255" required placeholder="Ej: Robert C. Martin">
            </label>

            <label for="editorial">
                <span>Editorial</span>
                <input type="text" id="editorial" name="editorial" maxlength="150" placeholder="Ej: Prentice Hall">
            </label>

            <label for="anio_publicacion">
                <span>Año de Publicación</span>
                <input type="number" id="anio_publicacion" name="anio_publicacion" min="1901" max="2155" required placeholder="Ej: 2008">
            </label>

            <label for="isbn">
                <span>ISBN</span>
                <input type="text" id="isbn" name="isbn" maxlength="20" placeholder="Ej: 9780132350884">
            </label>

            <label for="categoria_id">
                <span>Categoría</span>
                <select id="categoria_id" name="categoria_id" required>
                    <option value="">Seleccione una categoría</option>
                    <?php while($categoria = mysqli_fetch_assoc($resultado_categorias)) : ?>
                        <option value="<?php echo $categoria['id']; ?>">
                            <?php echo $categoria['nombre']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </label>

            <label for="stock">
                <span>Stock disponible</span>
                <input type="number" id="stock" name="stock" min="0" required placeholder="Ej: 5">
            </label>

            <label for="imagen">
                <span>Imagen de portada</span>
                <input type="file" id="imagen" name="imagen" accept="image/*">
            </label>

            <label class="checkbox-label">
                <input type="checkbox" id="estado" name="estado" value="1" checked>
                <span>Libro Activo / Disponible para préstamo</span>
            </label>

        </fieldset>

        <button type="submit">Guardar Libro</button>

    </form>

    <a href="../index.php">Volver al menu</a>
</main>

<?php 
incluirTemplate('footer');
?>

