<?php

namespace Model;

class Nivel extends ActiveRecord{

    public static $tabla = 'Niveles';
    public static $columnasDB = ['id', 'nombre', 'numero', 'descripcion'];

    public $id;
    public $nombre;
    public $numero;
    public $descripcion;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->numero = $args['numero'] ?? '';
        $this->descripcion = $args['descripcion'] ?? 'Sin descripcion';
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