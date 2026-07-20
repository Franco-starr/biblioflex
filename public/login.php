<?php 

require_once __DIR__ . '/../includes/app.php';

$errores = [];

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ){
    $db = conectarDB();
    $usuario = $_POST['usuario']; // 
    $password = $_POST['password']; //
    // Validaciones
    if (!$usuario) {
        $errores[] = 'El usuario es obligatorio';
    }
    if (!$password) {
        $errores[] = 'El password es obligatorio';
    } 
    if( empty($errores) ) {
        $query = "SELECT usuario.*, permisos.nombre_permiso
          FROM usuario
          INNER JOIN permisos ON usuario.permiso = permisos.id
          WHERE usuario.usuario = ?";
        $stmt = $db->prepare($query);
        $stmt->bind_param("s", $usuario);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if($resultado->num_rows) {
            $usuarioDB = mysqli_fetch_assoc($resultado);
            if(password_verify($password, $usuarioDB['password'])) {
                session_regenerate_id(true);
                $_SESSION['usuario_id'] = $usuarioDB['id'];
                $_SESSION['usuario'] = $usuarioDB['usuario'];
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

<main>

    <h1>Inicia Sesion</h1>

    <form  method="POST">

        <fieldset>

            <legend>usuario y password</legend>
            
            <label for="usuario">Usuario</label>
            <input id="usuario" name="usuario" placeholder="Tu Email" value="<?php echo s($usuario ?? ''); ?>">

                
            <label for="password">Password</label>
            <input id="password" name="password" type="password" placeholder="Tu password">
        
            <input type="submit" value="iniciar sesion" class="">
        </fieldset>

    </form>

</main>

<?php 
incluirTemplate('footer');
?>
