<?php

/**
 * Migración de portadas existentes a WebP.
 * Ejecutar por CLI: php scripts/migrar_imagenes.php
 *
 * Solo convierte libros que tienen portada y todavía no están en WebP.
 * El original se elimina únicamente después de convertir y actualizar la BD.
 */

require_once __DIR__ . '/../includes/app.php';

use App\Libro;
use App\ProcesadorImagen;

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo puede ejecutarse por línea de comandos.\n");
}

$carpeta = __DIR__ . '/../imagenes/';
$convertidas = 0;
$omitidas = 0;
$fallidas = 0;

foreach (Libro::all() as $libro) {
    $imagen = trim((string) $libro->imagen);

    if ($imagen === '') {
        $omitidas++;
        echo "OMITIDA libro {$libro->id}: sin portada\n";
        continue;
    }

    if (strtolower(pathinfo($imagen, PATHINFO_EXTENSION)) === 'webp') {
        $omitidas++;
        echo "OMITIDA libro {$libro->id}: ya está en WebP\n";
        continue;
    }

    $rutaOriginal = $carpeta . $imagen;

    if (!is_file($rutaOriginal)) {
        $fallidas++;
        echo "FALLO libro {$libro->id}: no existe el archivo {$imagen}\n";
        continue;
    }

    $nuevaImagen = null;

    try {
        $nuevaImagen = ProcesadorImagen::procesar($rutaOriginal, $carpeta);
        $libro->imagen = $nuevaImagen;

        if (!$libro->guardar()) {
            throw new RuntimeException('No se pudo actualizar el registro en la base de datos.');
        }

        // El original se borra solo después de una conversión y actualización correctas.
        if (is_file($rutaOriginal) && !unlink($rutaOriginal)) {
            echo "AVISO libro {$libro->id}: no se pudo borrar el original {$imagen}\n";
        }

        $convertidas++;
        echo "OK libro {$libro->id}: {$imagen} -> {$nuevaImagen}\n";
    } catch (Throwable $e) {
        if ($nuevaImagen !== null) {
            @unlink($carpeta . $nuevaImagen);
        }
        $fallidas++;
        echo "FALLO libro {$libro->id} ({$imagen}): " . $e->getMessage() . "\n";
    }
}

echo "\nResumen: {$convertidas} convertidas, {$fallidas} fallidas, {$omitidas} omitidas.\n";
