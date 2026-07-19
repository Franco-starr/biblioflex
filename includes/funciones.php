<?php 

function incluirTemplate($nombre) {
    include __DIR__ . "/templates/{$nombre}.php";
}


function estaAutenticado() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if(!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header('Location: ../public/login.php');
        exit;
    }
}

function esAdmin() {
    return isset($_SESSION['permiso']) && $_SESSION['permiso'] === 'admin';
}

function estaLogueado() {
    return isset($_SESSION['login']) && $_SESSION['login'] === true;
}

//escapar el HTML
function s($html) : string {
    if(is_null($html)) return '';
    $s = htmlspecialchars($html);
    return $s;
}

//validar tipo de contenido
function validarTipoContenido($tipo) {
    $tipos = ['libro', 'categoria'];
    return in_array($tipo, $tipos);
}

//mostrar mensajes 
function mostrarNotificacion($codigo) {
    $mensaje = '';

    switch($codigo) {
        case 1:
            $mensaje = 'Creado correctamente';
            break;
        case 2:
            $mensaje = 'Actualizado correctamente';
            break;
        case 3:
            $mensaje = 'Eliminado correctamente';
            break;
        case 4:
            $mensaje = 'Libro devuelto correctamente';
            break;
        default:
            $mensaje = false;
    }

    return $mensaje;
}

function debuguear($variable) {
    echo '<pre>';
    var_dump($variable);
    echo '</pre>';
    exit;
}
