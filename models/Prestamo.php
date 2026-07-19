<?php

namespace App;

class Prestamo extends ActiveRecord {

    protected static $tabla = 'prestamo';
    protected static $columnasDB = ['id', 'usuario_id', 'libro_id', 'fecha_prestamo', 'fecha_estimada', 'fecha_devolucion', 'estado'];

    public $id;
    public $usuario_id;
    public $libro_id;
    public $fecha_prestamo;
    public $fecha_estimada;
    public $fecha_devolucion;
    public $estado;
    public $titulo;
    public $imagen;
    public $autor;
    public $nombre;
    public $apellido;


    public function __construct($args = []) 
    {
        $this->id = $args['id'] ?? null;
        $this->usuario_id = $args['usuario_id'] ?? null;
        $this->libro_id = $args['libro_id'] ?? null;
        $this->fecha_prestamo = $args['fecha_prestamo'] ?? null;
        $this->fecha_estimada = $args['fecha_estimada'] ?? null;
        $this->fecha_devolucion = $args['fecha_devolucion'] ?? null;
        $this->estado = $args['estado'] ?? 'prestado';
    }

    public function validar() {
        parent::validar(); 

        if(!$this->usuario_id) { static::$errores[] = "El ID del usuario es obligatorio."; }
        if(!$this->libro_id) { static::$errores[] = "El ID del libro es obligatorio."; }

        if(!empty($this->usuario_id) && !empty($this->libro_id)) {
            $prestamosActivos = self::where('libro_id', $this->libro_id);
            foreach($prestamosActivos as $p) {
                if($p->usuario_id == $this->usuario_id && $p->estado === 'prestado') {
                    static::$errores[] = "Ya tenés un préstamo activo de este libro.";
                    break;
                }
            }
        }

        return static::$errores;
    }

    public function prestarLibro() {
        $this->validar();
        if(!empty(static::$errores)) {
            return false;
        }

        $libro = Libro::find($this->libro_id);
        if(!$libro || $libro->stock <= 0) {
            static::$errores[] = "No hay stock disponible de este libro.";
            return false;
        }

        self::$db->begin_transaction();

        try {
            $this->fecha_prestamo = date('Y-m-d H:i:s');
            $this->fecha_estimada = date('Y-m-d', strtotime('+15 days'));
            $this->estado = 'prestado';

            $resultado = $this->crear();

            if(!$resultado) {
                throw new Exception("Error al crear el préstamo.");
            }

            $libro->stock = $libro->stock - 1;
            $resultadoStock = $libro->guardar();

            if(!$resultadoStock) {
                throw new Exception("Error al actualizar el stock.");
            }

            self::$db->commit();
            return true;

        } catch(Exception $e) {
            self::$db->rollback();
            static::$errores[] = $e->getMessage();
            return false;
        }
    }

    public static function prestamosPorUsuario($usuario_id) {
        $usuario_id = self::$db->escape_string($usuario_id);
        $query = "SELECT prestamo.*, libros.titulo, libros.imagen, libros.autor
                  FROM prestamo
                  INNER JOIN libros ON prestamo.libro_id = libros.id
                  WHERE prestamo.usuario_id = {$usuario_id}
                  ORDER BY prestamo.fecha_prestamo DESC";
        return self::consultarSQL($query);
    }

    public static function allConDetalle() {
        $query = "SELECT prestamo.*, libros.titulo, libros.imagen,
                         usuario.nombre, usuario.apellido
                  FROM prestamo
                  INNER JOIN libros ON prestamo.libro_id = libros.id
                  INNER JOIN usuario ON prestamo.usuario_id = usuario.id
                  ORDER BY prestamo.fecha_prestamo DESC";
        return self::consultarSQL($query);
    }

    public function devolverLibro() {
        if($this->estado !== 'prestado') {
            static::$errores[] = "Este préstamo ya fue devuelto.";
            return false;
        }

        $libro = Libro::find($this->libro_id);
        if(!$libro) {
            static::$errores[] = "No se encontró el libro asociado.";
            return false;
        }

        self::$db->begin_transaction();

        try {
            $this->fecha_devolucion = date('Y-m-d H:i:s');
            $this->estado = 'devuelto';

            $resultado = $this->guardar();
            if(!$resultado) {
                throw new Exception("Error al actualizar el préstamo.");
            }

            $libro->stock = $libro->stock + 1;
            $resultadoStock = $libro->guardar();
            if(!$resultadoStock) {
                throw new Exception("Error al actualizar el stock.");
            }

            self::$db->commit();
            return true;

        } catch(Exception $e) {
            self::$db->rollback();
            static::$errores[] = $e->getMessage();
            return false;
        }
    }



    
}