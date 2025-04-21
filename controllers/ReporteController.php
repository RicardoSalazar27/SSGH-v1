<?php

namespace Controllers;

use Model\AniosReporte;
use Model\Hotel;
use Model\ReporteReservas;
use Model\ReporteVentas;
use Model\Usuario;
use MVC\Router;

class ReporteController{

    public static function indexReporteDiario(Router $router){
        
        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $fecha_hoy = date("Y-m-d");
        $hotel = Hotel::get(1);
        $usuariosReportes = Usuario::all('ASC');

        // Render a la vista 
        $router->render('admin/reportes/reporteDiario/index', [
            'titulo' => 'Reporte Diario',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'usuariosReportes' => $usuariosReportes,
            'fecha_hoy' => $fecha_hoy
            ]);
    }

    public static function indexReporteMensual(Router $router){
        is_auth();
    
        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);
        $usuariosReportes = Usuario::all('ASC');
    
        $meses = [
            '01' => 'Enero',
            '02' => 'Febrero',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        ];
    
        $anios = AniosReporte::obtenerAnios();
    
        $router->render('admin/reportes/reporteMensual/index', [
            'titulo' => 'Reporte Mensual',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'usuariosReportes' => $usuariosReportes,
            'meses' => $meses,
            'anios' => $anios
        ]);
    }
    

    public static function obtenerReporteDiario($usuario_id, $fecha) {
        is_auth();
    
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (empty($usuario_id) || empty($fecha)) {
                http_response_code(400);
                echo json_encode([
                    'tipo' => 'error',
                    'titulo' => 'Datos insuficientes',
                    'mensaje' => 'Faltan usuario_id o fecha'
                ]);
                exit;
            }
            $ventas = ReporteVentas::obtenerVentasPorFechaYUsuario($usuario_id, $fecha);
            $reservas = ReporteReservas::obtenerReservasPorFechaYUsuario($usuario_id, $fecha); // <- usa $usuario_id y $fecha

            http_response_code(200);
            echo json_encode([
                'ventas' => $ventas,
                'reservas' => $reservas
            ]);
        }
    }
    
    // public static function obtenerReporteDiario($usuario_id, $fecha) {
    //     is_auth();
    
    //     header('Content-Type: application/json');
    //     header('Access-Control-Allow-Origin: *');
    //     header('Access-Control-Allow-Methods: GET, OPTIONS');
    //     header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
    //     if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //         if (empty($usuario_id) || empty($fecha)) {
    //             http_response_code(400);
    //             echo json_encode([
    //                 'tipo' => 'error',
    //                 'titulo' => 'Datos insuficientes',
    //                 'mensaje' => 'Faltan usuario_id o fecha'
    //             ]);
    //             exit;
    //         }
    
    //         $ventas = ReporteVentas::obtenerVentasPorFechaYUsuario($usuario_id, $fecha);
    //         $reservas = ReporteReservas::obtenerReservasPorFechaYUsuario($usuario_id, $fecha);
    
    //         // Simular 500 registros
    //         $ventas_simuladas = [];
    //         $reservas_simuladas = [];
    
    //         // Repetir las ventas para obtener 500 registros
    //         foreach (range(1, 50) as $i) { // 50 x 10 = 500
    //             foreach ($ventas as $venta) {
    //                 $ventas_simuladas[] = $venta; 
    //             }
    //         }
    
    //         // Repetir las reservas para obtener 500 registros
    //         foreach (range(1, 50) as $i) { // 50 x 10 = 500
    //             foreach ($reservas as $reserva) {
    //                 $reservas_simuladas[] = $reserva; 
    //             }
    //         }
    
    //         http_response_code(200);
    //         echo json_encode([
    //             'ventas' => $ventas_simuladas,
    //             'reservas' => $reservas_simuladas
    //         ]);
    //     }
    // }    
}

?>