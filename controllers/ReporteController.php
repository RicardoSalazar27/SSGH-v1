<?php

namespace Controllers;

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

        $reservas = ReporteReservas::obtenerReservasPorFechaYUsuario($usuario->id,$fecha_hoy);
        //debuguear($reservas);
        $ventasServicios = 0;
        $totalTablaAlquiler = 0;
        $totalReservaciones = 0;
        foreach($reservas as $reserva){
            $ventasServicios += $reserva->Ventas_Servicios;
            $totalTablaAlquiler += $reserva->Total;
            $totalReservaciones += ($reserva->Total - $reserva->Ventas_Servicios);
        }
        //SEGUNDO TAB
        $ventas = ReporteVentas::obtenerVentasPorFechaYUsuario($usuario->id, $fecha_hoy);
        $ventasPublico = 0;
        foreach($ventas as $venta){
            if(strcasecmp($venta->Tipo, 'Público') === 0){
                $ventasPublico += (float)$venta->Total;
            }
        }

        $TotalVentasServiciosProductosDirectosOReservas = $ventasPublico + $ventasServicios;
       // debuguear($TotalVentasServiciosProductosDirectosOReservas);

        // Render a la vista 
        $router->render('admin/reportes/reporteDiario/index', [
            'titulo' => 'Reporte Diario',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'reservas' => $reservas,
            'ventasServicios' => $ventasServicios,
            'totalTablaAlquiler' => $totalTablaAlquiler,
            'totalReservaciones' => $totalReservaciones,
            'ventas' => $ventas,
            'ventasPublico' => $ventasPublico,
            'TotalVentasServiciosProductosDirectosOReservas' => $TotalVentasServiciosProductosDirectosOReservas,
            'usuariosReportes' => $usuariosReportes,
            'fecha_hoy' => $fecha_hoy
        ]);
    }

    public static function indexReporteMensual(Router $router){
        
        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);
        
        // Render a la vista 
        $router->render('admin/reportes/reporteMensual/index', [
            'titulo' => 'Reporte Mensual',
            'usuario' => $usuario,
            'hotel' => $hotel
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
}

?>