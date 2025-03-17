<?php

namespace Model;

class Reserva_Habitacion extends ActiveRecord{

    public static $tabla = 'Reservas_Habitaciones';
    public static $columnasDB = ['ID_reserva', 'ID_Habitacion'];

    public $ID_reserva;
    public $ID_Habitacion;

    public function __construct($args = []) {
        $this->ID_reserva = $args['ID_reserva'] ?? null;
        $this->ID_Habitacion = $args['ID_Habitacion'] ?? '';
    }    
}