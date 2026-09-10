<?php
require_once __DIR__ . '/../../includes/app.php';
estaAutenticado();
if (!esAdmin()) {
    header('Location: ' . base_url('/')); 
    exit;
}

use App\Categoria;
use App\Libro;

$categorias = Categoria::all();

$resultado = $_GET['resultado'] ?? null;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if($id){
        $tipo = $_POST['tipo'];
        if(validarTipoContenido($tipo)) {

            if($tipo === 'categoria') {
                $categoria = Categoria::find($id);

                $sinCategoria = Categoria::where('nombre', 'Sin categoria');
                $sinCategoria = $sinCategoria[0] ?? null;

                if($sinCategoria && $categoria->id !== $sinCategoria->id) {
                    $libros = Libro::where('categoria_id', $id);
                    foreach($libros as $libro) {
                        $libro->categoria_id = $sinCategoria->id;
                        $libro->guardar();
                    }
                }

                $categoria->eliminar();
                header('location: ' . base_url('/admin/categoria?resultado=3'));
                exit;
            }
        }

    }
}

incluirTemplate('header');
?>

<main class="main-content">
<h1>Categorías</h1>

<a href="<?php echo base_url('/admin'); ?>" class="boton-azul">Volver</a>


<?php
    $mensaje = mostrarNotificacion( intval($resultado) );
    if($mensaje) { ?>
    <p class="alerta exito"><?php echo s($mensaje); ?></p>
<?php } ?>

<a href="<?php echo base_url('/admin/categoria/crear'); ?>" class="boton-azul">Agregar Categoría</a>

<h2>Listado de Categorías</h2>
<div class="listado-datos">
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Creado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($categorias as $categoria): ?>
        <tr>
            <td data-label="ID"><?php echo s($categoria->id); ?></td>
            <td data-label="Nombre"><?php echo s($categoria->nombre); ?></td>
            <td data-label="Creado"><?php echo s($categoria->creado_ed); ?></td>
            <td data-label="Acciones">
                <a href="<?php echo base_url('/admin/categoria/editar?id=' . s($categoria->id)); ?>" class="boton-azul">Editar</a>
                <form method="POST" class="w-100">
                    <input type="hidden" name="id" value="<?php echo s($categoria->id); ?>">
                    <input type="hidden" name="tipo" value="categoria">
                    <input type="submit" class="boton-rojo" value="Eliminar">
                </form>
            </td>
        </tr>
        <?php endforeach;?>
    </tbody>
</table>
</div>

</main>

<?php
incluirTemplate('footer');
?>
