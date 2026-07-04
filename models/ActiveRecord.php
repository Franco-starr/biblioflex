<?php

namespace App;

class ActiveRecord
{
    // Base de datos
    protected static $db;

    // Cada modelo las sobrescribirá
    protected static $tabla = '';
    protected static $columnasDB = [];

    // Errores de validación
    protected static $errores = [];

    // Definir la conexión a la base de datos
    public static function setDB($database) {
        self::$db = $database;
    }

    // Errores
    public static function getErrores() {
        return static::$errores;
    }

    // Obtener todos los registros de la tabla actual
    public static function all() {
        $query = "SELECT * FROM " . static::$tabla;
        return self::consultarSQL($query);
    }

    // Busca un registro por su id
    public static function find($id) {
        // Sanitizamos el ID para evitar inyecciones
        $id_sanitizado = self::$db->escape_string($id);
        $query = "SELECT * FROM " . static::$tabla . " WHERE id = {$id_sanitizado}";
        $resultado = self::consultarSQL($query);

        return array_shift($resultado);
    }

    // Ejecutar la consulta SQL y construir los objetos
    protected static function consultarSQL($query) {
        $resultado = self::$db->query($query);
        $array = [];

        while($registro = $resultado->fetch_assoc()) {
            $array[] = static::crearObjeto($registro);
        }

        $resultado->free();
        return $array;
    }

    // Mapear el arreglo asociativo de la BD a propiedades del Objeto
    protected static function crearObjeto($registro) {
        $objeto = new static; 

        foreach($registro as $key => $value) {
            if(property_exists($objeto, $key)) {
                $objeto->$key = $value;
            }
        }

        return $objeto;
    }

    // --- NUEVOS MÉTODOS HERRAMIENTA PARA EL CRUD ---

   // Une las columnasDB con los valores en memoria del objeto ($this)
    public function atributos() {
        $atributos = [];
        foreach(static::$columnasDB as $columna) {
            if($columna === 'id') continue; // Saltamos el ID porque la BD lo autoincrementa
            $atributos[$columna] = $this->$columna;
        }
        return $atributos;
    }

    // Sanitiza los datos antes de guardarlos en la base de datos
    public function sanitizarAtributos(){
        $atributos = $this->atributos();
        $sanitizado = [];
        foreach($atributos as $key => $value){
            $sanitizado[$key] = self::$db->escape_string($value);
        }
        return $sanitizado;
    }

    // --- MÉTODOS CRUD ---

    // Guarda o actualiza según corresponda
    public function guardar() {
        if(!is_null($this->id)) {
            return $this->actualizar();
        } else {
            return $this->crear();
        } 
    }

    // Crea un nuevo registro en la base de datos
    public function crear() {

        $atributos = $this->sanitizarAtributos();

        $query = "INSERT INTO " . static::$tabla . " (";
        $query .= join(', ', array_keys($atributos));
        $query .= ") VALUES ('";
        $query .= join("', '", array_values($atributos));
        $query .= "')";

        $resultado = self::$db->query($query);

        return $resultado;

    } 
    // Actualiza un registro existente
    public function actualizar() {
        $atributos = $this->sanitizarAtributos();
        $valores = [];
        foreach($atributos as $key => $value) {
            $valores[] = "{$key}='{$value}'";
        }
        $query = "UPDATE " . static::$tabla . " SET ";
        $query .= join(', ', $valores);
        $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "'";
        $resultado = self::$db->query($query);
        return $resultado;
    }

    // Elimina un registro
    public function eliminar() {
        $query = "DELETE FROM " . static::$tabla . " WHERE id = '" . self::$db->escape_string($this->id) . "'";
        $resultado = self::$db->query($query);
        return $resultado;
    }

    // Validación base
    public function validar() {
        static::$errores = [];
        return static::$errores;
    }

    public static function vaciarErrores() {
        static::$errores = [];
    }

    

}
