<?php

namespace Model;

class Categoria extends ActiveRecord{

    public static $tabla = 'Categoria';
    public static $columnasDB = ['id', 'nombre', 'descripcion', 'estado'];

    public $id;
    public $nombre;
    public $descripcion;
    public $estado;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->estado = $args['estado'] ?? 0;
    }
}