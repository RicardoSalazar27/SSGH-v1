<?php

namespace Controllers;

use Model\Hotel;
use Model\Usuario;
use MVC\Router;

class ReservacionesController {
    public static function index(Router $router) {

        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);

        $hotel = Hotel::get(1);

        // Crear arrays de traducción
        $dias = ['Sunday' => 'Domingo', 'Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado'];
        $meses = ['January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo', 'April' => 'Abril', 'May' => 'Mayo', 'June' => 'Junio', 'July' => 'Julio', 'August' => 'Agosto', 'September' => 'Septiembre', 'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'];

        // Obtener fecha en inglés
        $fecha_ingles = date('l, F j, Y');

        // Reemplazar día y mes en español
        $fecha = str_replace(array_keys($dias), array_values($dias), $fecha_ingles);
        $fecha = str_replace(array_keys($meses), array_values($meses), $fecha);

        // Render a la vista 
        $router->render('admin/reservaciones/index', [
            'titulo' => 'Reservaciones',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'fecha' => $fecha
        ]);
    }

    public static function crear(){
        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // echo json_encode([
            //     'tipo' => 'error',
            //     'titulo' => 'Ooops...',
            //     'mensaje' => 'nos comunicamos perro'
            // ]);
            debuguear($_POST);
        }
    }

}