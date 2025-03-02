<?php

namespace Controllers;

use Model\Auditoria;
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
        is_auth();
        $niveles = Nivel::all();
        echo json_encode($niveles);
    }

    public static function obtener($id){
        is_auth();
        if ($_SERVER['REQUEST_METHOD'] === 'GET'){
            $nivel = Nivel::find($id);
            echo json_encode($nivel);
        }
    }

    public static function eliminar($id){
        
        is_auth();

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

        is_auth();

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

        is_auth();

        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: PUT, PATCH, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
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
    
            // Obtener los datos del cuerpo de la solicitud
            $datos = json_decode(file_get_contents('php://input'), true);
    
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
    
            // Determinar si es PUT o PATCH
            $resultado = ($_SERVER['REQUEST_METHOD'] === 'PUT') 
                ? $nivel->update($datos) 
                : $nivel->updatepartially($datos);

                $usuarioId = $_SESSION['id'];  // Asegúrate que $_SESSION['id'] tenga un valor válido
                $auditoria = new Auditoria();
                $registro = 'NULL';  // Si id_registro_afectado es NULL, esto está bien
                $fechaHora = date('Y-m-d H:i:s');  // Esto devuelve la fecha y hora actuales en formato "YYYY-MM-DD HH:MM:SS"
                $datos = [
                    'id_usuario' => $usuarioId,
                    'accion' => 'EDITAR',
                    'tabla_afectada' => 'Niveles',
                    'id_registro_afectado' => $registro,
                    'detalle' => "Edito Nivel $id",
                    'fecha_hora' => $fechaHora 
                ];
                
                $auditoria->sincronizar($datos);
                $auditoria->guardar();                
    
            // Responder según el resultado
            if ($resultado) {
                http_response_code(200);
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