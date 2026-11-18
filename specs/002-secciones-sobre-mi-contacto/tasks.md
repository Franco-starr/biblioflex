# Tasks 002 — Secciones públicas "Sobre mí" y "Contacto"

[Spec](spec.md) · [Plan](plan.md) · [Plan general](../plan-implementacion.md)

Cada tarea indica los RF que cubre. Marcar `[x]` solo cuando la verificación pase.

## Fase 1 — Datos y modelo

- [ ] T-001 Crear `sql/migracion_contenido.sql` con la tabla `pagina_contenido`. *(RF-1, RF-2)*
- [ ] T-002 Cargar las filas iniciales `sobre-mi` y `contacto` con el texto definido. *(RF-1, RF-2)* **[BLOQUEADO: texto inicial pendiente]**
- [ ] T-003 Crear `models/Contenido.php` extendiendo `ActiveRecord` con sus propiedades y `$columnasDB`. *(RF-3)*
- [ ] T-004 Implementar `validar()` que exija título y contenido no vacíos. *(RF-6)*
- [ ] T-005 Implementar `porClave($clave)` con escape del valor ( `find()` solo busca por id ). *(RF-1, RF-2)*

## Fase 2 — Páginas públicas

- [ ] T-006 Crear `public/sobre-mi.php` con header, navbar y footer. *(RF-1)*
- [ ] T-007 Crear `public/contacto.php` con header, navbar y footer. *(RF-2)*
- [ ] T-008 Renderizar con `nl2br(s($contenido))` para conservar saltos de línea sin interpretar HTML. *(RF-7)*
- [ ] T-009 Mostrar un mensaje alternativo si la clave no existe o el contenido está vacío. *(RF-1, RF-2)*

## Fase 3 — Edición en el admin

- [ ] T-010 Crear `admin/contenido/index.php` con `estaAutenticado()` y `esAdmin()`. *(RF-5)*
- [ ] T-011 Crear `admin/contenido/editar.php?clave=...` con `textarea` y validación. *(RF-3, RF-6)*
- [ ] T-012 Mostrar el contenido vigente al abrir el editor. *(RF-8)*
- [ ] T-013 Redireccionar con código de resultado después de guardar. *(RF-3)*
- [ ] T-014 Extender `mostrarNotificacion()` con los códigos nuevos. *(RF-3)*

## Fase 4 — Navegación

- [ ] T-015 Agregar "Sobre mí" y "Contacto" al navbar en el bloque visible para visitantes. *(RF-4)*
- [ ] T-016 Agregar enlaces en el footer. *(RF-4)*
- [ ] T-017 Agregar tarjeta "Contenido" en `admin/index.php`. *(RF-3)*
- [ ] T-018 Verificar enlaces y layout en móvil. *(RF-4)*

## Fase 5 — Verificación final

- [ ] T-019 Verificar que un admin edite y el cambio se refleje en la página pública. *(RF-3)*
- [ ] T-020 Verificar que un usuario sin rol admin no pueda acceder ni guardar. *(RF-5)*
- [ ] T-021 Verificar rechazo de contenido vacío con mensaje. *(RF-6)*
- [ ] T-022 Verificar que HTML o scripts cargados se muestren como texto. *(RF-7)*
- [ ] T-023 Demo manual de las dos secciones en escritorio y móvil.

## Bloqueantes

- T-002 (texto inicial) antes de dejar la spec lista para producción.
- Definir qué datos de contacto se muestran antes de T-002.
