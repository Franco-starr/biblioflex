<?php

function conectarDB() {
    // 1. Obtener directamente de las variables de entorno de Railway
    $host = getenv('DB_HOST');
    $usuario = getenv('DB_USER');
    $password = getenv('DB_PASSWORD') ?: getenv('DB_PASS');
    $base = getenv('DB_NAME');
    $port = (int)(getenv('DB_PORT') ?: 3306);

    // 2. Solo si estás en tu computadora local y existe un .env físico, lo usa como respaldo
    $envFile = __DIR__ . '/../../.env';
    if (!$host && file_exists($envFile)) {
        $env = parse_ini_file($envFile);
        $host = $env['DB_HOST'] ?? 'localhost';
        $usuario = $env['DB_USER'] ?? 'root';
        $password = $env['DB_PASSWORD'] ?? ($env['DB_PASS'] ?? '');
        $base = $env['DB_NAME'] ?? 'biblioflex';
        $port = (int)($env['DB_PORT'] ?? 3306);
    }

    // 3. Conexión a la base de datos
    $db = new mysqli($host, $usuario, $password, $base, $port);

    if ($db->connect_error) {
        die('Error de conexión: ' . $db->connect_error);
    }

    $db->set_charset('utf8');

    return $db;
}