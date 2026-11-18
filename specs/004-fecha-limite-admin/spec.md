# Spec 004 — Fecha límite de devolución definida por el admin

## Contexto y objetivo
La fecha estimada de devolución se calcula hoy de forma automática al crear el préstamo y no puede modificarse. Se quiere que el admin la cargue al confirmar la entrega y pueda corregirla mientras el libro siga prestado, para que el plazo responda a cada caso real.

## Usuarios / actores
- **Admin:** carga y edita la fecha límite al confirmar entregas y durante el préstamo.
- **Usuario:** consulta la fecha límite en sus préstamos.

## Historias de usuario
- H1: Como admin quiero cargar la fecha límite al entregar un libro para definir el plazo de cada préstamo.
- H2: Como admin quiero corregir la fecha límite mientras el libro está prestado para ajustar el plazo si cambia la situación.
- H3: Como usuario quiero ver la fecha límite para saber cuándo debo devolver el libro.
- H4: Como admin quiero que se rechacen fechas inválidas para evitar plazos incoherentes.

## Requisitos funcionales (criterios de aceptación en EARS)
- RF-1: CUANDO el admin confirma la entrega de un préstamo, EL SISTEMA le permite cargar la fecha límite de devolución.
- RF-2: SI el admin confirma la entrega sin cargar la fecha límite, ENTONCES EL SISTEMA no confirma la entrega e informa que la fecha es obligatoria.
- RF-3: CUANDO el admin edita la fecha límite de un préstamo "prestado" o "atrasado", EL SISTEMA guarda el nuevo valor.
- RF-4: MIENTRAS el préstamo está "devuelto" o "rechazado", EL SISTEMA no permite editar la fecha límite.
- RF-5: SI la fecha límite cargada es anterior a la fecha de entrega, ENTONCES EL SISTEMA rechaza el valor e informa el motivo.
- RF-6: SI el formato de fecha no es válido, ENTONCES EL SISTEMA rechaza el valor e informa el formato esperado.
- RF-7: EL SISTEMA muestra la fecha límite en la vista de préstamos del usuario y en la vista del admin.
- RF-8: EL SISTEMA deja de asignar automáticamente un plazo fijo de 15 días al crear la solicitud.
- RF-9: CUANDO un préstamo está "pendiente", EL SISTEMA no muestra una fecha límite definida.

## Requisitos no funcionales
- Idioma de mensajes y etiquetas: español.
- Las fechas se muestran con el mismo formato que las demás fechas del sistema.

## Casos límite
- Fecha límite igual a la fecha de entrega.
- Fecha muy lejana o en el pasado.
- Edición de la fecha por dos admins al mismo tiempo.
- Préstamo rechazado, que no llega a tener fecha límite.
- Préstamo atrasado cuya fecha se corrige antes de la devolución.
- Reenvío del formulario de confirmación de entrega con una fecha distinta a la original.

## Fuera de alcance
- Cálculo automático del atraso al superar la fecha límite: el atraso lo registra el admin según la spec 003.
- Prórrogas solicitadas por el usuario.
- Recordatorios o avisos automáticos por correo.
- Carga de la fecha límite por parte del usuario al solicitar el libro.

## Criterios de finalización
- Demo: el admin confirma una entrega cargando la fecha, la ve en su panel y en "Mis préstamos"; edita la fecha con el préstamo prestado y verifica el cambio; intenta cargar una fecha pasada y recibe el error; verifica que un préstamo devuelto ya no permite editarla y que las nuevas solicitudes no traen plazo automático.

## Dudas abiertas
- [NECESITA ACLARACIÓN] ¿La fecha límite es obligatoria al confirmar la entrega o puede quedar sin definir?
- [NECESITA ACLARACIÓN] ¿Existe un tope máximo de días hacia adelante para la fecha límite?
- [NECESITA ACLARACIÓN] ¿Se debe detectar el atraso automáticamente al vencer la fecha, además del marcado manual del admin?
