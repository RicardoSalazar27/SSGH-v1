<?php

namespace Controllers;

use Classes\Email;
use Model\Habitacion;
use Model\Hotel;
use Model\Reservacion;
use Model\Usuario;
use MVC\Router;
use Model\GananciaMensual;

class DashboardController {
    public static function index(Router $router) {
        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);

        $totalHabitaciones = Habitacion::total();
        $totalHabitacionesDisponibles = Habitacion::totalArrayIn(['id_estado_habitacion' => [1, 6]]);
        $totalHabitacionesOcupadas = Habitacion::totalArrayIn(['id_estado_habitacion' => [2, 3, 4, 5, 7, 8]]);
        $totalHabitacionesReservadasHoy = Reservacion::contarHabitacionesReservadasHoy();

        $reservas = Reservacion::detallesHabitacionesReservadasHoy();

        // Render a la vista 
        $router->render('admin/dashboard/index', [
            'titulo' => 'Panel de control',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'totalHabitaciones' => $totalHabitaciones,
            'totalHabitacionesDisponibles' => $totalHabitacionesDisponibles,
            'totalHabitacionesOcupadas' => $totalHabitacionesOcupadas,
            'totalHabitacionesReservadasHoy' => $totalHabitacionesReservadasHoy,
            'reservas' => $reservas
        ]);
    }
}