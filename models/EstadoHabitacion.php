<?php

namespace Model;

class EstadoHabitacion extends ActiveRecord{

    public static $tabla = 'EstadoHabitacion';
    public static $columnasDB = ['id', 'nombre', 'descripcion', 'color', 'icono'];

    public $id;
    public $nombre;
    public $descripcion;
    public $color;
    public $icono;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->color = $args['color'] ?? '';
        $this->icono = $args['icono'] ?? '';
    }
}