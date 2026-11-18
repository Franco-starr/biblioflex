# Spec 002 — Secciones públicas "Sobre mí" y "Contacto"

## Contexto y objetivo
El sitio no ofrece información sobre el proyecto ni formas de contacto. Se quieren dos secciones públicas cuyo contenido pueda ser cargado y modificado por el admin desde el panel, sin depender de cambios en el código, y que sean alcanzables desde la navegación.

## Usuarios / actores
- **Visitante:** consulta las secciones públicas.
- **Admin:** edita los textos y datos de contacto desde el panel de administración.

## Historias de usuario
- H1: Como visitante quiero leer "Sobre mí" para conocer quién sostiene la biblioteca.
- H2: Como visitante quiero ver "Contacto" para saber cómo comunicarme.
- H3: Como admin quiero editar ambos contenidos desde el panel para mantenerlos actualizados sin tocar código.
- H4: Como visitante quiero encontrar ambas secciones desde la navegación del sitio.

## Requisitos funcionales (criterios de aceptación en EARS)
- RF-1: EL SISTEMA muestra una sección pública "Sobre mí" con un texto definido por el admin.
- RF-2: EL SISTEMA muestra una sección pública "Contacto" con datos definidos por el admin.
- RF-3: CUANDO el admin guarda cambios en el contenido de las secciones, EL SISTEMA refleja el nuevo contenido en las páginas públicas.
- RF-4: EL SISTEMA muestra enlaces a "Sobre mí" y "Contacto" en la navegación disponible para visitantes.
- RF-5: SI una persona sin rol de admin intenta acceder a la edición de las secciones, ENTONCES EL SISTEMA no le permite modificar el contenido.
- RF-6: SI el admin intenta guardar un contenido vacío, ENTONCES EL SISTEMA rechaza el cambio e informa qué campo quedó vacío.
- RF-7: CUANDO se muestran las secciones, EL SISTEMA muestra el contenido cargado por el admin como texto y no lo interpreta como código.
- RF-8: CUANDO el admin edita una sección, EL SISTEMA le muestra el contenido vigente antes de aplicar cambios.

## Requisitos no funcionales
- Idioma de los contenidos y mensajes: español.
- Las secciones utilizan los mismos estilos visuales que las páginas públicas existentes.
- Se mantienen accesibles desde la navegación en pantallas pequeñas.

## Casos límite
- Textos con saltos de línea, acentos y caracteres especiales.
- Contenidos muy largos o con enlaces.
- Sesión de admin expirada durante la edición.
- Acceso directo a la página de edición por parte de un usuario común.
- Guardado simultáneo de dos admins con versiones distintas del texto.

## Fuera de alcance
- Formulario de contacto con envío de correo o mensajería: solo se muestran datos de contacto.
- Contenido en varios idiomas.
- Imágenes o adjuntos dentro de las secciones.
- Rediseño general de colores, tipografías o pantallas existentes.

## Criterios de finalización
- Un visitante puede llegar a ambas secciones desde la navegación y ver el contenido vigente.
- Un admin puede cambiar ambos contenidos desde el panel y verificar el resultado en las páginas públicas.
- Un usuario sin rol de admin no puede modificar los contenidos.

## Dudas abiertas
- [NECESITA ACLARACIÓN] ¿Qué cambios concretos de UI/UX além de las secciones se quieren corregir, y en qué pantallas (catálogo, detalle de libro, préstamos o panel admin)?
- [NECESITA ACLARACIÓN] ¿Los datos de contacto son correo electrónico, teléfono, redes sociales o una combinación?
