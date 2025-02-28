<?php

namespace Model;

class Nivel extends ActiveRecord{

    public static $tabla = 'Niveles';
    public static $columnasDB = ['id', 'nombre', 'numero', 'estatus'];

    public $id;
    public $nombre;
    public $numero;
    public $estatus;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->numero = $args['numero'] ?? '';
        $this->estatus = $args['estatus'] ?? 0;
    }

    public function validarDatos(){
        
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre del Hotel es Obligatorio';
        }
        if(!$this->numero) {
            self::$alertas['error'][] = 'El Correo es Obligatorio';
        }
        return self::$alertas;
    }

}