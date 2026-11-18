# Plan 001 — Portadas completas y livianas en WebP

## Enfoque
Dos frentes: dejar de recortar **al guardar** y dejar de recortar **al mostrar**. Hoy el recorte ocurre en `Image::make(...)->fit(800, 600)` y también en CSS (`object-fit: cover`). Corregir solo uno no alcanza.

## Pasos

1. **Verificar soporte WebP de GD.** Comprobar `function_exists('imagewebp')` en el entorno. Si no está disponible, usar el driver Imagick de Intervention Image (ya instalado en `vendor/`) antes de seguir.
2. **Procesador de imágenes compartido** en `includes/ProcesadorImagen.php`:
   - Validar MIME real del archivo con `finfo` (jpeg, png, webp). Rechazar otros y archivos corruptos.
   - Leer con `Intervention\Image\ImageManagerStatic` sin `fit()` ni `crop()`.
   - Convertir a WebP con calidad inicial 85.
   - Si el resultado supera 150 KB, reintentar bajando calidad (85 → 80 → … → 50). Si aún supera el tope, reducir proporcionalmente las dimensiones y reintentar. Si no se logra, devolver error (RF-4).
   - Devolver un nombre de archivo único con extensión `.webp`.
   - Centralizar aquí la lógica para que crear y editar no dupliquen código.
3. **Formulario** (`includes/templates/formulario_libro.php`): ampliar `accept` a `image/jpeg, image/png, image/webp` y agregar texto de ayuda con el tope de 150 KB.
4. **Alta y edición** (`admin/libros/crear.php`, `admin/libros/editar.php`): reemplazar el bloque `Image::make(...)->fit(800,600)->save(...jpg)` por el procesador. Mantener `setImagen()` para que se borre la portada anterior al reemplazarla.
5. **CSS** (`public/assets/css/style.css`):
   - `.card img` (línea ~272): `object-fit: contain` + color de fondo, en vez de `cover`.
   - `.contenido-libro img` (línea ~433): `object-fit: contain`, sin alto fijo que fuerce recorte.
   - Miniaturas de tablas (línea ~727): `object-fit: contain`.
   - Revisar reglas mobile (líneas ~796, ~816, ~897) para que no reintroduzcan `cover`.
6. **Migración de portadas existentes** en `scripts/migrar_imagenes.php`:
   - Recorrer `Libro::allConCategoria()` (o `all()`).
   - Para cada libro con imagen, convertir el JPG actual a WebP con el mismo procesador, actualizar `libros.imagen` y conservar el JPG original como respaldo (decisión por confirmar).
   - Registrar un log con conversiones OK y fallidas; no detener el proceso ante un error individual (RF-8).
   - Ejecutar en entorno local con copia de seguridad de `imagenes/` y de la BD.
7. **Documentación**: actualizar `README.md` (formato WebP, tope 150 KB, comando de migración).

## Criterios de verificación
- Subir imagen vertical y otra horizontal ancha: se ven completas en catálogo, detalle y préstamos.
- Subir WebP, JPG y PNG: todos se guardan como `.webp`.
- Archivo inválido o > 150 KB no convertible: error claro y libro no guardado.
- Reemplazar portada: desaparece la anterior en todas las vistas.
- Ejecutar migración: ningún libro queda sin imagen; fallos quedan listados.

## Riesgos
- GD sin soporte WebP (ver paso 1).
- `borrarImagen()` de `ActiveRecord` elimina el archivo anterior: verificar que no borre un JPG que se quiere conservar como respaldo durante la migración.
- Columna `libros.imagen` es `varchar(100)`: suficiente para nombres `.webp`, pero verificar.
