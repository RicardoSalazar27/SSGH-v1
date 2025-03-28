<?php

namespace Controllers;

use Model\EstadoCategoria;
use Model\EstadoHabitacion;
use Model\Habitacion;
use Model\Hotel;
use Model\Nivel;
use Model\Usuario;
use MVC\Router;

class RecepcionController
{
    public static function index(Router $router)
    {

        is_auth();

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

        is_auth();
        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);

        $idHabitacion = isset($_GET['id']) ? intval($_GET['id']) : 0;

        if ($idHabitacion <= 0) {
            // Manejar el caso en que el ID no sea válido
            die("ID inválido");
        }

        $habitacion = Habitacion::find($idHabitacion);
        $habitacion->id_nivel = Nivel::find($habitacion->id_nivel);
        $habitacion->id_categoria = EstadoCategoria::find($habitacion->id_categoria);
        $habitacion->id_estado_habitacion = EstadoHabitacion::find($habitacion->id_estado_habitacion);
    
        // Render a la vista 
        $router->render('admin/recepcion/checkin', [
            'titulo' => 'Procesar Habitacion',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'habitacion' => $habitacion
        ]);
    }
}