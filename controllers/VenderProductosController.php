<?php

namespace Controllers;

use Model\Cliente;
use Model\Hotel;
use Model\Reservacion;
use Model\Usuario;
use MVC\Router;

class VenderProductosController {
    public static function index(Router $router){
        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);

        $reservaciones = Reservacion::detallesHabitacionesReservadasHoy();
        //debuguear($reservas);
        
        // Render a la vista 
        $router->render('admin/punto_de_venta/vender_productos/index', [
            'titulo' => 'Reservacion para realizar ventas',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'reservaciones' => $reservaciones
        ]);
    }

    public static function ventaReservacion(Router $router){
        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);

        $idReserva = $_GET['id'] ?? null;
        if(!$idReserva){
            header('Location: /admin/puntodeventa/vender');
        }
        
        $reservacion = array_shift(Reservacion::obtenerReservaConHabitaciones($idReserva));
        
        if(!$reservacion){
            header('Location: /admin/puntodeventa/vender');
        }
        //$reserva->ID_cliente = Cliente::find($reserva->ID_cliente);
        //debuguear($reservacion);

        // Render a la vista 
        $router->render('admin/punto_de_venta/vender_productos/ventareservacion', [
            'titulo' => 'Proceso De Venta',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'reservacion' => $reservacion
        ]);
    }
}