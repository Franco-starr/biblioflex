<?php 

function incluirTemplate($nombre) {
    include __DIR__ . "/templates/{$nombre}.php";
}

// Ruta base de la app: '' si corre en la raiz del dominio (biblioflex.test,
// InfinityFree) o '/subcarpeta' si corre debajo (localhost/biblioflex).
function base_url($ruta = '') {
    static $base = null;
    if ($base === null) {
        $s = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
        $base = preg_match('~^(.*?)/(public|admin)/~', $s, $m) ? rtrim($m[1], '/') : '';
    }
    return $base . $ruta;
}


function estaAutenticado() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if(!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
        header('Location: ' . base_url('/login'));
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
    $s = htmlspecialchars($html, ENT_QUOTES, 'UTF-8');
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
