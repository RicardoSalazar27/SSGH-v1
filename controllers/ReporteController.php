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
        $hotel = Hotel::get(1);
        $reservas = ReporteReservas::obtenerReservasPorFechaYUsuario($usuario->id,'2025-04-16');
        //debuguear($reservas);
        $ventasServicios = 0;
        $totalTablaAlquiler = 0;
        $totalReservaciones = 0;
        foreach($reservas as $reserva){
            $ventasServicios += $reserva->Ventas_Servicios;
            $totalTablaAlquiler += $reserva->Total;
            $totalReservaciones += ($reserva->Total - $reserva->Ventas_Servicios);
        }

        $ventas = ReporteVentas::obtenerVentasPorFechaYUsuario($usuario->id,'2025-04-16');
    
        $ventasPublico = 0;
        foreach($ventas as $venta){
            if($venta->Tipo == 'Publico'){
                $ventasPublico += $venta->Total; 
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
            'TotalVentasServiciosProductosDirectosOReservas' => $TotalVentasServiciosProductosDirectosOReservas
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
}

?>