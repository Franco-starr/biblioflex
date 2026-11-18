# Tasks 001 — Portadas completas y livianas en WebP

[Spec](spec.md) · [Plan](plan.md) · [Plan general](../plan-implementacion.md)

Cada tarea indica los RF que cubre. Marcar `[x]` solo cuando la verificación pase.

## Fase 1 — Preparación

- [ ] T-001 Verificar `function_exists('imagewebp')`; si no está, configurar Imagick como driver de Intervention Image. *(RF-2)*
- [ ] T-002 Hacer copia de seguridad de `imagenes/` y de la base de datos antes de cualquier migración. *(RF-7)*

## Fase 2 — Procesador de imágenes

- [ ] T-003 Crear `includes/ProcesadorImagen.php` con validación de MIME real (jpeg, png, webp) mediante `finfo`. *(RF-5)*
- [ ] T-004 Implementar conversión a WebP **sin** `fit()` ni `crop()`, conservando la proporción original. *(RF-1, RF-6)*
- [ ] T-005 Implementar reducción de calidad progresiva hasta alcanzar 150 KB; si no se logra, devolver error. *(RF-3, RF-4)*
- [ ] T-006 Generar nombre de archivo único con extensión `.webp`. *(RF-2)*
- [ ] T-007 Devolver mensajes de error claros para archivo inválido, corrupto y demasiado pesado. *(RF-4, RF-5)*

## Fase 3 — Alta y edición de libros

- [ ] T-008 Ampliar `accept` en `formulario_libro.php` a JPEG, PNG y WebP, con texto de ayuda del tope. *(RF-1, RF-5)*
- [ ] T-009 Refactorizar `admin/libros/crear.php` para usar el procesador en lugar de `fit(800,600)`. *(RF-1 a RF-5)*
- [ ] T-010 Refactorizar `admin/libros/editar.php` con el mismo procesador. *(RF-1 a RF-5)*
- [ ] T-011 Verificar que `setImagen()` borre la portada anterior al reemplazarla. *(RF-9)*
- [ ] T-012 Verificar que los errores se muestren en el formulario y que el libro no se guarde. *(RF-4, RF-5)*

## Fase 4 — Presentación

- [ ] T-013 Cambiar `.card img` a `object-fit: contain` con fondo. *(RF-6)*
- [ ] T-014 Cambiar `.contenido-libro img` a `object-fit: contain` sin alto forzado. *(RF-6)*
- [ ] T-015 Cambiar miniaturas de tablas a `object-fit: contain`. *(RF-6)*
- [ ] T-016 Revisar media queries móviles para que no reintroduzcan `cover`. *(RF-6)*
- [ ] T-017 Verificar portada completa en catálogo, detalle y listado de préstamos. *(RF-6)*

## Fase 5 — Migración de portadas existentes

- [ ] T-018 Crear `scripts/migrar_imagenes.php` que recorra todos los libros con imagen. *(RF-7)*
- [ ] T-019 Convertir cada JPG a WebP y actualizar `libros.imagen`. *(RF-2, RF-7)*
- [ ] T-020 Conservar el original como respaldo (decisión por confirmar). *(RF-7)*
- [ ] T-021 Registrar log de conversiones correctas y fallidas sin detener el proceso. *(RF-8)*
- [ ] T-022 Ejecutar la migración en local y verificar que ningún libro quedó sin portada. *(RF-7)*
- [ ] T-023 Actualizar `README.md` con formato WebP, tope de 150 KB y comando de migración.

## Fase 6 — Verificación final

- [ ] T-024 Probar subida de imagen vertical, horizontal ancha, PNG y WebP.
- [ ] T-025 Probar archivo inválido y archivo que no puede bajar de 150 KB.
- [ ] T-026 Medir peso de las portadas generadas y migradas (≤ 150 KB).
- [ ] T-027 Demo manual de alta, reemplazo y migración en escritorio y móvil.

## Bloqueantes

- T-001 (soporte WebP) antes de T-004.
- Decisión sobre borrado de originales antes de T-020.
