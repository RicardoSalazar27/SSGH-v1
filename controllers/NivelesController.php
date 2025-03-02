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

    public static function crear(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            // Verificar si el Nivel ya existe
            $existeNivel = Nivel::where('numero', $_POST['numero']);
            if ($existeNivel) {
                http_response_code(400);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Ooops...',
                    'mensaje' => 'El Nivel ya existe'
                ];
                echo json_encode($respuesta);
                exit;
            }
    
            // Crear nuevo nivel
            $nivel = new Nivel();
            $nivel->sincronizar($_POST);
    
            // Guardar el nivel en la base de datos
            $resultado = $nivel->guardar();
    
            // Responder según el resultado de la creación del nivel
            if ($resultado) {
                $respuesta = [
                    'tipo' => 'success',
                    'titulo' => 'Creado',
                    'mensaje' => 'Nivel creado correctamente'
                ];
            } else {
                http_response_code(500);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'Hubo un problema al crear el Nivel'
                ];
            }
    
            echo json_encode($respuesta);
            exit;
        }
    }

    public static function actualizar($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'PATCH') {
    
            // Verificar si se recibió un ID válido desde la URL
            if (!$id) {
                http_response_code(400);
                echo json_encode([
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'ID no proporcionado en la URL'
                ]);
                exit;
            }
    
            // Obtener los datos de la solicitud (los datos del cuerpo serán JSON)
            $datos = json_decode(file_get_contents('php://input'), true);
    
            // Verificar si se enviaron datos válidos
            if (empty($datos)) {
                http_response_code(400);
                echo json_encode([
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'No se proporcionaron datos para actualizar'
                ]);
                exit;
            }
    
            // Buscar el objeto en la base de datos
            $nivel = Nivel::find($id);
            if (!$nivel) {
                http_response_code(404);
                echo json_encode([
                    'tipo' => 'error',
                    'titulo' => 'No encontrado',
                    'mensaje' => 'El Nivel no existe'
                ]);
                exit;
            }
    
            //debuguear($datos);
            // Actualizar el nivel con los datos proporcionados
            $resultado = $nivel->update($datos);
    
            // Responder según el resultado
            if ($resultado) {
                echo json_encode([
                    'tipo' => 'success',
                    'titulo' => 'Actualizado',
                    'mensaje' => 'Nivel actualizado correctamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'Hubo un problema al actualizar el nivel'
                ]);
            }
            exit;
        }
    }    
    
}