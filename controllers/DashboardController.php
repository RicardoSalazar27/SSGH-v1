<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class DashboardController {
    public static function index(Router $router) {
        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $informacion_hotel = '';
        $nombre_hotel = '';
        // Render a la vista 
        $router->render('admin/dashboard/index', [
            'titulo' => 'Panel de control',
            'usuario' => $usuario,
            'nombre_hotel' => $nombre_hotel
        ]);
    }
}