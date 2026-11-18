# Tasks 001 — Portadas completas y livianas en WebP

[Spec](spec.md) · [Plan](plan.md) · [Plan general](../plan-implementacion.md)

Cada tarea indica los RF que cubre. Marcar `[x]` solo cuando la verificación pase.

## Fase 1 — Preparación

- [x] T-001 Verificar `function_exists('imagewebp')`; si no está, configurar Imagick como driver de Intervention Image. *(RF-2)*
- [ ] T-002 Hacer copia de seguridad de `imagenes/` y de la base de datos antes de cualquier migración. *(RF-7)*

## Fase 2 — Procesador de imágenes

- [x] T-003 Crear `includes/ProcesadorImagen.php` con validación de MIME real (jpeg, png, webp) mediante `finfo`. *(RF-5)*
- [x] T-004 Implementar conversión a WebP **sin** `fit()` ni `crop()`, conservando la proporción original. *(RF-1, RF-6)*
- [x] T-005 Implementar reducción de calidad progresiva hasta alcanzar 150 KB; si no se logra, devolver error. No se fija tope de píxeles. *(RF-3, RF-4)*
- [x] T-006 Generar nombre de archivo único con extensión `.webp`. *(RF-2)*
- [x] T-007 Devolver mensajes de error claros para archivo inválido, corrupto y demasiado pesado. *(RF-4, RF-5)*

## Fase 3 — Alta y edición de libros

- [ ] T-008 Ampliar `accept` en `formulario_libro.php` a JPEG, PNG y WebP, con texto de ayuda del tope. *(RF-1, RF-5)*
- [ ] T-009 Refactorizar `admin/libros/crear.php` para usar el procesador en lugar de `fit(800,600)`. *(RF-1 a RF-5)*
- [ ] T-010 Refactorizar `admin/libros/editar.php` con el mismo procesador. *(RF-1 a RF-5)*
- [ ] T-011 Verificar que `setImagen()` borre la portada anterior al reemplazarla. *(RF-9)*
- [ ] T-012 Verificar que los errores se muestren en el formulario, que el libro no se guarde y que se conserven datos y portada anterior al editar. *(RF-4, RF-5, RF-10)*

## Fase 4 — Presentación

- [ ] T-013 Cambiar `.card img` a `object-fit: contain` con fondo. *(RF-6)*
- [ ] T-014 Cambiar `.contenido-libro img` a `object-fit: contain` sin alto forzado. *(RF-6)*
- [ ] T-015 Cambiar miniaturas de tablas a `object-fit: contain`. *(RF-6)*
- [ ] T-016 Revisar media queries móviles para que no reintroduzcan `cover`. *(RF-6)*
- [ ] T-017 Verificar portada completa en catálogo, detalle y listado de préstamos. *(RF-6)*

## Fase 5 — Migración de portadas existentes

- [x] T-018 Crear `scripts/migrar_imagenes.php` que recorra todos los libros con imagen; omitir los que no tienen y los que ya están en WebP. *(RF-7, RF-11)*
- [x] T-019 Convertir cada portada a WebP y actualizar `libros.imagen`. *(RF-2, RF-7)*
- [x] T-020 Eliminar el JPG original solo tras conversión y actualización correctas; conservarlo si la conversión falla. *(RF-8, RF-12)*
- [x] T-021 Registrar log de conversiones correctas y fallidas sin detener el proceso. *(RF-8)*
- [x] T-022 Ejecutar la migración en local y verificar que ningún libro con portada la perdió, que los JPG convertidos se eliminaron y que los fallidos se conservan. *(RF-7, RF-8, RF-11, RF-12)*
- [x] T-023 Actualizar `README.md` con formato WebP, tope de 150 KB y comando de migración.

## Fase 6 — Verificación final

- [x] T-024 Probar subida de imagen vertical, horizontal ancha, PNG y WebP.
- [x] T-025 Probar archivo inválido y archivo que no puede bajar de 150 KB, verificando que no se guarda el libro y se conserva la portada anterior al editar.
- [x] T-026 Medir peso de las portadas generadas y migradas (≤ 150 KB).
- [ ] T-027 Demo manual de alta, reemplazo y migración en escritorio y móvil.
- [x] T-028 Verificar que los libros sin imagen siguen sin imagen tras la migración. *(RF-11)*
- [x] T-029 Verificar que no se genera ningún archivo JPG de fallback. *(RF-2)*

## Bloqueantes

- T-001 (soporte WebP) antes de T-004.
