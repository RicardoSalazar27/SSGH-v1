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
        //is_auth();

        // Verificar si el usuario está autenticado
        if (!is_auth()) {
            // Si no está autenticado, redirigir al login o página de acceso no autorizado
            header('Location: /login');  // O la ruta que uses para el login
            exit;
        }

        // Verificar si el usuario tiene el rol necesario
        tiene_rol([1,2,3]);

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);

        $totalHabitaciones = Habitacion::total();
        $totalHabitacionesDisponibles = Habitacion::totalArrayIn(['id_estado_habitacion' => [1, 6]]);
        $totalHabitacionesOcupadas = Habitacion::totalArrayIn(['id_estado_habitacion' => [2, 3, 4, 5, 7, 8]]);
        $totalHabitacionesReservadasHoy = Reservacion::contarHabitacionesReservadasHoy();

        $reservas = Reservacion::detallesHabitacionesReservadasHoy();
        //debuguear($reservas);

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