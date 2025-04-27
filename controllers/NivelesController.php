<?php

namespace Controllers;

use Model\Auditoria;
use Model\Hotel;
use Model\Nivel;
use Model\Usuario;
use MVC\Router;

class NivelesController {
    public static function index(Router $router) {
        //is_auth();
        // Verificar si el usuario está autenticado
         if (!is_auth()) {
            // Si no está autenticado, redirigir al login o página de acceso no autorizado
            header('Location: /login');  // O la ruta que uses para el login
            exit;
        }

        // Verificar si el usuario tiene el rol necesario
        tiene_rol([1]);

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);
        
        // Render a la vista 
        $router->render('admin/configuracion/niveles/index', [
            'titulo' => 'Niveles',
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
        $niveles = Nivel::all();
    
        // Responder con los datos o con un mensaje si no hay registros
        if (empty($niveles)) {
            http_response_code(204); // 204 No Content cuando no hay datos
            $respuesta = [
                'tipo' => 'info',
                'titulo' => 'Sin contenido',
                'mensaje' => 'No hay niveles registrados'
            ];
            echo json_encode($respuesta);
        } else {
            http_response_code(200); // 200 OK
            echo json_encode($niveles);
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
            $nivel = Nivel::find($id);
    
            // Verificar si se encontró el nivel
            if (!$nivel) {
                http_response_code(404);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'No encontrado',
                    'mensaje' => 'El nivel no existe'
                ];
                echo json_encode($respuesta);
                exit;
            }
    
            // Responder con el objeto encontrado
            http_response_code(200);
            echo json_encode($nivel);
        }
    }    

    public static function eliminar($id) {

        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    
            // Verificar si el nivel existe
            $nivel = Nivel::find($id);
            if (!$nivel) {
                http_response_code(404);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => "El nivel no existe."
                ];
                echo json_encode($respuesta);
                exit;
            }
    
            // Intentar eliminar el nivel
            $resultado = $nivel->eliminar();
            if ($resultado) {
    
                // Auditoría de la eliminación
                $usuarioId = $_SESSION['id'];  
                $fechaHora = date('Y-m-d H:i:s');  
                $datosAuditoria = [
                    'id_usuario' => $usuarioId,
                    'accion' => 'ELIMINAR',
                    'tabla_afectada' => 'Niveles',
                    'id_registro_afectado' => $id,
                    'detalle' => "Eliminó Nivel con ID $id",
                    'fecha_hora' => $fechaHora
                ];
    
                $auditoria = new Auditoria();
                $auditoria->sincronizar($datosAuditoria);
                $auditoria->guardar();
    
                // Responder con éxito
                http_response_code(200);
                $respuesta = [
                    'tipo' => 'success',
                    'titulo' => 'Eliminado',
                    'mensaje' => "El nivel con ID $id ha sido eliminado correctamente."
                ];
                echo json_encode($respuesta);
            } else {
                http_response_code(500);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => "Hubo un error al eliminar el nivel."
                ];
                echo json_encode($respuesta);
            }
    
            exit;
        }
    }
    
    public static function crear() {

        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            // Verificar si el Nivel ya existe
            $existeNivel = Nivel::where('numero', $_POST['numero']);
            if ($existeNivel) {
                http_response_code(400);
                echo json_encode(respuesta('error', 'Ooops...', 'El Nivel ya existe'));
                exit;
            }
    
            // Crear nuevo nivel
            $nivel = new Nivel();
            $nivel->sincronizar($_POST);
    
            // Guardar el nivel en la base de datos
            $resultado = $nivel->guardar();
    
            // Auditoría de la acción
            $usuarioId = $_SESSION['id'];  
            // Definir la zona horaria
            date_default_timezone_set("America/Mexico_City");
            $fechaHora = date('Y-m-d H:i:s'); 
            $datosAuditoria = [
                'id_usuario' => $usuarioId,
                'accion' => 'CREAR',
                'tabla_afectada' => 'Niveles',
                'id_registro_afectado' => $resultado ? $nivel->id : 'NULL',
                'detalle' => "Creó Nivel con número {$_POST['numero']}",
                'fecha_hora' => $fechaHora
            ];
    
            $auditoria = new Auditoria();
            $auditoria->sincronizar($datosAuditoria);
            $auditoria->guardar();
    
            // Responder según el resultado de la creación del nivel
            if ($resultado) {
                http_response_code(201);
                echo json_encode(respuesta('success', 'Creado', 'Nivel Creado correctamente'));
            } else {
                http_response_code(500);
                echo json_encode(respuesta('error', 'Error', 'Hubo un problema al crear el Nivel'));
            }
    
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
                echo json_encode(respuesta('error', 'Error', 'ID no proporcionado en la URL'));
                exit;
            }
    
            // Obtener los datos del cuerpo de la solicitud
            $datos = json_decode(file_get_contents('php://input'), true);
    
            if (empty($datos)) {
                http_response_code(400);
                echo json_encode(respuesta('error', 'Error', 'No se proporcionaron datos para actualizar'));
                exit;
            }
    
            // Buscar el objeto en la base de datos
            $nivel = Nivel::find($id);
            if (!$nivel) {
                http_response_code(404);
                echo json_encode(respuesta('error', 'No encontrado', 'El Nivel no existe'));
                exit;
            }
    
            // Determinar si es PUT o PATCH
            $resultado = ($_SERVER['REQUEST_METHOD'] === 'PUT') 
                ? $nivel->update($datos) 
                : $nivel->updatepartially($datos);
    
            $usuarioId = $_SESSION['id'];  // Asegúrate que $_SESSION['id'] tenga un valor válido
            $auditoria = new Auditoria();
            $registro = $id;  // Si id_registro_afectado es NULL, esto está bien
            date_default_timezone_set("America/Mexico_City");
            $fechaHora = date('Y-m-d H:i:s');  // Esto devuelve la fecha y hora actuales en formato "YYYY-MM-DD HH:MM:SS"
            $datosAuditoria = [
                'id_usuario' => $usuarioId,
                'accion' => 'EDITAR',
                'tabla_afectada' => 'Niveles',
                'id_registro_afectado' => $registro,
                'detalle' => "Editó Nivel $id",
                'fecha_hora' => $fechaHora 
            ];
    
            $auditoria->sincronizar($datosAuditoria);
            $auditoria->guardar();                
    
            // Responder según el resultado
            if ($resultado) {
                http_response_code(200);
                echo json_encode(respuesta('success', 'Actualizado', 'Nivel actualizado correctamente'));
            } else {
                http_response_code(500);
                echo json_encode(respuesta('error', 'Error', 'Hubo un problema al actualizar el nivel'));
            }
            exit;
        }
    }            
    
}