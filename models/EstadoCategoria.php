<?php

namespace Model;

class EstadoCategoria extends ActiveRecord{

    public static $tabla = 'Categoria';
    public static $columnasDB = ['id', 'nombre', 'capacidad_maxima', 'estado', 'tipo_cama','precio_base','servicios_incluidos'];

    public $id;
    public $nombre;
    public $capacidad_maxima;
    public $estado;
    public $tipo_cama;
    public $precio_base;
    public $servicios_incluidos;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->capacidad_maxima = $args['capacidad_maxima'] ?? '';
        $this->estado = $args['estado'] ?? '';
        $this->tipo_cama = $args['tipo_cama'] ?? '';
        $this->precio_base = $args['precio_base'] ?? '';
        $this->servicios_incluidos = $args['servicios_incluidos'] ?? '';
    }
}