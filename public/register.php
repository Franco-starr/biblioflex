<?php

require_once __DIR__ . '/../includes/app.php';

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = conectarDB();

    $nombre = trim($_POST['nombre']);
    $apellido = trim($_POST['apellido']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmar = trim($_POST['confirmar_password']);

    // Validaciones
    if (!$nombre) $errores[] = 'El nombre es obligatorio';
    if (!$apellido) $errores[] = 'El apellido es obligatorio';
    if (!$email) $errores[] = 'El email es obligatorio';
    if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) $errores[] = 'El email no es válido';
    if (!$password) $errores[] = 'La contraseña es obligatoria';
    if ($password !== $confirmar) $errores[] = 'Las contraseñas no coinciden';

    // Verificar email duplicado
    $stmt = $db->prepare("SELECT id FROM usuario WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $resultado = $stmt->get_result();
    if ($resultado->num_rows > 0) {
        $errores[] = 'El email ya está registrado';
    }

    if (empty($errores)) {
        $query = "INSERT INTO usuario (nombre, apellido, email, password, permiso)
                VALUES (?, ?, ?, ?, 2)";
        $stmt = $db->prepare($query);
        $contrasenaHash = password_hash($password, PASSWORD_BCRYPT);
        $stmt->bind_param("ssss", $nombre, $apellido, $email, $contrasenaHash);
        $resultado = $stmt->execute();

        if ($resultado) {
            header('Location: ./login.php');
            exit;
        }
    }
}
?>

<?php incluirTemplate('header'); ?>

<main class="main-content">
    <div class="contenedor-centrado">

        <div class="register-card">
            <div class="register-header">
                <span class="register-icono">📝</span>
                <h1>Crear Cuenta</h1>
                <p class="register-subtitulo">Registrate en Biblioflex</p>
            </div>

            <?php if (!empty($errores)) : ?>
                <div class="errores">
                    <?php foreach ($errores as $error) : ?>
                        <p><?php echo s($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="formulario">
                <div class="campo">
                    <label for="nombre">Nombre</label>
                    <input id="nombre" name="nombre" type="text"
                           placeholder="Ingresá tu nombre"
                           value="<?php echo s($nombre ?? ''); ?>">
                </div>

                <div class="campo">
                    <label for="apellido">Apellido</label>
                    <input id="apellido" name="apellido" type="text"
                           placeholder="Ingresá tu apellido"
                           value="<?php echo s($apellido ?? ''); ?>">
                </div>

                <div class="campo">
                    <label for="email">Email</label>
                    <input id="email" name="email" type="email"
                           placeholder="Ingresá tu email"
                           value="<?php echo s($email ?? ''); ?>">
                </div>

                <div class="campo">
                    <label for="password">Contraseña</label>
                    <input id="password" name="password" type="password"
                           placeholder="Ingresá tu contraseña">
                </div>

                <div class="campo">
                    <label for="confirmar_password">Confirmar Contraseña</label>
                    <input id="confirmar_password" name="confirmar_password" type="password"
                           placeholder="Repetí tu contraseña">
                </div>

                <button type="submit" class="boton-submit">Registrarse</button>
            </form>

            <p class="link-secundario">¿Ya tenés cuenta? <a href="./login.php">Iniciá sesión</a></p>
        </div>

    </div>
</main>

<?php incluirTemplate('footer'); ?>