# Especificaciones de Biblioflex

Documentación viva del proyecto en formato SDD. Cada funcionalidad tiene tres archivos:

| Archivo | Responde | Contenido |
|---|---|---|
| `spec.md` | **QUÉ** y **POR QUÉ** | Requisitos funcionales en notación EARS, casos límite, fuera de alcance |
| `plan.md` | **CÓMO** | Enfoque técnico, pasos, archivos a tocar, riesgos |
| `tasks.md` | **EN QUÉ ORDEN** | Tareas chequeables con su RF de origen |

Regla: si algo no está en la spec, no se implementa. Si la spec y el plan difieren, manda la spec.

## Índice

| # | Funcionalidad | Spec | Plan | Tasks | Estado |
|---|---|---|---|---|---|
| 001 | Portadas completas y livianas en WebP | [spec](001-portadas-webp-sin-recorte/spec.md) | [plan](001-portadas-webp-sin-recorte/plan.md) | [tasks](001-portadas-webp-sin-recorte/tasks.md) | Aprobada · sin implementar |
| 002 | Secciones públicas "Sobre mí" y "Contacto" | [spec](002-secciones-sobre-mi-contacto/spec.md) | [plan](002-secciones-sobre-mi-contacto/plan.md) | [tasks](002-secciones-sobre-mi-contacto/tasks.md) | Aprobada · sin implementar |
| 003 | Solicitudes pendientes y confirmación del admin | [spec](003-estados-prestamo-admin/spec.md) | [plan](003-estados-prestamo-admin/plan.md) | [tasks](003-estados-prestamo-admin/tasks.md) | Aprobada · sin implementar |
| 004 | Fecha límite de devolución definida por el admin | [spec](004-fecha-limite-admin/spec.md) | [plan](004-fecha-limite-admin/plan.md) | [tasks](004-fecha-limite-admin/tasks.md) | Aprobada · sin implementar |

## Documento transversal

- [Plan de implementación](plan-implementacion.md): orden de ejecución, migración de base de datos compartida y decisiones pendientes.

## Orden de ejecución

1. **001** — independiente, corrige el bug visible de las portadas.
2. **003 + 004** — comparten migración de BD, modelo `Prestamo` y formulario de confirmación.
3. **002** — independiente, se deja para el final.

## Decisiones pendientes

Marcadas como `[NECESITA ACLARACIÓN]` en los specs y con valor por defecto en el plan:

1. Atraso automático al vencer la fecha (por defecto: solo manual).
2. Borrado de los JPG originales tras la migración (por defecto: conservar).
3. Fallback JPG para navegadores sin WebP (por defecto: no generar).
4. Cambios concretos de UI/UX além de las secciones (sin definir).
5. Datos de contacto a mostrar (por defecto: correo electrónico).
6. Fecha límite obligatoria y con tope de días (por defecto: obligatoria, sin tope).
