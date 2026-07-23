<?php

function conectarDB() {
    $envFile = __DIR__ . '/../../.env';
    $env = parse_ini_file($envFile);

    $host = $env['DB_HOST'];
    $usuario = $env['DB_USER'];
    $password = $env['DB_PASS'];
    $base = $env['DB_NAME'];

    $db = new mysqli($host, $usuario, $password, $base);

    if ($db->connect_error) {
        die('Error de conexión: ' . $db->connect_error);
    }

    $db->set_charset('utf8');

    return $db;
}
