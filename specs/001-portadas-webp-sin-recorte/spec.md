# Spec 001 — Portadas completas y livianas en WebP

## Contexto y objetivo
Hoy las portadas se recortan en el momento de subirse y se guardan en JPG, con lo que se pierde parte de la imagen y se ocupa más espacio del necesario. El objetivo es que la portada se vea completa, que pese menos y que las imágenes ya cargadas pasen al nuevo formato sin dejar libros sin imagen.

## Usuarios / actores
- **Admin:** carga y reemplaza portadas al crear o editar libros, y ejecuta la migración de las imágenes existentes.
- **Visitante y usuario:** ven las portadas en el catálogo, el detalle del libro y el listado de préstamos.

## Historias de usuario
- H1: Como admin quiero subir una portada sin que se recorte para que se vea la imagen completa.
- H2: Como visitante quiero ver la portada entera para elegir el libro con información visual completa.
- H3: Como admin quiero que las portadas pesen poco para mejorar el rendimiento de carga.
- H4: Como admin quiero migrar las portadas ya cargadas para que todo el catálogo use el mismo formato.
- H5: Como admin quiero reemplazar una portada y ver la nueva en todas las vistas.

## Requisitos funcionales (criterios de aceptación en EARS)
- RF-1: CUANDO el admin sube una portada, EL SISTEMA conserva la totalidad de la imagen sin recortarla (salida: imagen completa).
- RF-2: CUANDO el admin sube una portada, EL SISTEMA la almacena en formato WebP.
- RF-3: CUANDO el sistema guarda una portada, EL SISTEMA respeta un peso máximo de 150 KB por archivo.
- RF-4: SI la portada no puede reducirse a 150 KB o menos, ENTONCES EL SISTEMA no guarda el libro e informa que la imagen es demasiado pesada.
- RF-5: SI el archivo subido no es una imagen válida, ENTONCES EL SISTEMA rechaza la carga e informa el motivo sin guardar el libro.
- RF-6: CUANDO se muestra una portada cuya proporción no coincide con el espacio disponible, EL SISTEMA muestra la imagen completa sin ocultar ninguna parte.
- RF-7: CUANDO se migran las portadas existentes, EL SISTEMA conserva la imagen visible de cada libro ya cargado (salida: ningún libro queda sin portada por la conversión).
- RF-8: SI una portada existente no puede convertirse, ENTONCES EL SISTEMA conserva el archivo original e informa qué portada falló.
- RF-9: CUANDO el admin reemplaza la portada de un libro, EL SISTEMA deja de mostrar la portada anterior en todas las vistas.

## Requisitos no funcionales
- Formato de salida: WebP.
- Peso máximo por portada: 150 KB.
- Idioma de mensajes e interfaces: español.

## Casos límite
- Imágenes ya en WebP, imágenes con transparencia e imágenes con proporciones muy verticales o muy anchas.
- Imágenes corruptas, archivos vacíos y errores de subida.
- Portadas ya migradas que se vuelven a procesar.
- Archivos de imagen sin libro asociado y libros sin imagen cargada.
- Dos portadas con el mismo contenido subidas en libros distintos.

## Fuera de alcance
- Recorte manual o selección del área visible por parte del admin.
- Varias imágenes por libro o galería de portadas.
- Conversión de archivos que no sean portadas de libros.
- Cambios de diseño de tarjetas, catálogo o detalle de libro (corresponde a la spec 002).

## Criterios de finalización
- Toda portada nueva y toda portada migrada se muestra completa, está en WebP y pesa como máximo 150 KB.
- Demo manual: subir una imagen vertical y una horizontal ancha, verificar catálogo y detalle sin recorte; reemplazar una portada y verificar que la anterior desaparece; verificar que los libros ya existentes siguen mostrando su portada tras la migración.

## Dudas abiertas
- [NECESITA ACLARACIÓN] ¿Se requiere una alternativa en JPG para navegadores que no soporten WebP?
- [NECESITA ACLARACIÓN] ¿Cuál debe ser el tamaño máximo en píxeles del lado mayor, dado que el tope de 150 KB no fija resolución?
- [NECESITA ACLARACIÓN] ¿Los archivos originales se eliminan después de la conversión o se conservan como respaldo?
