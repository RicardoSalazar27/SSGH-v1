<?php

namespace Controllers;

use Model\Categoria_Producto;
use Model\Hotel;
use Model\Producto;
use Model\Usuario;
use MVC\Router;

class CatalogoProductosController{

    public static function index(Router $router){
        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);
        
        // Render a la vista 
        $router->render('admin/punto_de_venta/catalogo_de_productos/index', [
            'titulo' => 'Productos/Servicios',
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
    
        // Obtener todos los niveles
        $productos = Producto::all();

        foreach($productos as $producto){
            $categoria = Categoria_Producto::find($producto->categoria_producto_id);

            $producto->categoria_producto_id = $categoria;
        }
    
        // Responder con los datos o con un mensaje si no hay registros
        if (empty($productos)) {
            http_response_code(204); // 204 No Content cuando no hay datos
            $respuesta = [
                'tipo' => 'info',
                'titulo' => 'Sin contenido',
                'mensaje' => 'No hay productos registrados'
            ];
            echo json_encode($respuesta);
        } else {
            http_response_code(200); // 200 OK
            echo json_encode($productos);
        }
    }
}

?>