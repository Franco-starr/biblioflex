<?php 
require_once __DIR__ . '/../../includes/app.php';
use App\Prestamo;

if (!estaLogueado()) {
    header('Location: /public/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /public/index.php');
    exit;
}

$libro_id = $_POST['libro_id'] ?? null;

$libro_id = filter_var($libro_id, FILTER_VALIDATE_INT);
if (!$libro_id) {
    header('Location: /public/index.php');
    exit;
}

$prestamo = new Prestamo([
    'usuario_id' => $_SESSION['usuario_id'],
    'libro_id' => $libro_id
]);

$resultado = $prestamo->prestarLibro();

if ($resultado) {
    header("Location: /public/libro.php?id={$libro_id}&exito=1");
    exit;
} else {
    $errores = Prestamo::getErrores();
    $mensajeError = urlencode(implode('|', $errores));
    header("Location: /public/libro.php?id={$libro_id}&error={$mensajeError}");
    exit;
}
