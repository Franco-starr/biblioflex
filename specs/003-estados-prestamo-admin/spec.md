# Spec 003 — Solicitudes pendientes y confirmación del admin

## Contexto y objetivo
Hoy una solicitud de préstamo se registra directamente como préstamo activo y descuenta el stock sin intervención del admin, que solo puede registrar la devolución. Se quiere que la solicitud quede pendiente, que el admin confirme la entrega o la rechace, y que registre la devolución o el atraso, con el stock afectado solo cuando corresponde.

## Usuarios / actores
- **Usuario:** solicita préstamos y consulta el estado de los suyos.
- **Admin:** confirma entregas, rechaza solicitudes, marca atrasos y confirma devoluciones.

## Historias de usuario
- H1: Como usuario quiero que mi solicitud quede registrada como pendiente para saber que está en revisión.
- H2: Como usuario quiero ver el estado real de mis préstamos para saber qué esperar.
- H3: Como admin quiero confirmar la entrega de un libro para recién entonces descontar el stock.
- H4: Como admin quiero rechazar una solicitud sin alterar el stock.
- H5: Como admin quiero marcar un atraso y después registrar la devolución para llevar el historial correcto.
- H6: Como admin quiero que no se pueda confirmar dos veces la misma acción para no duplicar movimientos de stock.

## Requisitos funcionales (criterios de aceptación en EARS)
- RF-1: CUANDO un usuario solicita un libro, EL SISTEMA registra el préstamo en estado "pendiente".
- RF-2: CUANDO un usuario solicita un libro, EL SISTEMA no modifica el stock.
- RF-3: SI el usuario ya tiene una solicitud pendiente o un préstamo vigente del mismo libro, ENTONCES EL SISTEMA no crea otra solicitud e informa el motivo.
- RF-4: CUANDO el admin confirma la entrega de una solicitud pendiente, EL SISTEMA cambia el estado a "prestado".
- RF-5: CUANDO el admin confirma la entrega, EL SISTEMA descuenta una unidad del stock del libro.
- RF-6: CUANDO el admin rechaza una solicitud pendiente, EL SISTEMA cambia el estado a "rechazado".
- RF-7: CUANDO el admin rechaza una solicitud, EL SISTEMA no modifica el stock.
- RF-8: SI no hay stock disponible al confirmar la entrega, ENTONCES EL SISTEMA mantiene el estado "pendiente" e informa el motivo.
- RF-9: CUANDO el admin marca un préstamo como atrasado, EL SISTEMA cambia el estado a "atrasado" sin modificar el stock.
- RF-10: CUANDO el admin confirma la devolución de un préstamo "prestado" o "atrasado", EL SISTEMA cambia el estado a "devuelto".
- RF-11: CUANDO el admin confirma la devolución, EL SISTEMA registra la fecha y hora reales de devolución.
- RF-12: CUANDO el admin confirma la devolución, EL SISTEMA suma una unidad al stock del libro.
- RF-13: SI el admin intenta confirmar la entrega de un préstamo que no está "pendiente", ENTONCES EL SISTEMA no realiza el cambio e informa que el estado no permite esa acción.
- RF-14: SI el admin intenta devolver un préstamo ya "devuelto" o "rechazado", ENTONCES EL SISTEMA no modifica el stock e informa el motivo.
- RF-15: EL SISTEMA muestra el estado de cada préstamo en la vista del usuario y en la vista del admin.
- RF-16: MIENTRAS un préstamo está "pendiente" o "rechazado", EL SISTEMA no ofrece la acción de devolución al admin.

## Requisitos no funcionales
- Idioma de estados, mensajes y botones: español.
- Las acciones que cambian el estado requieren una sesión con rol de admin.

## Casos límite
- Stock cero o negativo al confirmar una entrega.
- Doble clic o reenvío de una confirmación de entrega o de devolución.
- Préstamo pendiente cuyo libro deja de estar disponible.
- Préstamo rechazado sobre el que se hace una nueva solicitud del mismo libro.
- Préstamo atrasado que luego se devuelve: el stock debe sumarse una sola vez.
- Dos admins confirmando el mismo préstamo al mismo tiempo.
- Usuario o libro dado de baja con préstamos pendientes asociados.

## Fuera de alcance
- Notificaciones automáticas por correo al usuario sobre cambios de estado.
- Multas, penalizaciones o suspensiones.
- Renovación o prórroga de préstamos.
- Confirmación de entregas por parte del usuario: solo lo hace el admin.
- Motivos obligatorios de rechazo o mensajes personalizados al usuario.

## Criterios de finalización
- Demo completa: usuario solicita y ve "pendiente" con stock sin cambios; admin confirma y ve "prestado" con stock menos uno; admin marca "atrasado"; admin confirma devolución y ve "devuelto" con stock más uno; admin rechaza otra solicitud y el stock queda igual.
- Verificación de que la misma acción no se puede aplicar dos veces y de que el stock no se duplica.

## Dudas abiertas
- [NECESITA ACLARACIÓN] ¿El rechazo debe incluir un motivo visible para el usuario?
- [NECESITA ACLARACIÓN] ¿Una solicitud pendiente expira automáticamente si el admin no la confirma en cierto tiempo?
- [NECESITA ACLARACIÓN] ¿El atraso se marca solo manualmente o también se detecta al superar la fecha límite de la spec 004?
