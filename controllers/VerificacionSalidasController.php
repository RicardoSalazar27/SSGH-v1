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
        debuguear($reservasHoy);
        //falta revisar la vista que las habitaciones me las de agrupadas
        
        // Render a la vista 
        $router->render('admin/verificacion_salidas/index', [
            'titulo' => 'Verificacion de salidas',
            'usuario' => $usuario,
            'hotel' => $hotel
        ]);
    }
}