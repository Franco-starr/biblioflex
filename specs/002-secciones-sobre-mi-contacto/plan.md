# Plan 002 — Secciones públicas "Sobre mí" y "Contacto"

## Enfoque
Una tabla chica de contenido por clave, un modelo, dos páginas públicas y un editor en el admin que reutiliza el patrón de autenticación existente.

## Pasos

1. **Base de datos** — archivo `sql/migracion_contenido.sql` y actualización del dump inicial:
   ```sql
   CREATE TABLE pagina_contenido (
     clave VARCHAR(50) NOT NULL,
     titulo VARCHAR(150) NOT NULL,
     contenido TEXT NOT NULL,
     actualizado_en TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     PRIMARY KEY (clave)
   );

   INSERT INTO pagina_contenido (clave, titulo, contenido) VALUES
     ('sobre-mi', 'Sobre mí', '[NECESITA ACLARACIÓN: texto inicial]'),
     ('contacto', 'Contacto', '[NECESITA ACLARACIÓN: datos de contacto iniciales]');
   ```
2. **Modelo** `models/Contenido.php` (extiende `ActiveRecord`): propiedades `clave`, `titulo`, `contenido`, `actualizado_en`; `$columnasDB` acorde; `validar()` que exija título y contenido no vacíos; método estático `porClave($clave)` (escapar con `escape_string`) porque `find()` solo busca por `id`.
3. **Páginas públicas** `public/sobre-mi.php` y `public/contacto.php`:
   - `require_once includes/app.php`, `incluirTemplate('header')` y `footer`.
   - Obtener registro con `Contenido::porClave(...)`.
   - Render con `s()` y `nl2br()` para conservar saltos de línea; los enlaces se muestran como texto (RF-7).
4. **Admin** `admin/contenido/index.php` (listado de las dos secciones con acceso a editar) y `admin/contenido/editar.php?clave=...`:
   - `estaAutenticado()` + `esAdmin()` al inicio, igual que el resto de `admin/`.
   - Formulario con `textarea` para el contenido; validar vacío antes de guardar (RF-6).
   - Redirección con código de resultado y mensaje en `mostrarNotificacion()`.
5. **Navegación** `includes/templates/navbar.php`: agregar "Sobre mí" y "Contacto" en el bloque visible para visitantes (fuera del condicional `estaLogueado()`). Íconos acordes al estilo actual.
6. **Panel admin** `admin/index.php`: agregar tarjeta "Contenido" en `dashboard-grid`.
7. **Mensajes** `includes/funciones.php`: extender `mostrarNotificacion()` con los códigos nuevos.

## Criterios de verificación
- Visitante ve ambas páginas y llega desde el navbar, también en móvil.
- Admin edita, guarda y ve el cambio reflejado en la página pública.
- Contenido vacío o usuario sin rol admin: acceso/guardado denegado con mensaje.
- Texto con `<script>` o comillas: se muestra como texto, no se ejecuta.

## Riesgos
- Doble escapado al combinar `s()` con `nl2br()` (usar `nl2br(s($texto))`, no al revés).
- `sincronizar()` de `ActiveRecord` ignora valores `null`; el editor debe enviar strings.
- La clave es la PK: no permite cambiar el identificador de la sección desde la UI (deseable).

## Pendiente para completar el contenido inicial
Texto de "Sobre mí" y datos de contacto (correo, teléfono o redes).
