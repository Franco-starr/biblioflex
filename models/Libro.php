<?php

namespace App;

class Libro extends ActiveRecord {
    // 1. Le decimos a ActiveRecord qué tabla y qué columnas usar
    protected static $tabla = 'libros';
    protected static $columnasDB = ['id', 'titulo', 'autor', 'editorial', 'anio_publicacion', 'isbn', 'categoria_id', 'stock', 'imagen', 'estado', 'creado_en'];

    // 2. Declaramos las propiedades del objeto (deben llamarse igual que en la BD)
    public $id;
    public $titulo;
    public $autor;
    public $editorial;
    public $anio_publicacion;
    public $isbn;
    public $categoria_id;
    public $stock;
    public $imagen;
    public $estado;
    public $creado_en;

    // 3. El constructor (recibe los datos del formulario $_POST)
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->titulo = $args['titulo'] ?? '';
        $this->autor = $args['autor'] ?? '';
        $this->editorial = $args['editorial'] ?? '';
        $this->anio_publicacion = $args['anio_publicacion'] ?? date('Y');
        $this->isbn = $args['isbn'] ?? '';
        $this->categoria_id = $args['categoria_id'] ?? null;
        $this->stock = $args['stock'] ?? 0;
        $this->imagen = $args['imagen'] ?? '';
        $this->estado = $args['estado'] ?? '';
        $this->creado_en = $args['creado_en'] ?? date('Y-m-d H:i:s');
    }

    // 4. Trasladamos las validaciones que tenías en tu archivo viejo
    public function validar() {
        // Al llamar a parent::validar() limpiamos los errores previos
        parent::validar(); 

        if(!$this->titulo) { static::$errores[] = "Debes añadir un título."; }
        if(!$this->autor) { static::$errores[] = "Debes añadir un autor."; }
        if(!$this->anio_publicacion) { static::$errores[] = "Debes añadir el año de publicación."; }
        if(!$this->categoria_id) { static::$errores[] = "Selecciona una categoría válida."; }
        if($this->stock === '') { static::$errores[] = "El stock no puede estar vacío."; }
        
        return static::$errores;
    }

}
?>