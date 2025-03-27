<?php
namespace Model;
class Editar_Reservacion extends ActiveRecord {
    public static $tabla = 'Reservas';
    public static $columnasDB = [
        'ID_reserva','ID_cliente', 'fecha_entrada', 'fecha_salida', 'ID_estado', 
        'precio_total', 'precio_pendiente', 'adelanto', 'cobro_extra', 'descuento_aplicado', 
        'tipo_descuento', 'observaciones', 'metodo_pago' // Agregar el campo 'metodo_pago'
    ];

    public $ID_reserva;
    public $ID_cliente;
    public $fecha_entrada;
    public $fecha_salida;
    public $ID_estado;
    public $precio_total;
    public $precio_pendiente;
    public $adelanto;
    public $cobro_extra;
    public $descuento_aplicado;
    public $tipo_descuento;
    public $observaciones;
    public $metodo_pago; // Agregar la propiedad para 'metodo_pago'

    public function __construct($args = []) {
        $this->ID_reserva = $args['ID_reserva'] ?? null;
        $this->ID_cliente = $args['ID_cliente'] ?? 1;
        $this->fecha_entrada = $args['fecha_entrada'] ?? '';
        $this->fecha_salida = $args['fecha_salida'] ?? '';
        $this->ID_estado = $args['ID_estado'] ?? '';
        $this->precio_total = $args['precio_total'] ?? '';
        $this->precio_pendiente = $args['precio_pendiente'] ?? '';
        $this->adelanto = $args['adelanto'] ?? 0;
        $this->cobro_extra = $args['cobro_extra'] ?? 0;
        $this->descuento_aplicado = $args['descuento_aplicado'] ?? 0;
        $this->tipo_descuento = $args['tipo_descuento'] ?? '';
        $this->observaciones = $args['observaciones'] ?? 'sin observaciones';
        $this->metodo_pago = $args['metodo_pago'] ?? ''; // Inicializar 'metodo_pago'
    }

    public static function editarReservacion($datos) {
        $nombreProcedimiento = "actualizar_reservacion";
        //debuguear($datos['pago']); // Para ver si 'metodo_pago' existe
        $params = [
            $datos['ID_reserva'],
            $datos['cliente']['nombre'],
            $datos['cliente']['apellidos'],
            $datos['cliente']['correo'],
            $datos['cliente']['documento_identidad'],
            $datos['cliente']['telefono'],
            $datos['cliente']['direccion'],
            $datos['fechas']['entrada'],
            $datos['fechas']['salida'],
            $datos['pago']['totalPagarOriginal'],
            $datos['pago']['totalPagar'],
            $datos['pago']['adelanto'],
            $datos['pago']['cobroExtra'],
            $datos['pago']['descuento'],
            $datos['pago']['tipoDescuento'],
            $datos['pago']['metodo_pago'], // Aquí pasamos el 'metodo_pago'
            $datos['observaciones'],
            $datos['ID_estado'],
            json_encode($datos['habitaciones'])
        ];

        return self::ejecutarProcedimiento($nombreProcedimiento, $params);
    }
}
