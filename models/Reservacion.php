<?php

namespace Model;

class Reservacion extends ActiveRecord {
    protected static $tabla = 'Reservas';
    protected static $columnasDB = [
        'ID_reserva',
        'ID_usuario',
        'ID_cliente',
        'fecha_entrada',
        'fecha_salida',
        'ID_estado',
        'precio_total',
        'precio_pendiente',
        'cliente_nombre',
        'cliente_apellidos',
        'documento_identidad',
        'telefono',
        'direccion',
        'correo',
        'habitaciones',
        'ID_habitacion',
        'estado_nombre',
        'estado_descripcion',
        'estado_color',
        'observaciones',
        'adelanto',
        'cobro_extra',
        'descuento_aplicado',
        'tipo_descuento',
        'metodo_pago'
    ];

    public $ID_reserva;
    public $ID_usuario;
    public $ID_cliente;
    public $fecha_entrada;
    public $fecha_salida;
    public $ID_estado;
    public $precio_total;
    public $precio_pendiente;
    public $cliente_nombre;
    public $cliente_apellidos;
    public $documento_identidad;
    public $telefono;
    public $direccion;
    public $correo;
    public $habitaciones;
    public $ID_habitacion;
    public $estado_nombre;
    public $estado_descripcion;
    public $estado_color;
    public $observaciones;
    public $adelanto;
    public $cobro_extra;
    public $descuento_aplicado;
    public $tipo_descuento;
    public $metodo_pago;

    public function __construct($args = []) {
        $this->ID_reserva = $args['ID_reserva'] ?? null;
        $this->ID_usuario = $args['ID_usuario'] ?? null;
        $this->ID_cliente = $args['ID_cliente'] ?? null;
        $this->fecha_entrada = $args['fecha_entrada'] ?? '';
        $this->fecha_salida = $args['fecha_salida'] ?? '';
        $this->ID_estado = $args['ID_estado'] ?? null;
        $this->precio_total = $args['precio_total'] ?? 0.0;
        $this->precio_pendiente = $args['precio_pendiente'] ?? 0.0;
        $this->cliente_nombre = $args['cliente_nombre'] ?? '';
        $this->cliente_apellidos = $args['cliente_apellidos'] ?? '';
        $this->documento_identidad = $args['documento_identidad'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
        $this->correo = $args['correo'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->ID_habitacion = $args['ID_habitacion'] ?? '';
        $this->estado_nombre = $args['estado_nombre'] ?? '';
        $this->estado_descripcion = $args['estado_descripcion'] ?? '';
        $this->estado_color = $args['estado_color'] ?? '';
        $this->observaciones = $args['observaciones'] ?? 'sin observaciones';
        $this->adelanto = $args['adelanto'] ?? 0.0;
        $this->cobro_extra = $args['cobro_extra'] ?? 0.0;
        $this->descuento_aplicado = $args['descuento_aplicado'] ?? 0.0;
        $this->tipo_descuento = $args['tipo_descuento'] ?? 'MONTO';
        $this->metodo_pago = $args['metodo_pago'] ?? '';
    }        

    public static function obtenerReservasConHabitaciones() {
        $query = "
            SELECT 
                r.*, 
                r.metodo_pago,  -- Agregado 'metodo_pago'
                c.nombre AS cliente_nombre,
                c.apellidos AS cliente_apellidos,
                c.documento_identidad,
                c.telefono,
                c.direccion,
                c.correo,
                GROUP_CONCAT(CONCAT(h.numero, ' - ', ca.tipo_cama) ORDER BY h.numero) AS habitaciones,
                GROUP_CONCAT(h.id ORDER BY h.numero) AS ID_habitacion,
                e.nombre AS estado_nombre,
                e.descripcion AS estado_descripcion,
                e.color AS estado_color,
                r.adelanto,
                r.cobro_extra,
                r.descuento_aplicado,
                r.tipo_descuento
            FROM Reservas r
            LEFT JOIN Reservas_Habitaciones rh ON r.ID_reserva = rh.ID_reserva
            LEFT JOIN Habitaciones h ON rh.ID_habitacion = h.id
            LEFT JOIN Categoria ca ON h.id_categoria = ca.id
            LEFT JOIN Clientes c ON r.ID_cliente = c.id
            LEFT JOIN Estado_Reservaciones e ON r.ID_estado = e.ID_estado
            GROUP BY r.ID_reserva
            ORDER BY r.ID_reserva;
        ";
        return self::consultarSQL($query);
    }
    
    public static function obtenerReservaConHabitaciones($id) {
        $query = "
            SELECT 
                r.*, 
                r.metodo_pago,  -- Agregado 'metodo_pago'
                c.nombre AS cliente_nombre,
                c.apellidos AS cliente_apellidos,
                c.documento_identidad,
                c.telefono,
                c.direccion,
                c.correo,
                GROUP_CONCAT(CONCAT(h.numero, ' - ', ca.tipo_cama) ORDER BY h.numero) AS habitaciones,
                GROUP_CONCAT(h.id ORDER BY h.numero) AS ID_habitacion,
                e.nombre AS estado_nombre,
                e.descripcion AS estado_descripcion,
                e.color AS estado_color,
                r.adelanto,
                r.cobro_extra,
                r.descuento_aplicado,
                r.tipo_descuento
            FROM Reservas r
            LEFT JOIN Reservas_Habitaciones rh ON r.ID_reserva = rh.ID_reserva
            LEFT JOIN Habitaciones h ON rh.ID_habitacion = h.id
            LEFT JOIN Categoria ca ON h.id_categoria = ca.id
            LEFT JOIN Clientes c ON r.ID_cliente = c.id
            LEFT JOIN Estado_Reservaciones e ON r.ID_estado = e.ID_estado
            WHERE r.ID_reserva = '$id'
            GROUP BY r.ID_reserva
            ORDER BY r.ID_reserva;
        ";
        return self::consultarSQL($query);
    }
    
    //Se usa en RecepcionController, por si una habitacion tiene una reservacion en el dia de hoy
    public static function obtenerReservaPorHabitacionYFecha($idHabitacion) {
        $query = "
            SELECT 
                r.*, 
                c.nombre AS cliente_nombre,
                c.apellidos AS cliente_apellidos,
                c.documento_identidad,
                c.telefono,
                c.direccion,
                c.correo
            FROM Reservas r
            JOIN Reservas_Habitaciones rh ON r.ID_reserva = rh.ID_reserva
            JOIN Clientes c ON r.ID_cliente = c.id
            WHERE rh.ID_habitacion = '$idHabitacion'
            AND NOW() BETWEEN r.fecha_entrada AND r.fecha_salida;
        ";
    
        return self::consultarSQL($query);
    }    
}
