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
        //devuelve
        // array(1) {
        //     [0]=>
        //     object(Model\VistaReservasTerminanHoy)#21 (6) {
        //       ["ID_reserva"]=>
        //       string(2) "73"
        //       ["cliente_nombre"]=>
        //       string(12) "Ivana Amante"
        //       ["habitacion_numero"]=>
        //       string(7) "202,203"
        //       ["color_estado_habitacion"]=>
        //       string(7) "warning"
        //       ["icono_estado_habitacion"]=>
        //       string(5) "clock"
        //       ["nombre_estado_habitacion"]=>
        //       string(7) "Ocupada"
        //     }
        //   }
        
        // Render a la vista 
        $router->render('admin/verificacion_salidas/index', [
            'titulo' => 'Verificacion de salidas',
            'usuario' => $usuario,
            'hotel' => $hotel
        ]);
    }
}