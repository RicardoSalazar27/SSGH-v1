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

    public static function obtener($id){
        if ($_SERVER['REQUEST_METHOD'] === 'GET'){
            $nivel = Nivel::find($id);
            echo json_encode($nivel);
        }
    }

    public static function eliminar($id){
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE'){

            $nivel = Nivel::find($id);
            if( !$nivel ){
                $respuesta = [
                    'tipo' => 'error',  // Cambié el tipo a 'success' porque el mensaje era de error
                    'titulo' => 'Error',
                    'mensaje' => "El nivel no existe."
                ];
                echo json_encode($respuesta);
                exit;
            }

            $resultado = $nivel->eliminar();
            if( $resultado ){
                // Ahora puedes usar el $id que viene de la URL
                $respuesta = [
                    'tipo' => 'success',  // Cambié el tipo a 'success' porque el mensaje era de error
                    'titulo' => 'Eliminado',
                    'mensaje' => "El nivel con ID $id ha sido eliminado correctamente."
                ];
                echo json_encode($respuesta);
                exit;
            } else {
                $respuesta = [
                    'tipo' => 'error',  // Cambié el tipo a 'success' porque el mensaje era de error
                    'titulo' => 'Error',
                    'mensaje' => "Hubo un error al eliminar el nivel."
                ];
                echo json_encode($respuesta);
                exit;
            }
        }
    }
}