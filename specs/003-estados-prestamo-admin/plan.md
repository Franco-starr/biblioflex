# Plan 003 — Solicitudes pendientes y confirmación del admin

## Enfoque
Cambiar el estado inicial, mover el descuento de stock al momento de la entrega y reemplazar el único botón "Devolver" por acciones por estado. Se implementa junto con la migración de la spec 004 porque comparten tabla y modelo.

## Pasos

1. **Base de datos**: aplicar `sql/migracion_estados_prestamo.sql` (ver `plan-implementacion.md`) y actualizar `sql/biblioflex_prestamo.sql`.
2. **Modelo `models/Prestamo.php`**:
   - Agregar propiedad `fecha_entrega` y meterla en `$columnasDB`.
   - `solicitar()`: crear con `estado = 'pendiente'`, `fecha_prestamo = now()`, sin `fecha_estimada` y sin tocar stock. Reemplaza al actual `prestarLibro()`.
   - `confirmarEntrega($fechaLimite)`: transacción con guardas de estado `pendiente` y `stock > 0`; setea `fecha_entrega`, `estado = 'prestado'`, guarda la fecha límite y descuenta stock. Si falla algo, rollback.
   - `rechazar()`: solo desde `pendiente`, cambia a `rechazado`, sin stock.
   - `marcarAtrasado()`: solo desde `prestado` (o `atrasado`), cambia a `atrasado`, sin stock.
   - `devolverLibro()` (existente): ampliar guardas para `prestado` y `atrasado`; registrar `fecha_devolucion`, pasar a `devuelto` y sumar stock dentro de la transacción.
   - `validar()`: considerar `pendiente`, `prestado` y `atrasado` como situaciones que bloquean una nueva solicitud del mismo libro.
   - Todos los métodos deben ser idempotentes: si el estado no permite la acción, error y sin cambios (RF-13, RF-14).
3. **Acceso a datos**: `allConDetalle()` y `prestamosPorUsuario()` ya traen `estado` y `fecha_devolución`; agregar `fecha_entrega` al SELECT si se muestra en pantalla.
4. **Admin `admin/prestamos/index.php`**:
   - Enrutamiento por un campo `accion` en el POST (`confirmar`, `rechazar`, `atrasado`, `devolver`) en vez de un solo formulario.
   - Botones según estado: pendiente → "Confirmar entrega" + "Rechazar"; prestado → "Marcar atrasado" + "Devolver"; atrasado → "Devolver"; devuelto/rechazado → sin acciones.
   - "Confirmar entrega" incluye el campo `fecha_limite` (comparte con spec 004).
   - `confirm()` de JS para rechazo y devolución, como ya se hace hoy.
5. **Vista usuario `public/prestamos/index.php`**: mostrar estados con etiquetas en español, sin acciones; agregar columna `fecha_entrega` si aplica.
6. **Detalle de libro `public/libro.php`**: cambiar el mensaje de éxito a "Solicitud enviada, pendiente de aprobación".
7. **Mensajes `includes/funciones.php`**: nuevos códigos en `mostrarNotificacion()` para entrega, rechazo y atraso.
8. **Datos existentes**: revisar préstamos en estado `prestado` con `fecha_estimada` vieja; la migración los conserva y les asigna `fecha_entrega = fecha_prestamo` para no dejar el campo vacío.

## Criterios de verificación
- Usuario solicita: aparece "pendiente", stock igual.
- Admin confirma: "prestado", stock -1, `fecha_entrega` cargada.
- Admin marca atrasado: "atrasado", stock sin cambios.
- Admin devuelve: "devuelto", stock +1, `fecha_devolucion` registrada.
- Admin rechaza: "rechazado", stock sin cambios.
- Reenviar cualquier POST dos veces: no hay segundos cambios de stock.
- Sin stock: confirmar entrega falla con mensaje y sigue pendiente.

## Riesgos
- Concurrencia de dos admins: las guardas de estado dentro de la transacción no bastan con `mysqli` por defecto; considerar `SELECT ... FOR UPDATE` sobre la fila del préstamo.
- `ActiveRecord::guardar()` actualiza todas las columnas; verificar que no pise `fecha_estimada` con `null` en estados donde debe permanecer.
- El enum viejo no contempla `pendiente`/`rechazado`: la migración es obligatoria antes de deployar código.
