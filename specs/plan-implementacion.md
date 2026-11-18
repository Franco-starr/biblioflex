# Plan de implementación — Biblioflex

Contrato de entrada: specs 001 a 004 (aprobadas). Este documento describe el **cómo**; los specs describen el **qué**.

## Orden recomendado

| Orden | Spec | Motivo |
|---|---|---|
| 1 | 001 — Portadas WebP | Independiente, bug visible y rápido de validar |
| 2 | 003 + 004 — Préstamos | Comparten migración de BD y el modelo `Prestamo`; 004 se apoya en la acción "confirmar entrega" de 003 |
| 3 | 002 — Secciones | Independiente; se hace al final para no mezclar navegación con el flujo de préstamos |

## Migración de base de datos (única, antes de 003/004)

Archivo nuevo `sql/migracion_estados_prestamo.sql` y actualización del dump canónico `sql/biblioflex_prestamo.sql`:

```sql
ALTER TABLE prestamo
  MODIFY estado ENUM('pendiente','prestado','rechazado','atrasado','devuelto')
       NOT NULL DEFAULT 'pendiente',
  MODIFY fecha_estimada DATE NULL,
  ADD fecha_entrega DATETIME NULL AFTER fecha_prestamo;

UPDATE prestamo SET estado = 'devuelto' WHERE estado = 'devuelto';
-- Los préstamos ya existentes se dejan como 'prestado' o 'devuelto' según su estado actual.
```

`fecha_estimada` pasa a nullable porque una solicitud pendiente no tiene plazo definido (RF-9 de la spec 004). `fecha_entrega` es necesaria para validar que la fecha límite no sea anterior a la entrega (RF-5 de la spec 004).

## Decisiones a confirmar antes de escribir código

Estas vienen de las dudas abiertas de los specs. El plan toma un valor por defecto para poder avanzar, pero hay que confirmarlo:

1. **Atraso automático:** por defecto solo manual (admin). Si se quiere automático, agregar una tarea en 004 que compare `fecha_estimada` con la fecha actual al listar.
2. **Originales JPG:** por defecto se conservan como respaldo y no se borran. Si se quieren eliminar, ajustar la tarea de migración de 001.
3. **Fallback JPG para WebP:** por defecto no se genera. agregar solo si hay que soportar navegadores antiguos.
4. **UI/UX específica:** sin alcance definido más allá de secciones y navegación. Hace falta que se indiquen pantallas y problemas concretos.
5. **Datos de contacto:** por defecto solo correo electrónico.
6. **Fecha límite:** por defecto obligatoria al confirmar entrega y sin tope de días.

## Archivos que se tocan (resumen)

| Spec | Archivos principales |
|---|---|
| 001 | `includes/ProcesadorImagen.php` (nuevo), `includes/templates/formulario_libro.php`, `admin/libros/crear.php`, `admin/libros/editar.php`, `public/assets/css/style.css`, `scripts/migrar_imagenes.php` (nuevo), `README.md` |
| 002 | `sql/migracion_contenido.sql` (nuevo), `models/Contenido.php` (nuevo), `public/sobre-mi.php`, `public/contacto.php`, `admin/contenido/index.php`, `admin/contenido/editar.php`, `includes/templates/navbar.php`, `admin/index.php`, `includes/funciones.php` |
| 003 | `sql/migracion_estados_prestamo.sql` (nuevo), `sql/biblioflex_prestamo.sql`, `models/Prestamo.php`, `admin/prestamos/index.php`, `public/prestamos/index.php`, `public/libro.php`, `includes/funciones.php` |
| 004 | `models/Prestamo.php`, `admin/prestamos/index.php`, `public/prestamos/index.php` |

## Verificación global (definition of done)

- Sin `fit()` ni `object-fit: cover` en portadas de libros.
- Toda portada nueva y migrada: WebP, completa, ≤ 150 KB.
- Flujo completo: pendiente → entregado → atrasado → devuelto, más rechazo, con stock correcto en cada paso y sin dobles descuentos.
- Fecha límite cargada por admin, editable solo mientras está prestado/atrasado, visible para el usuario.
- Secciones públicas editables desde el panel y accesibles desde la navegación.
- Demo manual de los 4 flujos principales en escritorio y móvil.

Planes detallados: `001-portadas-webp-sin-recorte/plan.md`, `002-secciones-sobre-mi-contacto/plan.md`, `003-estados-prestamo-admin/plan.md`, `004-fecha-limite-admin/plan.md`.
