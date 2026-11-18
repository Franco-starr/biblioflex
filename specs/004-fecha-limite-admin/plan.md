# Plan 004 — Fecha límite de devolución definida por el admin

## Enfoque
La fecha límite nace en el mismo formulario con el que el admin confirma la entrega (spec 003) y se edita desde el listado mientras el préstamo siga abierto. Se apoya en los campos `fecha_estimada` (nullable) y `fecha_entrega` de la migración compartida.

## Pasos

1. **Modelo `models/Prestamo.php`**:
   - `confirmarEntrega($fechaLimite)` valida el formato `Y-m-d` con `DateTime::createFromFormat` y que `fecha_limite >= fecha(fecha_entrega)` (RF-5, RF-6).
   - Método `actualizarFechaLimite($fecha)` con guarda de estado: solo `prestado` o `atrasado` (RF-3, RF-4).
   - Eliminar definitivamente el cálculo `date('Y-m-d', strtotime('+15 days'))` que hoy está en `prestarLibro()`.
2. **Admin `admin/prestamos/index.php`**:
   - En el formulario de "Confirmar entrega", input `type="date"` name `fecha_limite` con `required`.
   - En filas `prestado`/`atrasado`, formulario inline con el mismo input precargado y botón "Guardar fecha".
   - En filas `pendiente`, `rechazado`, `devuelto`: no mostrar el campo ni el botón.
   - Mostrar errores de fecha con el mismo mecanismo de `?error=` ya existente.
3. **Vista usuario `public/prestamos/index.php`**: renombrar la columna "Fecha Estimada Devolución" a "Fecha límite" y mostrar un guion cuando no haya fecha (RF-9).
4. **Opcional solo si se confirma la duda**: tarea extra de detección automática de atraso al listar, comparando `fecha_estimada < hoy` para préstamos `prestado`. Por defecto NO se implementa.

## Criterios de verificación
- Confirmar entrega sin fecha: no se confirma y muestra un error.
- Confirmar con fecha anterior a hoy/entrega: error.
- Editar fecha con préstamo prestado o atrasado: se guarda y se ve en ambas vistas.
- Préstamo devuelto o rechazado: campo no editable.
- Nueva solicitud: no trae plazo automático de 15 días.

## Riesgos
- Zona horaria: el servidor usa `TIME_ZONE='+00:00'` en los dumps; comparar fechas en `Y-m-d` para evitar desfases.
- `type="date"` envía `Y-m-d`; validar igualmente en servidor, no solo en el navegador.
- El input inline puede colisionar con el formulario de acciones si se anidan forms: usar formularios hermanos, nunca anidados.
