<?php

namespace Model;

class ReporteReservas extends ActiveRecord {

    public static $tabla = 'Reservas';
    public static $columnasDB = [
        'No.Reserva', 'Habitaciones', 'Descuento', 'Cobro_Extra',
        'Adelanto', 'Penalidad', 'Ventas/Servicios', 'Precio_Total', 'Total', 'Tiempo Rebasado'
    ];

    public $No_Reserva;
    public $Habitaciones;
    public $Descuento;
    public $Cobro_Extra;
    public $Adelanto;
    public $Penalidad;
    public $Ventas_Servicios;
    public $Precio_Total;
    public $Total;
    public $Tiempo_Rebasado;

    public function __construct($args = []) {
        $this->No_Reserva = $args['No_Reserva'] ?? null;
        $this->Habitaciones = $args['Habitaciones'] ?? '';
        $this->Descuento = $args['Descuento'] ?? 0;
        $this->Cobro_Extra = $args['Cobro_Extra'] ?? 0;
        $this->Adelanto = $args['Adelanto'] ?? 0;
        $this->Penalidad = $args['Penalidad'] ?? 0;
        $this->Ventas_Servicios = $args['Ventas_Servicios'] ?? 0;
        $this->Precio_Total = $args['Precio_Total'] ?? 0;
        $this->Total = $args['Total'] ?? 0;
        $this->Tiempo_Rebasado = $args['Tiempo_Rebasado'] ?? 'No';
    }

    public static function obtenerReservasPorFechaYUsuario($idUsuario = null, $fecha) {
        $condicionUsuario = $idUsuario ? "AND r.ID_usuario = '$idUsuario'" : "";
    
        $query = "
            SELECT
                r.ID_reserva AS No_Reserva,
                GROUP_CONCAT(DISTINCT h.numero) AS Habitaciones,
                ANY_VALUE(r.descuento_aplicado) AS Descuento,
                ANY_VALUE(r.cobro_extra) AS Cobro_Extra,
                ANY_VALUE(r.adelanto) AS Adelanto,
                ANY_VALUE(r.penalidad) AS Penalidad,
                SUM(CASE WHEN v.producto_id IS NOT NULL THEN v.monto ELSE 0 END) AS Ventas_Servicios,
                ANY_VALUE(r.precio_total) AS Precio_Total,
                (
                    (ANY_VALUE(r.precio_total) + ANY_VALUE(r.cobro_extra) - ANY_VALUE(r.descuento_aplicado) + ANY_VALUE(r.penalidad))
                    + SUM(CASE WHEN v.producto_id IS NOT NULL THEN v.monto ELSE 0 END)
                ) AS Total,
                CASE
                    WHEN ANY_VALUE(r.penalidad) > 0 THEN 'Sí'
                    WHEN DATE(r.fecha_salida) < CURDATE() AND ANY_VALUE(r.penalidad) > 0 THEN 'Sí'
                    WHEN DATE(r.fecha_salida) = CURDATE() AND NOW() > r.fecha_salida THEN 'Sí'
                    ELSE 'No'
                END AS Tiempo_Rebasado
            FROM
                Reservas r
            LEFT JOIN Reservas_Habitaciones rh ON rh.ID_reserva = r.ID_reserva
            LEFT JOIN Habitaciones h ON h.id = rh.ID_habitacion
            LEFT JOIN Ventas v ON v.reservacion_id = r.ID_reserva AND v.estado = 1
            WHERE
                (DATE(r.fecha_salida) = '$fecha' OR DATE(r.fecha_salida) = CURDATE())
                $condicionUsuario
            GROUP BY
                r.ID_reserva;
        ";
    
        return self::consultarSQL($query);
    }    

    public static function obtenerReservasPorMesYUsuario($mes, $anio, $usuarioId = null) {
        $condicionUsuario = $usuarioId ? "AND r.ID_usuario = '$usuarioId'" : "";
    
        $query = "
            SELECT
                r.ID_reserva AS No_Reserva,
                GROUP_CONCAT(DISTINCT h.numero) AS Habitaciones,
                ANY_VALUE(r.descuento_aplicado) AS Descuento,
                ANY_VALUE(r.cobro_extra) AS Cobro_Extra,
                ANY_VALUE(r.adelanto) AS Adelanto,
                ANY_VALUE(r.penalidad) AS Penalidad,
                SUM(CASE WHEN v.producto_id IS NOT NULL THEN v.monto ELSE 0 END) AS Ventas_Servicios,
                ANY_VALUE(r.precio_total) AS Precio_Total,
                (
                    (ANY_VALUE(r.precio_total) + ANY_VALUE(r.cobro_extra) - ANY_VALUE(r.descuento_aplicado) + ANY_VALUE(r.penalidad))
                    + SUM(CASE WHEN v.producto_id IS NOT NULL THEN v.monto ELSE 0 END)
                ) AS Total,
                CASE
                    WHEN ANY_VALUE(r.penalidad) > 0 THEN 'Sí'
                    WHEN DATE(r.fecha_salida) < CURDATE() AND ANY_VALUE(r.penalidad) > 0 THEN 'Sí'
                    WHEN DATE(r.fecha_salida) = CURDATE() AND NOW() > r.fecha_salida THEN 'Sí'
                    ELSE 'No'
                END AS Tiempo_Rebasado
            FROM
                Reservas r
            LEFT JOIN Reservas_Habitaciones rh ON rh.ID_reserva = r.ID_reserva
            LEFT JOIN Habitaciones h ON h.id = rh.ID_habitacion
            LEFT JOIN Ventas v ON v.reservacion_id = r.ID_reserva AND v.estado = 1
            WHERE
                MONTH(r.fecha_salida) = '$mes'
                AND YEAR(r.fecha_salida) = '$anio'
                $condicionUsuario
            GROUP BY
                r.ID_reserva
        ";
        //debuguear($query);
    
        return self::consultarSQL($query);
    }    
}
