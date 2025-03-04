<?php 

namespace Controllers;

use Model\Hotel;
use Model\Usuario;
use MVC\Router;

class HabitacionController{

    public static function index (Router $router){
        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);
        
        // Render a la vista 
        $router->render('admin/configuracion/habitaciones/index', [
            'titulo' => 'Niveles',
            'usuario' => $usuario,
            'hotel' => $hotel
        ]);
    }

}
?>