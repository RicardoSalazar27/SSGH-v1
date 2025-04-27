<?php

namespace Controllers;

use DateTime;
use Model\EstadoCategoria;
use Model\EstadoHabitacion;
use Model\Habitacion;
use Model\Hotel;
use Model\Nivel;
use Model\Reservacion;
use Model\Usuario;
use MVC\Router;

class RecepcionController
{
    public static function index(Router $router)
    {

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

        //Obtener niveles para la vista
        $niveles = Nivel::all('ASC');
        $habitaciones = Habitacion::all('ASC');

        foreach($habitaciones as $habitacion){
            $habitacion->id_estado_habitacion = EstadoHabitacion::find($habitacion->id_estado_habitacion);
            $habitacion->id_categoria = EstadoCategoria::find($habitacion->id_categoria);
        };

        // Render a la vista 
        $router->render('admin/recepcion/index', [
            'titulo' => 'Vista General De Recepcion',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'niveles' => $niveles,
            'habitaciones' => $habitaciones
        ]);
    }

    public static function checkin(Router $router){

        // Verificar si el usuario está autenticado
        if (!is_auth()) {
            // Si no está autenticado, redirigir al login o página de acceso no autorizado
            header('Location: /login');  // O la ruta que uses para el login
            exit;
        }

        // Verificar si el usuario tiene el rol necesario
        tiene_rol([1,2]);
        
        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);

        //Validamos que la habitacion exista
        $idHabitacion = isset($_GET['id']) ? intval($_GET['id']) : 0;
        if ($idHabitacion <= 0) {
            // Manejar el caso en que el ID no sea válido
            die("ID inválido");
        }
        $habitacion = Habitacion::find($idHabitacion);
        if(!$habitacion){
            header('Location: /admin/recepcion');
        };
        
        //Obteniendo los datos faltantes para mostrar, datos de la habitacion en cuestion
        $habitacion->id_nivel = Nivel::find($habitacion->id_nivel);
        $habitacion->id_categoria = EstadoCategoria::find($habitacion->id_categoria);
        $habitacion->id_estado_habitacion = EstadoHabitacion::find($habitacion->id_estado_habitacion);
        
        //Obtenemos fecha actual,fecha de inico de reservacion
        date_default_timezone_set('America/El_Salvador');
        $date = date('Y-m-d');
        $nextday = date('Y-m-d', strtotime('+1 day'));

        // Si la habitación está reservada, obtener la primera reservación
        $reservacion = array_shift(Reservacion::obtenerReservaPorHabitacionYFecha($idHabitacion));
        //debuguear($reservacion);
        if ($reservacion) { // Verificar que se obtuvo una reservación válida
            $reservacion->fecha_entrada = (new DateTime($reservacion->fecha_entrada))->format('Y-m-d');
            $reservacion->fecha_salida = (new DateTime($reservacion->fecha_salida))->format('Y-m-d');
        }

        $ultimaReservacion = Reservacion::proximaReserva($idHabitacion);
        $fechaMax = new DateTime($ultimaReservacion->proxima_reserva);
        $fechaMax->modify('-1 day'); // Restar un día
        $fechaMax = $fechaMax->format('Y-m-d'); // Formato para input date

        // Render a la vista 
        $router->render('admin/recepcion/checkin', [
            'titulo' => 'Procesar Habitacion',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'habitacion' => $habitacion,
            'date' => $date,
            'nextday' => $nextday,
            'reservacion' => $reservacion,
            'fechaMax' => $fechaMax
        ]);
    }
}