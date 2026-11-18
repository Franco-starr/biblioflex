# Plan 001 — Portadas completas y livianas en WebP

## Enfoque
Dos frentes: dejar de recortar **al guardar** y dejar de recortar **al mostrar**. Hoy el recorte ocurre en `Image::make(...)->fit(800, 600)` y también en CSS (`object-fit: cover`). Corregir solo uno no alcanza.

## Pasos

1. **Verificar soporte WebP de GD.** Comprobar `function_exists('imagewebp')` en el entorno. Si no está disponible, usar el driver Imagick de Intervention Image (ya instalado en `vendor/`) antes de seguir.
2. **Procesador de imágenes compartido** en `includes/ProcesadorImagen.php`:
   - Validar MIME real del archivo con `finfo` (jpeg, png, webp). Rechazar otros y archivos corruptos.
   - Leer con `Intervention\Image\ImageManagerStatic` sin `fit()` ni `crop()`.
   - Convertir a WebP con calidad inicial 85.
   - Si el resultado supera 150 KB, reintentar bajando calidad (85 → 80 → … → 50). Si aún supera el tope, reducir proporcionalmente las dimensiones y reintentar. No se fija un tope de píxeles: la resolución original se conserva mientras el archivo cumpla el peso (RF-3). Si no se logra, devolver error (RF-4).
   - Devolver un nombre de archivo único con extensión `.webp`.
   - Centralizar aquí la lógica para que crear y editar no dupliquen código.
   - No generar archivos JPG alternativos (fuera de alcance).
3. **Formulario** (`includes/templates/formulario_libro.php`): ampliar `accept` a `image/jpeg, image/png, image/webp` y agregar texto de ayuda con el tope de 150 KB.
4. **Alta y edición** (`admin/libros/crear.php`, `admin/libros/editar.php`): reemplazar el bloque `Image::make(...)->fit(800,600)->save(...jpg)` por el procesador. Mantener `setImagen()` para que se borre la portada anterior al reemplazarla. Si la imagen nueva falla, no se guarda nada y se conserva la portada anterior (RF-10).
5. **CSS** (`public/assets/css/style.css`):
   - `.card img` (línea ~272): `object-fit: contain` + color de fondo, en vez de `cover`.
   - `.contenido-libro img` (línea ~433): `object-fit: contain`, sin alto fijo que fuerce recorte.
   - Miniaturas de tablas (línea ~727): `object-fit: contain`.
   - Revisar reglas mobile (líneas ~796, ~816, ~897) para que no reintroduzcan `cover`.
6. **Migración de portadas existentes** en `scripts/migrar_imagenes.php`:
   - Recorrer solo los libros con `imagen` no vacía; dejar sin cambios a los que no tienen portada (RF-11).
   - Omitir portadas ya en `.webp` para que la migración sea idempotente.
   - Convertir con el mismo procesador, actualizar `libros.imagen` en la BD y **solo entonces** eliminar el JPG original (RF-12).
   - Si la conversión falla, conservar el archivo original, no modificar el registro y registrarlo en el log (RF-8).
   - Registrar un log con conversiones OK y fallidas; no detener el proceso ante un error individual.
   - Ejecutar en entorno local con copia de seguridad de `imagenes/` y de la BD.
7. **Documentación**: actualizar `README.md` (formato WebP, tope 150 KB, comando de migración).

## Criterios de verificación
- Subir imagen vertical y otra horizontal ancha: se ven completas en catálogo, detalle y préstamos.
- Subir WebP, JPG y PNG: todos se guardan como `.webp`; no se crea ningún JPG alternativo.
- Archivo inválido o > 150 KB no convertible: error claro, libro no guardado y portada anterior conservada al editar.
- Reemplazar portada: desaparece la anterior en todas las vistas.
- Ejecutar migración: ningún libro con portada la pierde; los JPG convertidos se eliminan; los fallidos se conservan y se listan; los libros sin imagen siguen sin imagen.

## Riesgos
- GD sin soporte WebP (ver paso 1).
- `borrarImagen()` de `ActiveRecord` elimina el archivo anterior: en alta/edición es el comportamiento deseado al reemplazar (RF-9); en la migración el borrado del JPG debe ocurrir solo después de actualizar el registro (RF-12).
- Columna `libros.imagen` es `varchar(100)`: suficiente para nombres `.webp`, pero verificar.
