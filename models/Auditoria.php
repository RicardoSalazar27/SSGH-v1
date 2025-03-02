<?php

namespace Model;

class Auditoria extends ActiveRecord{

    public static $tabla = 'Auditoria';
    public static $columnasDB = ['id', 'id_usuario', 'accion', 'tabla_afectada' ,'id_registro_afectado', 'detalle', 'fecha_hora'];

    public $id;
    public $id_usuario;
    public $accion;
    public $tabla_afectada;
    public $id_registro_afectado;
    public $detalle;
    public $fecha_hora;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->id_usuario = $args['id_usuario'] ?? '';
        $this->accion = $args['accion'] ?? '';
        $this->tabla_afectada = $args['tabla_afectada'] ?? '';
        $this->id_registro_afectado = $args['id_registro_afectado'] ?? 'NULL';
        $this->detalle = $args['detalle'] ?? '';
        $this->fecha_hora = $args['fecha_hora'] ?? '';
    }
}