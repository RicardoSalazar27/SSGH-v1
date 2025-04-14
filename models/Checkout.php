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
        'descuento_aplicado'
    ];

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

        return array_shift(self::consultarSQL($query));
    }
}
?>
