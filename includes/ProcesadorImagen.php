<?php

namespace App;

use Intervention\Image\ImageManagerStatic as Image;

/**
 * Convierte portadas a WebP sin recortarlas y respetando el peso máximo.
 * Es el único punto donde se procesan las imágenes de los libros.
 */
class ProcesadorImagen
{
    /** 150 KB en bytes */
    public const PESO_MAXIMO = 153600;

    private const CALIDADES = [85, 80, 75, 70, 65, 60, 55, 50];

    private const REDUCCIONES = 10;

    /**
     * @param string $archivo Ruta del archivo subido o de la portada a migrar.
     * @param string|null $carpeta Carpeta de destino; por defecto imagenes/.
     * @return string Nombre único del archivo WebP generado.
     * @throws \RuntimeException Cuando el archivo no es válido o no alcanza el peso máximo.
     */
    public static function procesar(string $archivo, ?string $carpeta = null): string
    {
        if (!is_file($archivo)) {
            throw new \RuntimeException('No se recibió ningún archivo de imagen.');
        }

        if (filesize($archivo) === 0) {
            throw new \RuntimeException('El archivo de imagen está vacío.');
        }

        $tipo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $tipo->file($archivo);
        $permitidos = ['image/jpeg', 'image/png', 'image/webp'];

        if (!in_array($mime, $permitidos, true)) {
            throw new \RuntimeException('Formato de imagen no permitido. Usa JPG, PNG o WebP.');
        }

        $contenido = file_get_contents($archivo);

        Image::configure(['driver' => 'gd']);

        try {
            $original = Image::make($contenido);
        } catch (\Exception $e) {
            throw new \RuntimeException('La imagen está corrupta o no se pudo leer.', 0, $e);
        }

        $anchoOriginal = $original->width();
        $altoOriginal = $original->height();

        $carpeta = $carpeta ?? __DIR__ . '/../imagenes/';
        if (!is_dir($carpeta)) {
            mkdir($carpeta, 0755, true);
        }

        $nombre = md5(uniqid(rand(), true)) . '.webp';
        $ruta = rtrim($carpeta, '/\\') . DIRECTORY_SEPARATOR . $nombre;

        $factor = 1.0;

        for ($intento = 0; $intento <= self::REDUCCIONES; $intento++) {
            $ancho = max(1, (int) round($anchoOriginal * $factor));
            $alto = max(1, (int) round($altoOriginal * $factor));

            foreach (self::CALIDADES as $calidad) {
                $imagen = Image::make($contenido);

                if ($ancho !== $anchoOriginal || $alto !== $altoOriginal) {
                    $imagen->resize($ancho, $alto);
                }

                $binario = (string) $imagen->encode('webp', $calidad);

                if (strlen($binario) <= self::PESO_MAXIMO) {
                    if (file_put_contents($ruta, $binario) === false) {
                        throw new \RuntimeException('No se pudo guardar la portada WebP.');
                    }
                    return $nombre;
                }
            }

            if ($ancho <= 16 || $alto <= 16) {
                break;
            }

            $factor *= 0.85;
        }

        throw new \RuntimeException('La imagen es demasiado pesada: no se pudo reducir a 150 KB.');
    }
}
