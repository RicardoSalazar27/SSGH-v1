<?php

namespace Model;

class Pago extends ActiveRecord {

    public static $tabla = 'Pagos';
    public static $columnasDB = ['id_pago', 'reservacion_id', 'producto_id', 'monto', 'fecha_pago', 'tipo_pago', 'descripcion', 'estado'];

    public $id_pago;
    public $reservacion_id;
    public $producto_id;
    public $monto;
    public $fecha_pago;
    public $tipo_pago;
    public $descripcion;
    public $estado;

    public function __construct($args = []) {
        $this->id_pago = $args['id_pago'] ?? '';
        $this->reservacion_id = $args['reservacion_id'] ?? '';
        $this->producto_id = $args['producto_id'] ?? '';
        $this->monto = $args['monto'] ?? 0;
        $this->fecha_pago = $args['fecha_pago'] ?? '';
        $this->tipo_pago = $args['tipo_pago'] ?? 'Huespéd';
        $this->descripcion = $args['descripcion'] ?? 'sin descripcion';
        $this->estado = $args['estado'] ?? 1;
    }

    // public static function insertarVentasYActualizarStock($datos) {
    //     $nombreProcedimiento = "insertar_ventas_y_actualizar_stock";

    //     $params = [
    //         json_encode($datos['ventas']),
    //         json_encode($datos['productos'])
    //     ];

    //     return self::ejecutarProcedimiento($nombreProcedimiento, $params);
    // }
}
