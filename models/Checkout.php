<?php 

namespace Model;

class Checkout extends ActiveRecord {
    public static $tabla = 'Reservas'; // tabla base
    public static $columnasDB = [
        'ID_reserva',
        'fecha_entrada',
        'fecha_salida',
        'noches',
        'tiempo_rebasado',
        'nombre',
        'apellidos',
        'documento_identidad',
        'telefono',
        'correo',
        'numeros_habitaciones',
        'categorias',
        'precios_base',
        'capacidades_maximas',
        'precio_total',
        'precio_pendiente',
        'adelanto',
        'cobro_extra',
        'descuento_aplicado',
        
        // Nuevos campos para ServicioAlCuarto
        'producto_nombre',
        'producto_precio',
        'producto_cantidad',
        'producto_monto',
        'producto_estado'
    ];

    // Atributos
    public $ID_reserva;
    public $fecha_entrada;
    public $fecha_salida;
    public $noches;
    public $tiempo_rebasado;
    public $nombre;
    public $apellidos;
    public $documento_identidad;
    public $telefono;
    public $correo;
    public $numeros_habitaciones;
    public $categorias;
    public $precios_base;
    public $capacidades_maximas;
    public $precio_total;
    public $precio_pendiente;
    public $adelanto;
    public $cobro_extra;
    public $descuento_aplicado;

    // Nuevas propiedades para ventas
    public $producto_nombre;
    public $producto_precio;
    public $producto_cantidad;
    public $producto_monto;
    public $producto_estado;

    public function __construct($args = []) {
        $this->ID_reserva = $args['ID_reserva'] ?? null;
        $this->fecha_entrada = $args['fecha_entrada'] ?? '';
        $this->fecha_salida = $args['fecha_salida'] ?? '';
        $this->noches = $args['noches'] ?? 0;
        $this->tiempo_rebasado = $args['tiempo_rebasado'] ?? 'Sin tiempo rebasado';
        $this->nombre = $args['nombre'] ?? '';
        $this->apellidos = $args['apellidos'] ?? '';
        $this->documento_identidad = $args['documento_identidad'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->correo = $args['correo'] ?? '';
        $this->numeros_habitaciones = $args['numeros_habitaciones'] ?? '';
        $this->categorias = $args['categorias'] ?? '';
        $this->precios_base = $args['precios_base'] ?? '';
        $this->capacidades_maximas = $args['capacidades_maximas'] ?? '';
        $this->precio_total = $args['precio_total'] ?? 0.00;
        $this->precio_pendiente = $args['precio_pendiente'] ?? 0.00;
        $this->adelanto = $args['adelanto'] ?? 0.00;
        $this->cobro_extra = $args['cobro_extra'] ?? 0.00;
        $this->descuento_aplicado = $args['descuento_aplicado'] ?? 0.00;

        // Inicializar datos de productos
        $this->producto_nombre = $args['producto_nombre'] ?? '';
        $this->producto_precio = $args['producto_precio'] ?? 0.00;
        $this->producto_cantidad = $args['producto_cantidad'] ?? 0;
        $this->producto_monto = $args['producto_monto'] ?? 0.00;
        $this->producto_estado = $args['producto_estado'] ?? 0;
    }

    public static function DatosHabitacionClienteHospedaje($id_reserva){
        $query = "
            SELECT 
                r.ID_reserva,
                r.fecha_entrada,
                r.fecha_salida,
                DATEDIFF(r.fecha_salida, r.fecha_entrada) AS noches,
                IF(NOW() > r.fecha_salida, TIMEDIFF(NOW(), r.fecha_salida), NULL) AS tiempo_rebasado,

                c.nombre,
                c.apellidos,
                c.documento_identidad,
                c.telefono,
                c.correo,

                GROUP_CONCAT(h.numero ORDER BY h.numero SEPARATOR ',') AS numeros_habitaciones,
                GROUP_CONCAT(cat.nombre ORDER BY h.numero SEPARATOR '-') AS categorias,
                GROUP_CONCAT(cat.precio_base ORDER BY h.numero SEPARATOR ',') AS precios_base,
                GROUP_CONCAT(cat.capacidad_maxima ORDER BY h.numero SEPARATOR ',') AS capacidades_maximas,

                r.precio_total,
                r.precio_pendiente,
                r.adelanto,
                r.cobro_extra,
                r.descuento_aplicado

            FROM Reservas r
            JOIN Clientes c ON r.ID_cliente = c.id
            JOIN Reservas_Habitaciones rh ON r.ID_reserva = rh.ID_reserva
            JOIN Habitaciones h ON rh.ID_habitacion = h.id
            JOIN Categoria cat ON h.id_categoria = cat.id

            WHERE r.ID_reserva = $id_reserva
            GROUP BY r.ID_reserva;
        ";

        return self::consultarSQL($query);
    }

    // public static function ServicioAlCuarto($id_reserva){
    //     $query = "
    //         SELECT 
    //             p.nombre AS producto_nombre,
    //             p.precio AS producto_precio,
    //             (pg.monto / p.precio) AS producto_cantidad,
    //             pg.monto AS producto_monto,
    //             pg.estado AS producto_estado
    //         FROM Pagos pg
    //         JOIN Productos p ON pg.producto_id = p.id
    //         WHERE pg.reservacion_id = $id_reserva
    //           AND pg.producto_id IS NOT NULL        
    //     ";

    //     return self::consultarSQL($query);
    // }
    public static function ServicioAlCuarto($id_reserva){
        $query = "
            SELECT 
                p.nombre AS producto_nombre,
                p.precio AS producto_precio,
                (pg.monto / p.precio) AS producto_cantidad,
                pg.monto AS producto_monto,
                pg.estado AS producto_estado
            FROM Pagos pg
            JOIN Productos p ON pg.producto_id = p.id
            WHERE pg.reservacion_id = $id_reserva
              AND pg.producto_id IS NOT NULL        
        ";

        return self::consultarSQL($query);
    }
}
?>
