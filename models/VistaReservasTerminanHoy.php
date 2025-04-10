<?php

namespace Model;

class VistaReservasTerminanHoy extends ActiveRecord {
    protected static $tabla = 'Reservas';
    protected static $columnasDB = [
        'ID_reserva',
        'cliente_nombre',
        'habitacion_numero',  // Agrupado y concatenado
        'color_estado_habitacion',
        'icono_estado_habitacion',
        'nombre_estado_habitacion'
    ];

    public $ID_reserva;
    public $cliente_nombre;
    public $habitacion_numero;
    public $color_estado_habitacion;
    public $icono_estado_habitacion;
    public $nombre_estado_habitacion;

    public function __construct($args = []) {
        $this->ID_reserva = $args['ID_reserva'] ?? null;
        $this->cliente_nombre = $args['cliente_nombre'] ?? '';
        $this->habitacion_numero = $args['habitacion_numero'] ?? '';
        $this->color_estado_habitacion = $args['color_estado_habitacion'] ?? '';
        $this->icono_estado_habitacion = $args['icono_estado_habitacion'] ?? '';
        $this->nombre_estado_habitacion = $args['nombre_estado_habitacion'] ?? '';
    }
    // Método para obtener las reservas que terminan hoy desde la vista
    public static function ReservacionesTerminanHoy() {
        // Consulta SQL que usa la vista
        $query = "SELECT * FROM ReservacionesTerminanHoy";
        
        // Ejecutar la consulta a través del método consultarSQL
        return self::consultarSQL($query);
    }  
}