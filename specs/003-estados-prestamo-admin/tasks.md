# Tasks 003 — Solicitudes pendientes y confirmación del admin

[Spec](spec.md) · [Plan](plan.md) · [Plan general](../plan-implementacion.md)

Cada tarea indica los RF que cubre. Marcar `[x]` solo cuando la verificación pase.

## Fase 1 — Base de datos

- [ ] T-001 Crear `sql/migracion_estados_prestamo.sql`: enum con `pendiente` y `rechazado`, `fecha_estimada` nullable, nueva columna `fecha_entrega`. *(RF-1, RF-9)*
- [ ] T-002 Actualizar el dump canónico `sql/biblioflex_prestamo.sql`. *(RF-1)*
- [ ] T-003 Backfill de `fecha_entrega` para los préstamos ya existentes. *(RF-4)*
- [ ] T-004 Aplicar la migración en local antes de tocar código. *(RF-1)*

## Fase 2 — Modelo `Prestamo`

- [ ] T-005 Agregar propiedad `fecha_entrega` y meterla en `$columnasDB`. *(RF-5)*
- [ ] T-006 Reemplazar `prestarLibro()` por `solicitar()`: estado `pendiente`, sin stock, sin plazo automático. *(RF-1, RF-2)*
- [ ] T-007 Actualizar `validar()` para bloquear duplicados desde `pendiente`. *(RF-3)*
- [ ] T-008 Implementar `confirmarEntrega()` con transacción y guarda de estado + stock. *(RF-4, RF-5, RF-8, RF-13)*
- [ ] T-009 Implementar `rechazar()` solo desde `pendiente`, sin stock. *(RF-6, RF-7)*
- [ ] T-010 Implementar `marcarAtrasado()` sin modificar stock. *(RF-9)*
- [ ] T-011 Ampliar `devolverLibro()` a `prestado` y `atrasado`, con `fecha_devolucion` y +1 stock. *(RF-10, RF-11, RF-12, RF-14)*
- [ ] T-012 Agregar `SELECT ... FOR UPDATE` sobre el préstamo para evitar dobles confirmaciones concurrentes. *(RF-13, RF-14)*
- [ ] T-013 Incluir `fecha_entrega` en `allConDetalle()` y `prestamosPorUsuario()`. *(RF-15)*

## Fase 3 — Panel admin

- [ ] T-014 Enrutamiento del POST por campo `accion` (`confirmar`, `rechazar`, `atrasado`, `devolver`). *(RF-4 a RF-10)*
- [ ] T-015 Botones por estado: pendiente → confirmar/rechazar; prestado → atrasado/devolver; atrasado → devolver; cerrados → ninguno. *(RF-16)*
- [ ] T-016 Formulario de "Confirmar entrega" con campo de fecha límite (integración con 004). *(RF-4)*
- [ ] T-017 Confirmación por JavaScript para rechazo y devolución. *(RF-6, RF-10)*
- [ ] T-018 Mostrar errores de stock y de estado inválido en el listado. *(RF-8, RF-13, RF-14)*

## Fase 4 — Vista usuario y mensajes

- [ ] T-019 Mostrar los cinco estados en "Mis Préstamos" sin acciones. *(RF-15)*
- [ ] T-020 Cambiar el mensaje de `public/libro.php` a solicitud pendiente de aprobación. *(RF-1)*
- [ ] T-021 Extender `mostrarNotificacion()` con códigos para entrega, rechazo y atraso. *(RF-4, RF-6, RF-9)*

## Fase 5 — Verificación final

- [ ] T-022 Demo: pendiente → entregado → atrasado → devuelto, verificando stock en cada paso.
- [ ] T-023 Demo de rechazo sin cambios de stock.
- [ ] T-024 Probar doble envío de cada POST y verificar que no haya segundos descuentos.
- [ ] T-025 Probar confirmación con stock cero: error y estado pendiente.
- [ ] T-026 Probar rechazo, devolución y re-solicitud del mismo libro.

## Bloqueantes

- T-004 (migración aplicada) antes de T-005.
- T-016 depende del campo de fecha de la spec 004.
- Definir si el rechazo lleva motivo visible antes de T-015.
