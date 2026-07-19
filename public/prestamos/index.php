<?php 
require_once __DIR__ . '/../../includes/app.php';
use App\Prestamo;

if (!estaLogueado()) {
    header('Location: /public/login.php');
    exit;
}

$prestamos = Prestamo::prestamosPorUsuario($_SESSION['usuario_id']);

incluirTemplate('header');
incluirTemplate('navbar');
?>

<main>
    <h1>Mis Préstamos</h1>

    <?php if (empty($prestamos)): ?>
        <p>No tenés préstamos registrados.</p>
    <?php else: ?>
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
                    <td>
                        <img src="/imagenes/<?php echo s($prestamo->imagen); ?>" alt="<?php echo s($prestamo->titulo); ?>" width="50">
                    </td>
                    <td><?php echo s($prestamo->titulo); ?></td>
                    <td><?php echo s($prestamo->fecha_prestamo); ?></td>
                    <td></td>
                    <td><?php echo s($prestamo->fecha_estimada); ?></td>
                    <td><?php echo $prestamo->fecha_devolucion ? s($prestamo->fecha_devolucion) : '-'; ?></td>
                    <td><?php echo s($prestamo->estado); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</main>

<?php 
incluirTemplate('footer');
?>
