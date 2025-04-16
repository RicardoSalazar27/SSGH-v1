<?php

namespace Model;

class TerminarReservacion extends ActiveRecord {
    protected static $tabla = 'Reservas';
    protected static $columnasDB = [
        'id_reserva',
        'penalidad',
    ];

    public $id_reserva;
    public $penalidad;

    public function __construct($args = []) {
        $this->id_reserva = $args['id_reserva'] ?? null;
        $this->penalidad = $args['penalidad'] ?? '';
    }

    // 🔹 Método para ejecutar el procedimiento de terminar reservación
    public static function terminarReserva($id_reserva, $penalidad = 0.00) {
        $nombreProcedimiento = "terminar_reservacion";
        $params = [
            $id_reserva,
            $penalidad
        ];
        return self::ejecutarProcedimiento($nombreProcedimiento, $params);
    }
}
