<?php
namespace Model;

class Reserva extends ActiveRecord {
    public static $tabla = 'Reservas';
    public static $columnasDB = ['ID_reserva', 'ID_usuario', 'ID_cliente', 'fecha_entrada', 'fecha_salida', 'ID_estado', 'precio_total', 'precio_pendiente'];

    public $ID_reserva;
    public $ID_usuario;
    public $ID_cliente;
    public $fecha_entrada;
    public $fecha_salida;
    public $ID_estado;
    public $precio_total;
    public $precio_pendiente;

    public function __construct($args = []) {
        $this->ID_reserva = $args['ID_reserva'] ?? null;
        $this->ID_usuario = $args['ID_usuario'] ?? '';
        $this->ID_cliente = $args['ID_cliente'] ?? 1;
        $this->fecha_entrada = $args['fecha_entrada'] ?? '';
        $this->fecha_salida = $args['fecha_salida'] ?? '';
        $this->ID_estado = $args['ID_estado'] ?? '';
        $this->precio_total = $args['precio_total'] ?? '';
        $this->precio_pendiente = $args['precio_pendiente'] ?? '';
    }

    public static function crearReservacion($datos) {
        // Definir el nombre del procedimiento almacenado
        $nombreProcedimiento = "crear_reservacion";

        // Preparar los parámetros
        $params = [
            $datos['cliente']['correo'], // correo
            $datos['cliente']['nombre'], // nombre
            $datos['cliente']['apellidos'], // apellidos
            $datos['cliente']['documento_identidad'], // documento
            $datos['cliente']['telefono'], // telefono
            $datos['cliente']['direccion'], // direccion
            $datos['fechas']['entrada'], // fecha entrada
            $datos['fechas']['salida'], // fecha salida
            $datos['pago']['totalPagar'], // total a pagar
            $datos['pago']['adelanto'], // adelanto
            $datos['pago']['descuento'], // descuento
            $datos['pago']['cobroExtra'], // cobro extra
            $datos['pago']['totalPagarOriginal'], // total original
            $datos['metodoPago'], // metodo de pago
            json_encode($datos['habitaciones']), // habitaciones (convertir a JSON)
            $datos['usuario_id'] // id del usuario
        ];

        //debuguear($params);

        // Llamar al procedimiento almacenado a través del método ejecutarProcedimiento
        return self::ejecutarProcedimiento($nombreProcedimiento, $params);
    }

    // Función para obtener las reservas con las habitaciones asociadas
//     public static function obtenerReservasConHabitaciones() {
//         // Consulta SQL para obtener las reservas y las habitaciones asociadas
//         // $query = " 
//         // SELECT 
//         //     r.ID_reserva,
//         //     r.ID_usuario,
//         //     r.ID_cliente,
//         //     r.fecha_entrada,
//         //     r.fecha_salida,
//         //     r.ID_estado,
//         //     r.precio_total,
//         //     r.precio_pendiente,
//         //     rh.ID_habitacion
//         // FROM Reservas r
//         // LEFT JOIN Reservas_Habitaciones rh ON r.ID_reserva = rh.ID_reserva
//         // ORDER BY r.ID_reserva;

//         // ";
        
//         $query = "
//     SELECT 
//         r.ID_reserva,
//         r.ID_usuario,
//         r.ID_cliente,
//         r.fecha_entrada,
//         r.fecha_salida,
//         r.ID_estado,
//         r.precio_total,
//         r.precio_pendiente,
//         c.nombre AS cliente_nombre,
//         GROUP_CONCAT(h.numero ORDER BY h.numero) AS habitaciones
//     FROM Reservas r
//     LEFT JOIN Reservas_Habitaciones rh ON r.ID_reserva = rh.ID_reserva
//     LEFT JOIN Habitaciones h ON rh.ID_habitacion = h.id
//     LEFT JOIN Clientes c ON r.ID_cliente = c.id
//     GROUP BY r.ID_reserva, c.nombre
//     ORDER BY r.ID_reserva;
// ";


//         // Ejecutar la consulta SQL y devolver los resultados
//         return self::consultarSQL($query);
//     }

}
