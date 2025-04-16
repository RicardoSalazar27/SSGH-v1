<?php

namespace Controllers;

use Model\Auditoria;
use Model\AuditoriaUsuarios;
use Model\Hotel;
use Model\Usuario;
use MVC\Router;

class AuditoriaController{
    public static function index(Router $router){
        
        is_auth();
        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);
        
        // Render a la vista 
        $router->render('admin/auditoria/index', [
            'titulo' => 'Registro de actividades',
            'usuario' => $usuario,
            'hotel' => $hotel
        ]);
    }

    public static function RegistroActividades(){
        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        // Obtener todos las actividades
        $actividades = AuditoriaUsuarios::historial();
        
        // Responder con los datos o con un mensaje si no hay registros
        if (empty($actividades)) {
            http_response_code(204); // 204 No Content cuando no hay datos
            $respuesta = [
                'tipo' => 'info',
                'titulo' => 'Sin contenido',
                'mensaje' => 'No hay registros'
            ];
            echo json_encode($respuesta);
        } else {
            http_response_code(200); // 200 OK
            echo json_encode($actividades);
        }
    }
}