<?php

namespace Controllers;

use GuzzleHttp\Psr7\Header;
use Model\Hotel;
use Model\Usuario;
use MVC\Router;

class UsuariosController {
    public static function index(Router $router) {

        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);

        // Render a la vista 
        $router->render('admin/usuarios/index', [
            'titulo' => 'Usuarios',
            'usuario' => $usuario,
            'hotel' => $hotel
        ]);
    }
}