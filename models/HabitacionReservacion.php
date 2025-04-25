<?php
namespace Model;

class HabitacionReservacion extends ActiveRecord {
    protected static $tabla = 'Reservas_Habitaciones'; // Puedes modificar esto si tu tabla real tiene otro nombre
    protected static $columnasDB = ['numero', 'fecha_entrada', 'fecha_salida', 'nivel', 'categoria', 'precio', 'precio_total'];

    public $numero;
    public $fecha_entrada;
    public $fecha_salida;
    public $nivel;
    public $categoria;
    public $precio;
    public $precio_total;

    public function __construct($args = []) {
        $this->numero = $args['numero'] ?? '';
        $this->fecha_entrada = $args['fecha_entrada'] ?? '';
        $this->fecha_salida = $args['fecha_salida'] ?? '';
        $this->nivel = $args['nivel'] ?? '';
        $this->categoria = $args['categoria'] ?? '';
        $this->precio = $args['precio'] ?? 0;
        $this->precio_total = $args['precio_total'] ?? 0;
    }
}
