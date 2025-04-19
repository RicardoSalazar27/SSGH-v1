<?php

namespace Model;

class Ventas extends ActiveRecord{

    public static $tabla = 'Ventas';
    public static $columnasDB = ['id', 'producto_id', 'cantidad', 'monto', 'descripcion', 'reservacion_id','usuario_id','estado','fecha_venta'];

    public $id;
    public $producto_id;
    public $cantidad;
    public $monto;
    public $descripcion;
    public $reservacion_id;
    public $usuario_id;
    public $estado;
    public $fecha_venta;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->producto_id = $args['producto_id'] ?? '';
        $this->cantidad = $args['cantidad'] ?? 1;
        $this->monto = $args['monto'] ?? '';
        $this->descripcion = $args['descripcion'] ?? '';
        $this->reservacion_id = $args['reservacion_id'] ?? null;
        $this->usuario_id = $args['usuario_id'] ?? '';
        $this->estado = $args['estado'] ?? 0;
        $this->fecha_venta = $args['fecha_venta'] ?? '';
    }

    public static function insertarVentasYActualizarStock($datos) {
        $nombreProcedimiento = "insertar_ventas_y_actualizar_stock";

        // $datos['ventas'] = array_map('limpiarClavesRecursivamente', $datos['ventas']);
        // $datos['productos'] = array_map('limpiarClavesRecursivamente', $datos['productos']);

        $params = [
            json_encode($datos['ventas']),
            json_encode($datos['productos'])
        ];
        //debuguear($params);
        return self::ejecutarProcedimiento($nombreProcedimiento, $params);
    }    
}