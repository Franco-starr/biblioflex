<?php 

require_once __DIR__ . '/../includes/app.php';

$errores = [];

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
    $db = conectarDB();
    $email = $_POST['email'];
    $password = $_POST['password'];
    // Validaciones
    if (!$email) {
        $errores[] = 'El email es obligatorio';
    }
    if (!$password) {
        $errores[] = 'El password es obligatorio';
    } 
    if( empty($errores) ) {
        $query = "SELECT usuario.*, permisos.nombre_permiso
          FROM usuario
          INNER JOIN permisos ON usuario.permiso = permisos.id
          WHERE usuario.email = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if($resultado->num_rows) {
            $usuarioDB = mysqli_fetch_assoc($resultado);
            if(password_verify($password, $usuarioDB['password'])) {
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuarioDB['id'];
                $_SESSION['usuario'] = $usuarioDB['email'];
                $_SESSION['login'] = true;
                $_SESSION['permiso'] = $usuarioDB['nombre_permiso'];

                header('Location: ./index.php');
                exit;
            } else {
                $errores[] = 'Credenciales incorrectas';
            }
        } else {
            $errores[] = 'Credenciales incorrectas';
        }
    }
    
}



   


?>

<?php incluirTemplate('header'); ?>

<main class="main-content">
    <div class="contenedor-centrado">

        <div class="login-card">
            <div class="login-header">
                <span class="login-icono">🔑</span>
                <h1>Iniciar Sesión</h1>
                <p class="login-subtitulo">Accedé a tu cuenta de Biblioflex</p>
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

                <button type="submit" class="boton-submit">Iniciar Sesión</button>
            </form>

            <p class="link-secundario">¿No tenés cuenta? <a href="./register.php">Registrate</a></p>
        </div>

    </div>
</main>

<?php 
incluirTemplate('footer');
?>
