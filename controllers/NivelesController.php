<?php

namespace Controllers;

use Model\Hotel;
use Model\Nivel;
use Model\Usuario;
use MVC\Router;

class NivelesController {
    public static function index(Router $router) {
        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);
        
        // Render a la vista 
        $router->render('admin/configuracion/niveles/index', [
            'titulo' => 'Niveles',
            'usuario' => $usuario,
            'hotel' => $hotel
        ]);
    }

    public static function listar(){
        $niveles = Nivel::all();
        echo json_encode($niveles);
    }
}