<?php

namespace Model;

class Habitacion extends ActiveRecord{

    public static $tabla = 'Habitaciones';
    public static $columnasDB = ['id', 'numero', 'id_estado_habitacion','id_nivel', 'id_categoria', 'estatus', 'detalles_personalizados'];

    public $id;
    public $numero;
    public $id_estado_habitacion;
    public $id_nivel;
    public $id_categoria;
    public $estatus;
    public $detalles_personalizados;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->numero = $args['numero'] ?? '';
        $this->id_estado_habitacion = $args['id_estado_habitacion'] ?? 1;
        $this->id_nivel = $args['id_nivel'] ?? '';
        $this->id_categoria = $args['id_categoria'] ?? '';
        $this->detalles_personalizados = $args['detalles_personalizados'] ?? '';
        $this->estatus = $args['estatus'] ?? 0;
    }
}