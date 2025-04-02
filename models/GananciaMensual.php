<?php

namespace Model;

class GananciaMensual extends ActiveRecord {
    protected static $tabla = 'Reservas';
    protected static $columnasDB = [ 
        'anio',
        'mes',
        'ganancias'
    ];

    public $anio;
    public $mes;
    public $ganancias;
    
    public function __construct($args = []) {        
        $this->anio = $args['anio'] ?? '';
        $this->mes = $args['mes'] ?? '';
        $this->ganancias = $args['ganancias'] ?? '';
        
    }
    //Llena la grafica del dashboard
    public static function obtenerGananciasPorPeriodo($anio, $periodo) {
        // Determinar el rango de meses según el periodo seleccionado
        if ($periodo == 'enero-junio') {
            $mesInicio = 1;
            $mesFin = 6;
        } else {
            $mesInicio = 7;
            $mesFin = 12;
        }
    
        $query = "
            SELECT
                YEAR(fecha_entrada) AS anio,
                MONTH(fecha_entrada) AS mes,
                SUM(precio_total - descuento_aplicado + cobro_extra) AS ganancias
            FROM Reservas
            WHERE YEAR(fecha_entrada) = {$anio} 
            AND MONTH(fecha_entrada) BETWEEN {$mesInicio} AND {$mesFin}
            GROUP BY anio, mes
            ORDER BY anio, mes
        ";
        
        return self::consultarSQL($query);
    }    
}