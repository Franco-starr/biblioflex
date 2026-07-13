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
    $s = htmlspecialchars($html);
    return $s;
}

//validar tipo de contenido
function validarTipoContenido($tipo) {
    $tipos = ['libro', 'categoria'];
    return in_array($tipo, $tipos);
}