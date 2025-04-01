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

    public static function habitacionesDisponibles($fechaInicio, $fechaFin) {
        // $query = "SELECT h.id, h.numero, h.id_categoria, h.id_nivel, h.detalles_personalizados
        //   FROM Habitaciones h
        //   JOIN EstadoHabitacion eh ON h.id_estado_habitacion = eh.id
        //   LEFT JOIN Reservas_Habitaciones rh ON h.id = rh.ID_habitacion
        //   LEFT JOIN Reservas r ON rh.ID_reserva = r.ID_reserva 
        //       AND r.fecha_entrada < '$fechaFin'  
        //       AND r.fecha_salida > '$fechaInicio' 
        //       AND r.ID_estado != 4
        //   WHERE eh.id = 1  
        //   AND (rh.ID_habitacion IS NULL OR r.ID_reserva IS NULL)";

        // $query = "
        //             SELECT DISTINCT h.id, h.numero, h.id_categoria, h.id_nivel, h.detalles_personalizados
        //             FROM Habitaciones h
        //             JOIN EstadoHabitacion eh ON h.id_estado_habitacion = eh.id
        //             WHERE eh.id = 1
        //             AND NOT EXISTS (
        //                 SELECT 1
        //                 FROM Reservas_Habitaciones rh
        //                 JOIN Reservas r ON rh.ID_reserva = r.ID_reserva
        //                 WHERE rh.ID_habitacion = h.id
        //                 AND r.fecha_entrada < '$fechaFin'
        //                 AND r.fecha_salida > '$fechaInicio'
        //                 AND r.ID_estado != 4
        //             )
        //         ";
        $query = "
                    SELECT DISTINCT h.id, h.numero, h.id_categoria, h.id_nivel, h.detalles_personalizados
                    FROM Habitaciones h
                    JOIN EstadoHabitacion eh ON h.id_estado_habitacion = eh.id
                    AND NOT EXISTS (
                        SELECT 1
                        FROM Reservas_Habitaciones rh
                        JOIN Reservas r ON rh.ID_reserva = r.ID_reserva
                        WHERE rh.ID_habitacion = h.id
                        AND r.fecha_entrada < '$fechaFin'
                        AND r.fecha_salida > '$fechaInicio'
                    )
                ";
        //debuguear($query);
        //debuguear([$fechaInicio, $fechaFin]); // Ver los valores reales
    
        return self::consultarSQL($query, [$fechaInicio, $fechaFin]); // Parámetros en el orden correcto
    }    
}