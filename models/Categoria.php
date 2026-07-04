<?php

namespace App;

class Categoria extends ActiveRecord
{
    protected static $tabla = 'categoria';
    protected static $columnasDB = ['id', 'nombre', 'estado', 'creado_ed'];

    // 2. Declaramos las propiedades públicas
    public $id;
    public $nombre;
    public $estado;
    public $creado_ed;

    // 3. El constructor para inicializar el objeto o recibir datos de formularios
    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->estado = $args['estado'] ?? 1; // 1 por defecto (Activa)
        $this->creado_ed = $args['creado_ed'] ?? date('Y-m-d H:i:s');
    }

}
