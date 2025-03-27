<?php

namespace Controllers;

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

        // Render a la vista 
        $router->render('admin/recepcion/index', [
            'titulo' => 'Vista General De Recepcion',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'niveles' => $niveles
        ]);
    }
}