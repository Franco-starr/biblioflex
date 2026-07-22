<?php
require_once __DIR__ . '/../../includes/app.php';
estaAutenticado();

if(!esAdmin()) {
    header('Location: /public/index.php');
    exit;
}

use App\Prestamo;

$prestamos = Prestamo::allConDetalle();

$resultado = $_GET['resultado'] ?? null;

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $id = $_POST['id'] ?? null;
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if($id) {
        $prestamo = Prestamo::find($id);
        if($prestamo) {
            $resultado = $prestamo->devolverLibro();
            if($resultado) {
                header('location: ./index.php?resultado=4');
                exit;
            } else {
                $errores = Prestamo::getErrores();
                $mensajeError = urlencode(implode('|', $errores));
                header("location: ./index.php?error={$mensajeError}");
                exit;
            }
        }
    }
}

incluirTemplate('header');
incluirTemplate('navbar');
?>

<main class="main-content">
<h1>Administración de Préstamos</h1>

<?php
    $mensaje = mostrarNotificacion( intval($resultado) );
    if($mensaje) { ?>
    <p class="alerta exito"><?php echo s($mensaje); ?></p>
<?php } ?>

<?php if(isset($_GET['error'])): ?>
    <?php $listaErrores = explode('|', urldecode($_GET['error'])); ?>
    <div class="alerta error">
        <?php foreach($listaErrores as $error): ?>
            <p><?php echo s($error); ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<a href="/admin/index.php" class="boton-azul">Volver</a>

<div class="listado-datos">
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Libro</th>
            <th>Usuario</th>
            <th>Fecha Préstamo</th>
            <th>Fecha Estimada</th>
            <th>Fecha Devolución</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($prestamos as $prestamo): ?>
        <tr>
            <td><?php echo s($prestamo->id); ?></td>
            <td>
                <img src="/imagenes/<?php echo s($prestamo->imagen); ?>" alt="<?php echo s($prestamo->titulo); ?>" width="50">
                <?php echo s($prestamo->titulo); ?>
            </td>
            <td><?php echo s($prestamo->nombre) . ' ' . s($prestamo->apellido); ?></td>
            <td><?php echo s($prestamo->fecha_prestamo); ?></td>
            <td><?php echo s($prestamo->fecha_estimada); ?></td>
            <td><?php echo $prestamo->fecha_devolucion ? s($prestamo->fecha_devolucion) : '-'; ?></td>
            <td><?php echo s($prestamo->estado); ?></td>
            <td>
                <?php if($prestamo->estado === 'prestado'): ?>
                    <form method="POST">
                        <input type="hidden" name="id" value="<?php echo s($prestamo->id); ?>">
                        <input type="submit" value="Devolver" class="boton-verde" onclick="return confirm('¿Confirmar devolución?')">
                    </form>
                <?php else: ?>
                    <span>—</span>
                <?php endif; ?>
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
