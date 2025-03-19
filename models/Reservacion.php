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
        'documento_identidad',  // Agregar documento de identidad
        'telefono',             // Agregar teléfono
        'direccion',            // Agregar dirección
        'correo',               // Agregar correo
        'habitaciones',         // Aquí se almacenará los números de las habitaciones con tipo de cama
        'ID_habitacion',        // Se agrega la columna para los IDs de las habitaciones
        'estado_nombre',        // Nombre del estado
        'estado_descripcion',   // Descripción del estado
        'estado_color',          // Color del estado
        'observaciones'
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
    public $documento_identidad;  // Agregar propiedad para documento de identidad
    public $telefono;             // Agregar propiedad para teléfono
    public $direccion;            // Agregar propiedad para dirección
    public $correo;               // Agregar propiedad para correo
    public $habitaciones;         // Nombres de las habitaciones con tipo de cama
    public $ID_habitacion;        // IDs de las habitaciones
    public $estado_nombre;        // Nombre del estado
    public $estado_descripcion;   // Descripción del estado
    public $estado_color;         // Color del estado
    public $observaciones;


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
        $this->documento_identidad = $args['documento_identidad'] ?? '';  // Inicializar documento de identidad
        $this->telefono = $args['telefono'] ?? '';                         // Inicializar teléfono
        $this->direccion = $args['direccion'] ?? '';                       // Inicializar dirección
        $this->correo = $args['correo'] ?? '';                             // Inicializar correo
        $this->habitaciones = $args['habitaciones'] ?? ''; // Números de las habitaciones con tipo de cama
        $this->ID_habitacion = $args['ID_habitacion'] ?? ''; // IDs de las habitaciones
        $this->estado_nombre = $args['estado_nombre'] ?? ''; // Nombre del estado
        $this->estado_descripcion = $args['estado_descripcion'] ?? ''; // Descripción del estado
        $this->estado_color = $args['estado_color'] ?? ''; // Color del estado
        $this->observaciones = $args['observaciones'] ?? 'sin observaciones';
    }        

    // Función para obtener las reservas con las habitaciones asociadas
    public static function obtenerReservasConHabitaciones() {
        // Consulta SQL para obtener las reservas, las habitaciones asociadas, y el tipo de cama de las habitaciones
        $query = "
            SELECT 
                r.ID_reserva,
                r.ID_usuario,
                r.ID_cliente,
                r.fecha_entrada,
                r.fecha_salida,
                r.ID_estado,
                r.precio_total,
                r.precio_pendiente,
                c.nombre AS cliente_nombre,
                GROUP_CONCAT(CONCAT(h.numero, ' - ', ca.tipo_cama) ORDER BY h.numero) AS habitaciones, -- Números de las habitaciones con tipo de cama
                GROUP_CONCAT(h.id ORDER BY h.numero) AS ID_habitacion,      -- Obtener los IDs de las habitaciones
                e.nombre AS estado_nombre,                                   -- Nombre del estado
                e.descripcion AS estado_descripcion,                         -- Descripción del estado
                e.color AS estado_color                                      -- Color del estado
            FROM Reservas r
            LEFT JOIN Reservas_Habitaciones rh ON r.ID_reserva = rh.ID_reserva
            LEFT JOIN Habitaciones h ON rh.ID_habitacion = h.id
            LEFT JOIN Categoria ca ON h.id_categoria = ca.id  -- Obtener el tipo de cama desde la tabla Categoria
            LEFT JOIN Clientes c ON r.ID_cliente = c.id
            LEFT JOIN Estado_Reservaciones e ON r.ID_estado = e.ID_estado   -- Unir con la tabla de estado
            GROUP BY r.ID_reserva, c.nombre, e.nombre, e.descripcion, e.color
            ORDER BY r.ID_reserva;
        ";
        
        // Ejecutar la consulta SQL y devolver los resultados
        return self::consultarSQL($query);
    }

    public static function obtenerReservaConHabitaciones($id) {
        // Consulta SQL para obtener una reserva específica junto con sus habitaciones y detalles adicionales
        $query = "
            SELECT 
                r.ID_reserva,
                r.ID_usuario,
                r.ID_cliente,
                r.fecha_entrada,
                r.fecha_salida,
                r.ID_estado,  -- Traer el ID del estado de la reservación
                r.precio_total,
                r.precio_pendiente,
                r.observaciones,
                c.nombre AS cliente_nombre,
                c.apellidos AS cliente_apellidos,  -- Agregar apellidos del cliente
                c.documento_identidad,  -- Agregar documento de identidad del cliente
                c.telefono,             -- Agregar teléfono del cliente
                c.direccion,            -- Agregar dirección del cliente
                c.correo,               -- Agregar correo del cliente
                GROUP_CONCAT(CONCAT(h.numero, ' - ', ca.tipo_cama) ORDER BY h.numero) AS habitaciones, -- Números de las habitaciones con tipo de cama
                GROUP_CONCAT(h.id ORDER BY h.numero) AS ID_habitacion,      -- Obtener los IDs de las habitaciones
                e.ID_estado AS estado_id,                                   -- ID del estado desde la tabla Estado_Reservaciones
                e.nombre AS estado_nombre,                                  -- Nombre del estado
                e.descripcion AS estado_descripcion,                        -- Descripción del estado
                e.color AS estado_color                                     -- Color del estado
            FROM Reservas r
            LEFT JOIN Reservas_Habitaciones rh ON r.ID_reserva = rh.ID_reserva
            LEFT JOIN Habitaciones h ON rh.ID_habitacion = h.id
            LEFT JOIN Categoria ca ON h.id_categoria = ca.id  -- Obtener el tipo de cama desde la tabla Categoria
            LEFT JOIN Clientes c ON r.ID_cliente = c.id
            LEFT JOIN Estado_Reservaciones e ON r.ID_estado = e.ID_estado   -- Unir con la tabla de estado
            WHERE r.ID_reserva = '$id'  -- Filtro para obtener solo la reserva con el ID especificado
            GROUP BY r.ID_reserva, c.nombre, c.apellidos, c.documento_identidad, c.telefono, c.direccion, c.correo, e.ID_estado, e.nombre, e.descripcion, e.color
            ORDER BY r.ID_reserva;
        ";
        
        // Ejecutar la consulta SQL y devolver los resultados
        return self::consultarSQL($query);
    }    

}
