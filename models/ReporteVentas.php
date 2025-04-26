<?php

namespace Model;

class ReporteVentas extends ActiveRecord {

    public static $tabla = 'Ventas';
    public static $columnasDB = [
        'Identificador', 'Reserva', 'Tipo', 'Habitacion', 'Articulo',
        'Precio_Unitario', 'Cantidad', 'Total', 'Hora', 'Responsable'
    ];

    public $Identificador;
    public $Reserva;
    public $Tipo;
    public $Habitacion;
    public $Articulo;
    public $Precio_Unitario;
    public $Cantidad;
    public $Total;
    public $Hora;
    public $Responsable;

    public function __construct($args = []) {
        $this->Identificador = $args['Identificador'] ?? null;
        $this->Reserva = $args['Reserva'] ?? null;
        $this->Tipo = $args['Tipo'] ?? '';
        $this->Habitacion = $args['Habitacion'] ?? '';
        $this->Articulo = $args['Articulo'] ?? '';
        $this->Precio_Unitario = $args['Precio_Unitario'] ?? 0;
        $this->Cantidad = $args['Cantidad'] ?? 1;
        $this->Total = $args['Total'] ?? 0;
        $this->Hora = $args['Hora'] ?? '';
        $this->Responsable = $args['Responsable'] ?? '';
    }

    public static function obtenerVentasPorFechaYUsuario($idUsuario = null, $fecha) {
        $condicionUsuario = $idUsuario ? "AND v.usuario_id = '$idUsuario'" : "";
    
        $query = "
            SELECT 
                v.id AS Identificador,
                v.reservacion_id AS Reserva,
                IF(v.reservacion_id IS NULL, 'Público', 'Huésped') AS Tipo,
                GROUP_CONCAT(DISTINCT h.numero) AS Habitacion,
                v.descripcion AS Articulo,
                ROUND(v.monto / v.cantidad, 2) AS Precio_Unitario,
                v.cantidad AS Cantidad,
                v.monto AS Total,
                TIME(v.fecha_venta) AS Hora,
                COALESCE(u.nombre, 'Público') AS Responsable
            FROM 
                Ventas v
            LEFT JOIN Reservas r ON r.ID_reserva = v.reservacion_id
            LEFT JOIN Reservas_Habitaciones rh ON rh.ID_reserva = r.ID_reserva
            LEFT JOIN Habitaciones h ON h.id = rh.ID_habitacion
            LEFT JOIN Usuarios u ON u.id = v.usuario_id
            WHERE 
                DATE(v.fecha_venta) = '$fecha'
                AND v.estado = 1
                $condicionUsuario
            GROUP BY 
                v.id
            ORDER BY 
                v.fecha_venta ASC
        ";
    
        return self::consultarSQL($query);
    }    
    
    public static function obtenerVentasPorMesYUsuario($mes, $anio, $usuarioId = null) {
        $condicionUsuario = $usuarioId ? "AND v.usuario_id = '$usuarioId'" : "";
    
        $query = "
            SELECT 
                v.id AS Identificador,
                v.reservacion_id AS Reserva,
                IF(v.reservacion_id IS NULL, 'Público', 'Huésped') AS Tipo,
                GROUP_CONCAT(DISTINCT h.numero) AS Habitacion,
                v.descripcion AS Articulo,
                ROUND(v.monto / v.cantidad, 2) AS Precio_Unitario,
                v.cantidad AS Cantidad,
                v.monto AS Total,
                TIME(v.fecha_venta) AS Hora,
                COALESCE(u.nombre, 'Público') AS Responsable
            FROM 
                Ventas v
            LEFT JOIN Reservas r ON r.ID_reserva = v.reservacion_id
            LEFT JOIN Reservas_Habitaciones rh ON rh.ID_reserva = r.ID_reserva
            LEFT JOIN Habitaciones h ON h.id = rh.ID_habitacion
            LEFT JOIN Usuarios u ON u.id = v.usuario_id
            WHERE 
                MONTH(v.fecha_venta) = '$mes'
                AND YEAR(v.fecha_venta) = '$anio'
                AND v.estado = 1
                $condicionUsuario
            GROUP BY 
                v.id
            ORDER BY 
                v.fecha_venta ASC
        ";
    
        return self::consultarSQL($query);
    }    
}
