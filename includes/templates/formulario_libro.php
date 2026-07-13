<fieldset>
    <legend>Información del Libro</legend>

    <label for="titulo">Título:</label>
    <input type="text" id="titulo" name="libro[titulo]" placeholder="Título del libro" value="<?php echo s($libro->titulo ?? ''); ?>">

    <label for="autor">Autor:</label>
    <input type="text" id="autor" name="libro[autor]" placeholder="Nombre del autor" value="<?php echo s($libro->autor ?? ''); ?>">

    <label for="editorial">Editorial:</label>
    <input type="text" id="editorial" name="libro[editorial]" placeholder="Editorial" value="<?php echo s($libro->editorial ?? ''); ?>">

    <label for="anio_publicacion">Año de Publicación:</label>
    <input type="number" id="anio_publicacion" name="libro[anio_publicacion]" placeholder="Ej: 2026" value="<?php echo s((string)($libro->anio_publicacion ?? '')); ?>">

    <label for="isbn">ISBN:</label>
    <input type="text" id="isbn" name="libro[isbn]" placeholder="ISBN-13" value="<?php echo s($libro->isbn ?? ''); ?>">

    <label for="categoria_id">Categoría:</label>
    <select name="libro[categoria_id]" id="categoria_id">
        <option selected value="">-- Seleccione --</option>
        <?php foreach($categorias as $categoria) : ?>
            <option 
                <?php echo ($libro->categoria_id == $categoria->id) ? 'selected' : ''; ?>
                value="<?php echo s($categoria->id); ?>">
                <?php echo s($categoria->nombre); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="stock">Stock:</label>
    <input type="number" id="stock" name="libro[stock]" min="0" value="<?php echo s((string)($libro->stock ?? '0')); ?>">

    <label for="imagen">Imagen:</label>
    <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png">

    <?php if($libro->imagen) : ?>
        <img src="/imagenes/<?php echo s($libro->imagen); ?>" class="imagen-small" style="width: 100px;">
    <?php endif; ?>

    <label for="estado">Estado:</label>
    <select name="libro[estado]" id="estado">
        <option value="1" <?php echo $libro->estado == 1 ? 'selected' : ''; ?>>Disponible</option>
        <option value="0" <?php echo $libro->estado == 0 ? 'selected' : ''; ?>>No disponible</option>
    </select>
</fieldset>