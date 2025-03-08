<?php

namespace Model;

class Producto extends ActiveRecord{

    public static $tabla = 'Productos';
    public static $columnasDB = ['id', 'nombre', 'codigo_barras', 'precio', 'stock', 'categoria_producto_id', 'proveedor', 'foto'];

    public $id;
    public $nombre;
    public $codigo_barras;
    public $precio;
    public $stock;
    public $categoria_producto_id;
    public $proveedor;
    public $foto;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->codigo_barras = $args['codigo_barras'] ?? '';
        $this->precio = $args['precio'] ?? 0;
        $this->stock = $args['stock'] ?? 0;
        $this->categoria_producto_id = $args['categoria_producto_id'] ?? 0;
        $this->proveedor = $args['proveedor'] ?? '';
        $this->foto = $args['foto'] ?? '';
    }
}