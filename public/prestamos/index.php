<?php 
require_once __DIR__ . '/../../includes/app.php';
use App\Prestamo;

if (!estaLogueado()) {
    header('Location: /public/login.php');
    exit;
}

$prestamos = Prestamo::prestamosPorUsuario($_SESSION['usuario_id']);

incluirTemplate('header');
?>

<main class="main-content">
    <div class="contenedor-centrado">
        <h1>Mis Préstamos</h1>

        <?php if (empty($prestamos)): ?>
            <p class="sin-resultados">No tenés préstamos registrados.</p>
        <?php else: ?>
            <div class="listado-datos">
                <table>
                <thead>
                    <tr>
                        <th>Libro</th>
                        <th>Fecha Préstamo</th>
                        <th>Fecha Estimada Devolución</th>
                        <th>Fecha Devolución</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($prestamos as $prestamo): ?>
                    <tr>
                        <td data-label="Libro">
                            <img src="/imagenes/<?php echo s($prestamo->imagen); ?>" alt="<?php echo s($prestamo->titulo); ?>" width="50">
                            <?php echo s($prestamo->titulo); ?>
                        </td>
                        <td data-label="Fecha Préstamo"><?php echo s($prestamo->fecha_prestamo); ?></td>
                        <td data-label="Fecha Estimada"><?php echo s($prestamo->fecha_estimada); ?></td>
                        <td data-label="Devolución"><?php echo $prestamo->fecha_devolucion ? s($prestamo->fecha_devolucion) : '-'; ?></td>
                        <td data-label="Estado"><?php echo s($prestamo->estado); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php 
incluirTemplate('footer');
?>
