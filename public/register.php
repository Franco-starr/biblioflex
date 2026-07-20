<?php

require_once __DIR__ . '/../includes/app.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = conectarDB();

    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $confirmar = trim($_POST['confirmar_password']);

    // Validaciones
    if (!$nombre) $errores[] = 'El nombre es obligatorio';
    if (!$apellido) $errores[] = 'El apellido es obligatorio';
    if (!$usuario) $errores[] = 'El usuario es obligatorio';
    if (!$password) $errores[] = 'La contraseña es obligatoria';
    if ($password !== $confirmar) $errores[] = 'Las contraseñas no coinciden';

    

    // Verificar usuario duplicado
    $stmt = $db->prepare("SELECT id FROM usuario WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows > 0) {
        $errores[] = 'El usuario ya existe';
    }

    if (empty($errores)) {
        //password hash?
        
        $query = "INSERT INTO usuario (nombre, apellido, usuario, password, permiso)
                VALUES (?, ?, ?, ?, 2)";
        // 2. Preparar el statement
        $stmt = $db->prepare($query);
        $contrasenaHash = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bind_param("ssss", $nombre, $apellido, $usuario, $contrasenaHash); // 4. Vincular los parámetros (s = string)
        $resultado = $stmt->execute(); //ejecutar consultar

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
        <p><?php echo s($error); ?></p>
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