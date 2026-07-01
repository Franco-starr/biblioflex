<?php 
require_once __DIR__ . '/../../includes/app.php';

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
                    <option value="">-- Selecciona una categoría --</option>
                    <option value="1">Programación / Desarrollo</option>
                    <option value="2">Diseño de Software</option>
                    <option value="3">Bases de Datos</option>
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