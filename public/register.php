<?php

require_once __DIR__ . '/../includes/app.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = conectarDB();

    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $usuario = $_POST['usuario'];
    $password = $_POST['password'];
    $confirmar = $_POST['confirmar_password'];

    // Validaciones
    if (!$nombre) $errores[] = 'El nombre es obligatorio';
    if (!$apellido) $errores[] = 'El apellido es obligatorio';
    if (!$usuario) $errores[] = 'El usuario es obligatorio';
    if (!$password) $errores[] = 'La contraseña es obligatoria';
    if ($password !== $confirmar) $errores[] = 'Las contraseñas no coinciden';

    

    // Verificar usuario duplicado
    $query = "SELECT id FROM usuario WHERE usuario = '$usuario'";
    $resultado = mysqli_query($db, $query);
    if ($resultado->num_rows > 0) {
        $errores[] = 'El usuario ya existe';
    }

    if (empty($errores)) {
        //password hash?
        $contrasenaHash = password_hash($password, PASSWORD_BCRYPT);
        $query = "INSERT INTO usuario (nombre, apellido, usuario, password, permiso)
                  VALUES ('$nombre', '$apellido', '$usuario', '$contrasenaHash', 2)";
        $resultado = mysqli_query($db, $query);

        if ($resultado) {
            header('Location: ./login.php');
            exit;
        }
    }
}
?>

<?php incluirTemplate('header'); ?>

<main>
    <h1>Crear Cuenta</h1>

    <?php foreach ($errores as $error) : ?>
        <p><?php echo $error; ?></p>
    <?php endforeach; ?>

    <form method="POST">
        <fieldset>
            <legend>Datos personales</legend>

            <label for="nombre">Nombre</label>
            <input id="nombre" name="nombre" placeholder="Tu nombre"
                   value="<?php echo s($nombre ?? '') ; ?>">

            <label for="apellido">Apellido</label>
            <input id="apellido" name="apellido" placeholder="Tu apellido"
                   value="<?php echo s($apellido ?? '') ; ?>">

            <label for="usuario">Usuario</label>
            <input id="usuario" name="usuario" placeholder="Elige un nombre de usuario"
                   value="<?php echo s($usuario ?? ''); ?>">

            <label for="password">Contraseña</label>
            <input id="password" name="password" type="password"
                   placeholder="Tu contraseña">

            <label for="confirmar_password">Confirmar Contraseña</label>
            <input id="confirmar_password" name="confirmar_password" type="password"
                   placeholder="Repite la contraseña">

            <input type="submit" value="Registrarse">
        </fieldset>
    </form>

    <p>¿Ya tenés cuenta? <a href="./login.php">Iniciá sesión</a></p>
</main>

<?php incluirTemplate('footer'); ?>