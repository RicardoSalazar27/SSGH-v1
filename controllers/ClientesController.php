<?php

namespace Controllers;

use Model\Cliente;
use Model\Hotel;
use Model\Usuario;
use MVC\Router;

class ClientesController{

    public static function index(Router $router){
        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);
        
        // Render a la vista 
        $router->render('admin/clientes/index', [
            'titulo' => 'Clientes',
            'usuario' => $usuario,
            'hotel' => $hotel
        ]);
    }

    public static function listar() {
        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        // Obtener todos los clientes
        $clientes = Cliente::all();
    
        // Responder con los datos o con un mensaje si no hay registros
        if (empty($clientes)) {
            http_response_code(204); // 204 No Content cuando no hay datos
            $respuesta = [
                'tipo' => 'info',
                'titulo' => 'Sin contenido',
                'mensaje' => 'No hay clientes registrados'
            ];
            echo json_encode($respuesta);
        } else {
            http_response_code(200); // 200 OK
            echo json_encode($clientes);
        }
    }

    public static function obtener($id) {
        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Buscar el nivel
            $cliente = Cliente::find($id);
    
            // Verificar si se encontró el cliente
            if (!$cliente) {
                http_response_code(404);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'No encontrado',
                    'mensaje' => 'El cliente no existe'
                ];
                echo json_encode($respuesta);
                exit;
            }
    
            // Responder con el objeto encontrado
            http_response_code(200);
            echo json_encode($cliente);
        }
    }
}
?>