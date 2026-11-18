# Tasks 004 — Fecha límite de devolución definida por el admin

[Spec](spec.md) · [Plan](plan.md) · [Plan general](../plan-implementacion.md)

Cada tarea indica los RF que cubre. Marcar `[x]` solo cuando la verificación pase.

## Fase 1 — Modelo `Prestamo`

- [ ] T-001 Eliminar el cálculo automático de +15 días que hoy está en `prestarLibro()`. *(RF-8)*
- [ ] T-002 Validar formato `Y-m-d` con `DateTime::createFromFormat` en `confirmarEntrega()`. *(RF-6)*
- [ ] T-003 Validar que la fecha límite no sea anterior a la fecha de entrega. *(RF-5)*
- [ ] T-004 Persistir `fecha_estimada` al confirmar la entrega. *(RF-1)*
- [ ] T-005 Implementar `actualizarFechaLimite($fecha)` con guarda de estado: solo `prestado` o `atrasado`. *(RF-3, RF-4)*
- [ ] T-006 Rechazar la edición en `pendiente`, `devuelto` y `rechazado`. *(RF-4)*

## Fase 2 — Panel admin

- [ ] T-007 Agregar input `type="date"` con `name="fecha_limite"` y `required` al confirmar entrega. *(RF-1, RF-2)*
- [ ] T-008 No confirmar la entrega si la fecha viene vacía o es inválida. *(RF-2, RF-6)*
- [ ] T-009 Agregar formulario inline de edición en filas `prestado` y `atrasado`. *(RF-3)*
- [ ] T-010 Mostrar el valor actual precargado en el input de edición. *(RF-3)*
- [ ] T-011 Ocultar el campo en filas `pendiente`, `rechazado` y `devuelto`. *(RF-4, RF-9)*
- [ ] T-012 Evitar formularios anidados en la tabla (hermanos, nunca dentro de otro form). *(RF-3)*
- [ ] T-013 Mostrar los errores de fecha por el mecanismo existente `?error=`. *(RF-5, RF-6)*

## Fase 3 — Vista usuario

- [ ] T-014 Renombrar la columna a "Fecha límite" en `public/prestamos/index.php`. *(RF-7)*
- [ ] T-015 Mostrar un guion cuando el préstamo aún no tiene fecha (pendiente). *(RF-9)*
- [ ] T-016 Verificar que la fecha se vea igual en el panel admin y en la vista del usuario. *(RF-7)*

## Fase 4 — Opcional (solo si se confirma la duda)

- [ ] T-017 **[BLOQUEADO: decisión pendiente]** Detectar atraso automáticamente al listar si `fecha_estimada < hoy` y el estado es `prestado`.
- [ ] T-018 **[BLOQUEADO: decisión pendiente]** Definir tope máximo de días hacia adelante para la fecha límite.

## Fase 5 — Verificación final

- [ ] T-019 Confirmar entrega sin fecha: no se confirma y muestra error.
- [ ] T-020 Confirmar con fecha anterior a la entrega: error.
- [ ] T-021 Editar fecha con préstamo prestado y atrasado: se guarda y se refleja.
- [ ] T-022 Préstamo devuelto: campo no editable.
- [ ] T-023 Nueva solicitud: no trae plazo automático de 15 días.
- [ ] T-024 Demo manual completa en escritorio y móvil.

## Bloqueantes

- Depende de la migración de la spec 003 (`fecha_estimada` nullable y `fecha_entrega`).
- T-001 depende de que la spec 003 haya reemplazado `prestarLibro()` por `solicitar()`.
- T-017 y T-018 requieren confirmación de las dudas abiertas.
