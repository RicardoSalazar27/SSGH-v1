<?php

namespace Controllers;

use Model\Categoria;
use Model\Hotel;
use Model\Usuario;
use MVC\Router;

class CategoriasController{
    public static function index(Router $router){
        
        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);
        
        // Render a la vista 
        $router->render('admin/configuracion/categorias/index', [
            'titulo' => 'Categorias',
            'usuario' => $usuario,
            'hotel' => $hotel
        ]);
    }

    public static function listar(){
        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        // Obtener todos los niveles
        $categorias = Categoria::all();
    
        // Responder con los datos o con un mensaje si no hay registros
        if (empty($categorias)) {
            http_response_code(204); // 204 No Content cuando no hay datos
            $respuesta = [
                'tipo' => 'info',
                'titulo' => 'Sin contenido',
                'mensaje' => 'No hay categorías registradas'
            ];
            echo json_encode($respuesta);
        } else {
            http_response_code(200); // 200 OK
            echo json_encode($categorias);
        }
    }
    
}
?>