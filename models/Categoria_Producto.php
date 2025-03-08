<?php

namespace Model;

class Categoria_Producto extends ActiveRecord{

    public static $tabla = 'Categorias_Productos';
    public static $columnasDB = ['id', 'nombre'];

    public $id;
    public $nombre;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
    }
}