<?php

namespace Model;

class reporteReservas extends ActiveRecord {

    public static $tabla = 'Reservas';
    public static $columnasDB = [
        'ID_reserva', 'descuento_aplicado', 'cobro_extra', 'adelanto', 'penalidad',
        'precio_total', 'fecha_entrada', 'fecha_salida'
    ];

    public $ID_reserva;
    public $descuento_aplicado;
    public $cobro_extra;
    public $adelanto;
    public $penalidad;
    public $precio_total;
    public $fecha_entrada;
    public $fecha_salida;

    // Constructor
    public function __construct($args = []) {
        $this->ID_reserva = $args['ID_reserva'] ?? null;
        $this->descuento_aplicado = $args['descuento_aplicado'] ?? 0;
        $this->cobro_extra = $args['cobro_extra'] ?? 0;
        $this->adelanto = $args['adelanto'] ?? 0;
        $this->penalidad = $args['penalidad'] ?? 0;
        $this->precio_total = $args['precio_total'] ?? 0;
        $this->fecha_entrada = $args['fecha_entrada'] ?? '';
        $this->fecha_salida = $args['fecha_salida'] ?? '';
    }

    // Función para obtener reservas por fecha y usuario
    public static function obtenerReservasPorFechaYUsuario($idUsuario, $fecha) {
        $query = "
            SELECT
                r.ID_reserva AS 'No.Reserva',
                GROUP_CONCAT(DISTINCT h.numero) AS Habitaciones,
                ANY_VALUE(r.descuento_aplicado) AS Descuento,
                ANY_VALUE(r.cobro_extra) AS Cobro_Extra,
                ANY_VALUE(r.adelanto) AS Adelanto,
                ANY_VALUE(r.penalidad) AS Penalidad,
                SUM(CASE WHEN p.producto_id IS NOT NULL THEN p.monto ELSE 0 END) AS `Ventas/Servicios`,
                (
                    (ANY_VALUE(r.precio_total) + ANY_VALUE(r.cobro_extra) - ANY_VALUE(r.descuento_aplicado) + ANY_VALUE(r.penalidad))
                    + SUM(CASE WHEN p.producto_id IS NOT NULL THEN p.monto ELSE 0 END)
                ) AS Total,
                CASE
                    -- Si tiene penalidad asignada, o si ya pasó la fecha de salida y tiene penalidad
                    WHEN ANY_VALUE(r.penalidad) > 0 THEN 'Sí'
                    WHEN DATE(r.fecha_salida) < CURDATE() AND ANY_VALUE(r.penalidad) > 0 THEN 'Sí'
                    -- Si es hoy y ya pasó la hora de salida, debe marcar Sí aunque no tenga penalidad
                    WHEN DATE(r.fecha_salida) = CURDATE() AND NOW() > r.fecha_salida THEN 'Sí'
                    ELSE 'No'
                END AS `Tiempo Rebasado`
            FROM
                Reservas r
            LEFT JOIN Reservas_Habitaciones rh ON rh.ID_reserva = r.ID_reserva
            LEFT JOIN Habitaciones h ON h.id = rh.ID_habitacion
            LEFT JOIN Pagos p ON p.reservacion_id = r.ID_reserva
            WHERE
                (DATE(r.fecha_salida) = '$fecha' OR DATE(r.fecha_salida) = CURDATE()) 
                AND r.ID_usuario = '$idUsuario'
            GROUP BY
                r.ID_reserva;
        ";

        // Ejecutar la consulta SQL
        return self::consultarSQL($query);
    }
}

?>
