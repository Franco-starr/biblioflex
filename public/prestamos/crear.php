<?php 
require_once __DIR__ . '/../../includes/app.php';
use App\Prestamo;

if (!estaLogueado()) {
    header('Location: /login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /');
    exit;
}

$libro_id = $_POST['libro_id'] ?? null;

$libro_id = filter_var($libro_id, FILTER_VALIDATE_INT);
if (!$libro_id) {
    header('Location: /');
    exit;
}

$prestamo = new Prestamo([
    'usuario_id' => $_SESSION['usuario_id'],
    'libro_id' => $libro_id
]);

$resultado = $prestamo->prestarLibro();

if ($resultado) {
    header("Location: /libro?id={$libro_id}&exito=1");
    exit;
} else {
    $errores = Prestamo::getErrores();
    $mensajeError = urlencode(implode('|', $errores));
    header("Location: /libro?id={$libro_id}&error={$mensajeError}");
    exit;
}
