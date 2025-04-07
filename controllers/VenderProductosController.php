<?php

namespace Controllers;

use Model\Hotel;
use Model\Usuario;
use MVC\Router;

class VenderProductosController {
    public static function index(Router $router){
        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);
        
        // Render a la vista 
        $router->render('admin/punto_de_venta/vender_productos/index', [
            'titulo' => 'Habitaciones para realizar ventas',
            'usuario' => $usuario,
            'hotel' => $hotel
        ]);
    }
}