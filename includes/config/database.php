<?php

function conectarDB() {
    $host = 'localhost';
    $usuario = 'root';
    $password = 'root';
    $base = 'biblioflex';

    $db = new mysqli($host, $usuario, $password, $base);

    if ($db->connect_error) {
        die('Error de conexión: ' . $db->connect_error);
    }

    $db->set_charset('utf8');

    return $db;
}
