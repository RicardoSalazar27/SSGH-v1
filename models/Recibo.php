<?php

namespace Model;

class Recibo extends ActiveRecord {

    public static $tabla = 'Reservas';
    public static $columnasDB = [
        'ID_reserva', 'cliente', 'telefono', 'habitaciones',
        'fecha_entrada', 'fecha_salida',
        'precio_total', 'cobro_extra', 'adelanto',
        'nombre', 'apellidos', 'correo',
        'numero', 'nivel', 'categoria','precio'
    ];

    public $ID_reserva;
    public $cliente;
    public $telefono;
    public $habitaciones;
    public $fecha_entrada;
    public $fecha_salida;

    public $precio_total;
    public $cobro_extra;
    public $adelanto;

    public $nombre;
    public $apellidos;
    public $correo;

    public $numero;
    public $nivel;
    public $categoria;
    public $precio;

    public function __construct($args = []) {
        $this->ID_reserva = $args['ID_reserva'] ?? null;
        $this->cliente = $args['cliente'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->habitaciones = $args['habitaciones'] ?? '';
        $this->fecha_entrada = $args['fecha_entrada'] ?? '';
        $this->fecha_salida = $args['fecha_salida'] ?? '';

        $this->precio_total = $args['precio_total'] ?? 0;
        $this->cobro_extra = $args['cobro_extra'] ?? 0;
        $this->adelanto = $args['adelanto'] ?? 0;

        $this->nombre = $args['nombre'] ?? '';
        $this->apellidos = $args['apellidos'] ?? '';
        $this->correo = $args['correo'] ?? '';
    }

    public static function tablaRecibos() {
        $query = "
            SELECT 
                r.ID_reserva,
                CONCAT(c.nombre, ' ', c.apellidos) AS cliente,
                c.telefono,
                GROUP_CONCAT(h.numero ORDER BY h.numero SEPARATOR ', ') AS habitaciones,
                r.fecha_entrada,
                r.fecha_salida
            FROM Reservas r
            JOIN Clientes c ON r.ID_cliente = c.id
            JOIN Reservas_Habitaciones rh ON r.ID_reserva = rh.ID_reserva
            JOIN Habitaciones h ON rh.ID_habitacion = h.id
            GROUP BY r.ID_reserva, c.nombre, c.apellidos, c.telefono, r.fecha_entrada, r.fecha_salida
            ORDER BY r.ID_reserva DESC
            LIMIT 30
        ";

        return self::consultarSQL($query);
    }

    public static function datosReserva($idReserva){
        $query = "
            SELECT
                ID_reserva,
                precio_total,
                cobro_extra,
                adelanto
            FROM
                Reservas
            WHERE
                ID_reserva = $idReserva
        ";

        return self::consultarSQL($query);
    }

    public static function datosHabitaciones($idReserva){
        $query = "
            SELECT
                h.numero,
                r.fecha_entrada,
                r.fecha_salida,
                n.nombre AS nivel,
                c.nombre AS categoria,
                c.precio_base AS precio,
                c.precio_base * DATEDIFF(r.fecha_salida, r.fecha_entrada) AS precio_total
            FROM
                Reservas_Habitaciones rh
            JOIN
                Habitaciones h ON rh.ID_habitacion = h.id
            JOIN
                Reservas r ON rh.ID_reserva = r.ID_reserva
            JOIN
                Niveles n ON h.id_nivel = n.id
            JOIN
                Categoria c ON h.id_categoria = c.id
            WHERE
                rh.ID_reserva = $idReserva
        ";

        return self::consultarSQL($query);
    }

    public static function datosCliente ($idReserva){
        $query = "
            SELECT
                c.nombre,
                c.apellidos,
                c.correo,
                c.telefono
            FROM
                Clientes c
            JOIN
                Reservas r ON r.ID_cliente = c.id
            WHERE
                r.ID_reserva = $idReserva;
        ";
        return self::consultarSQL($query);
    }
}
