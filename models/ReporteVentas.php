<?php

namespace Model;

class ReporteVentas extends ActiveRecord {

    public static $tabla = 'Pagos';
    public static $columnasDB = [
        'Identificador', 'Tipo', 'Habitacion', 'Articulo',
        'Precio_Unitario', 'Cantidad', 'Total', 'Hora', 'Responsable'
    ];

    public $Identificador;
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
        $this->Tipo = $args['Tipo'] ?? '';
        $this->Habitacion = $args['Habitacion'] ?? '';
        $this->Articulo = $args['Articulo'] ?? '';
        $this->Precio_Unitario = $args['Precio_Unitario'] ?? 0;
        $this->Cantidad = $args['Cantidad'] ?? 1;
        $this->Total = $args['Total'] ?? 0;
        $this->Hora = $args['Hora'] ?? '';
        $this->Responsable = $args['Responsable'] ?? '';
    }    

    public static function obtenerVentasPorFechaYUsuario($idUsuario, $fecha) {
        $query = "
            SELECT 
                p.id_pago AS Identificador,
                p.tipo_pago AS Tipo,
                GROUP_CONCAT(DISTINCT h.numero) AS Habitacion,
                p.descripcion AS Articulo,
                prod.precio AS Precio_Unitario,
                ROUND(ABS(p.monto) / prod.precio) AS Cantidad,
                ABS(p.monto) AS Total,
                TIME(p.fecha_pago) AS Hora,
                COALESCE(u.nombre, 'Público') AS Responsable
            FROM 
                Pagos p
            LEFT JOIN Reservas r ON r.ID_reserva = p.reservacion_id
            LEFT JOIN Reservas_Habitaciones rh ON rh.ID_reserva = r.ID_reserva
            LEFT JOIN Habitaciones h ON h.id = rh.ID_habitacion
            LEFT JOIN Usuarios u ON u.id = r.ID_usuario
            LEFT JOIN Productos prod ON prod.id = p.producto_id
            WHERE 
                DATE(p.fecha_pago) = '$fecha'
                AND p.producto_id IS NOT NULL
                AND (
                    (p.tipo_pago = 'Huésped' AND r.ID_usuario = '$idUsuario')
                    OR p.tipo_pago = 'Publico'
                )
            GROUP BY 
                p.id_pago
            ORDER BY 
                p.fecha_pago ASC
        ";
        return self::consultarSQL($query);
    }        
}