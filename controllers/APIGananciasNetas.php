<?php

namespace Controllers;

use Model\GananciaMensual;

class APIGananciasNetas{
    public static function gananciasPorPeriodo() {
        is_auth();
        
        // Establecer headers
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        // Obtener parámetros desde la URL
        $anio = $_GET['anio'] ?? date('Y'); // Si no envían año, usa el actual
        $periodo = $_GET['periodo'] ?? 'enero-junio'; // Valor por defecto
    
        $ingresosPeriodo = GananciaMensual::obtenerGananciasPorPeriodo($anio, $periodo);
    
        if (empty($ingresosPeriodo)) {
            echo json_encode([]);
            http_response_code(204);
        } else {
            http_response_code(200);
            echo json_encode($ingresosPeriodo);
        }
    }
    
}