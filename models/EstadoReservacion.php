<?php

namespace Model;

class EstadoReservacion extends ActiveRecord{

    public static $tabla = 'Estado_Reservaciones';
    public static $columnasDB = ['ID_estado', 'nombre', 'descripcion', 'correo', 'color'];

    public $ID_estado;
    public $nombre;
    public $descripcion;
    public $correo;
    public $color;

    public function __construct($args = []) {
        $this->ID_estado = $args['ID_estado'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->correo = $args['correo'] ?? '';
        $this->color = $args['color'] ?? '';
    }

    public static function todosEstadoReservacion(){
        $query = 'SELECT * FROM Estado_Reservaciones';
        return self::consultarSQL($query);
    }
}