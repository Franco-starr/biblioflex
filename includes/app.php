<?php 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/funciones.php';
require __DIR__ . '/config/database.php';
//require __DIR__ . '/../vendor/autoload.php';
