<?php

namespace Controllers;

use Model\Hotel;
use Model\Reservacion;
use Model\Usuario;
use Model\VistaReservasTerminanHoy;
use MVC\Router;

class VerificacionSalidasController {
    public static function index(Router $router){
        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);

        $reservasHoy = VistaReservasTerminanHoy::ReservacionesTerminanHoy();
        
        // Render a la vista 
        $router->render('admin/verificacion_salidas/index', [
            'titulo' => 'Verificacion de salidas',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'reservasHoy' => $reservasHoy
        ]);
    }

    public static function checkout(Router $router){
        
        is_auth();
        $idReserva = $_GET['id'];
        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);

        //$reservasHoy = VistaReservasTerminanHoy::ReservacionesTerminanHoy();
        
        // Render a la vista 
        $router->render('admin/verificacion_salidas/checkout', [
            'titulo' => 'Proceso de salida',
            'usuario' => $usuario,
            'hotel' => $hotel
        ]);
    }
}