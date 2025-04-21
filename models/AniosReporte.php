<?php

namespace Model;

class AniosReporte extends ActiveRecord {

    // Aunque no usamos directamente la tabla ni columnas, las definimos por convención
    public static $tabla = 'Reservas';
    public static $columnasDB = ['anio'];

    public $anio;

    public function __construct($args = []) {
        $this->anio = $args['anio'] ?? null;
    }

    public static function obtenerAnios() {
        // Consulta a reservas
        $consultaReservas = "SELECT DISTINCT YEAR(fecha_entrada) AS anio FROM Reservas";

        // Consulta a ventas
        $consultaVentas = "SELECT DISTINCT YEAR(fecha_venta) AS anio FROM Ventas";

        // Unión de ambas consultas
        $consulta = "
            SELECT anio FROM (
                $consultaReservas
                UNION
                $consultaVentas
            ) AS anios
            ORDER BY anio DESC
        ";

        $resultado = self::consultarSQL($consulta);
        $anios = [];

        foreach ($resultado as $registro) {
            $anios[] = $registro->anio;
        }

        return $anios;
    }
}
