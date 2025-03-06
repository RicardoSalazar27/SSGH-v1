<?php

namespace Model;

class Cliente extends ActiveRecord{

    public static $tabla = 'Clientes';
    public static $columnasDB = ['id', 'nombre', 'apellidos', 'correo', 'telefono', 'direccion', 'documento_identidad'];

    public $id;
    public $nombre;
    public $apellidos;
    public $correo;
    public $telefono;
    public $direccion;
    public $documento_identidad;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellidos = $args['apellidos'] ?? '';
        $this->correo = $args['correo'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->direccion = $args['direccion'] ?? '';
        $this->documento_identidad = $args['documento_identidad'] ?? '';
    }
}