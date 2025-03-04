<?php

namespace Model;

class Habitacion extends ActiveRecord{

    public static $tabla = 'Habitacion';
    public static $columnasDB = ['id', 'numero', 'id_estado_habitacion', 'id_categoria', 'estatus'];

    public $id;
    public $numero;
    public $id_estado_habitacion;
    public $id_categoria;
    public $estatus;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->numero = $args['numero'] ?? '';
        $this->id_estado_habitacion = $args['id_estado_habitacion'] ?? '';
        $this->id_estado_habitacion = $args['id_estado_habitacion'] ?? '';
        $this->estatus = $args['estatus'] ?? 0;
    }
}