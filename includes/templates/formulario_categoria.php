<fieldset>
    <legend>Información de Categoría</legend>

    <label for="nombre">Nombre:</label>
    <input type="text" id="nombre" name="categoria[nombre]" placeholder="Nombre de la categoría" value="<?php echo s($categoria->nombre ?? ''); ?>">
</fieldset>
