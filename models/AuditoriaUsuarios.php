<?php

namespace Model;

class AuditoriaUsuarios extends ActiveRecord {

    public static $tabla = 'VistaAuditoriaUsuarios';
    public static $columnasDB = ['nombre_usuario', 'telefono', 'accion', 'detalle', 'fecha', 'hora'];

    public $nombre_usuario;
    public $telefono;
    public $accion;
    public $detalle;
    public $fecha;
    public $hora;

    public function __construct($args = []) {
        $this->nombre_usuario = $args['nombre_usuario'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->accion = $args['accion'] ?? '';
        $this->detalle = $args['detalle'] ?? '';
        $this->fecha = $args['fecha'] ?? '';
        $this->hora = $args['hora'] ?? '';
    }

    public static function historial() {
        $query = 'SELECT * FROM VistaAuditoriaUsuarios ORDER BY fecha DESC, hora DESC';
        return self::consultarSQL($query);
    }
}
