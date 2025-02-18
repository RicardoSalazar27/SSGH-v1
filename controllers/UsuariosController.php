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

    public static function listar(){
        $usuarios = Usuario::all();
        echo json_encode($usuarios);
    }

    public static function eliminar($id){
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE'){

            $usuario = Usuario::find($id);
            if( !$usuario ){
                $respuesta = [
                    'tipo' => 'error',  // Cambié el tipo a 'success' porque el mensaje era de error
                    'titulo' => 'Error',
                    'mensaje' => "El usuario no existe."
                ];
                echo json_encode($respuesta);
                exit;
            }
            $resultado = $usuario->eliminar();
            if( $resultado ){
                // Ahora puedes usar el $id que viene de la URL
                $respuesta = [
                    'tipo' => 'success',  // Cambié el tipo a 'success' porque el mensaje era de error
                    'titulo' => 'Eliminado',
                    'mensaje' => "El usuario con ID $id ha sido eliminado correctamente."
                ];
                echo json_encode($respuesta);
                exit;
            } else {
                $respuesta = [
                    'tipo' => 'error',  // Cambié el tipo a 'success' porque el mensaje era de error
                    'titulo' => 'Error',
                    'mensaje' => "Hubo un error al eliminar el usuario."
                ];
                echo json_encode($respuesta);
                exit;
            }
        }
    }
    
}