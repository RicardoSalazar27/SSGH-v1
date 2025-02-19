<?php

namespace Model;

class Hotel extends ActiveRecord{

    public static $tabla = 'Hotel';
    public static $columnasDB = ['id', 'nombre', 'telefono', 'correo', 'ubicacion', 'img'];

    public $id;
    public $nombre;
    public $telefono;
    public $correo;
    public $ubicacion;
    public $img;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->correo = $args['correo'] ?? '';
        $this->ubicacion = $args['ubicacion'] ?? '';
        $this->img = $args['img'] ?? '';
    }

    public function validarDatos(){
        
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre del Hotel es Obligatorio';
        }
        if(!$this->correo) {
            self::$alertas['error'][] = 'El Correo es Obligatorio';
        }
        if(!filter_var($this->correo, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'Email no válido';
        }
        if(!$this->telefono) {
            self::$alertas['error'][] = 'El Telefono del Hotel es Obligatorio';
        }
        if(strlen($this->telefono) < 10) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }

        return self::$alertas;
    }

}