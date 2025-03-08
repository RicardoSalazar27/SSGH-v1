<?php

namespace Controllers;

use Model\Auditoria;
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
            $cliente = Cliente::find($id);
            if (!$cliente) {
                http_response_code(404);
                echo json_encode([
                    'tipo' => 'error',
                    'titulo' => 'No encontrado',
                    'mensaje' => 'El Cliente no existe'
                ]);
                exit;
            }
    
            // Determinar si es PUT o PATCH
            $resultado = ($_SERVER['REQUEST_METHOD'] === 'PUT') 
                ? $cliente->update($datos) 
                : $cliente->updatepartially($datos);

                $usuarioId = $_SESSION['id'];  // Asegúrate que $_SESSION['id'] tenga un valor válido
                $auditoria = new Auditoria();
                $registro = $cliente->id;
                date_default_timezone_set("America/Mexico_City");
                $fechaHora = date('Y-m-d H:i:s');  // Esto devuelve la fecha y hora actuales en formato "YYYY-MM-DD HH:MM:SS"
                $datos = [
                    'id_usuario' => $usuarioId,
                    'accion' => 'EDITAR',
                    'tabla_afectada' => 'Clientes',
                    'id_registro_afectado' => $registro,
                    'detalle' => "Edito Cliente $id",
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
                    'mensaje' => 'Cliente actualizado correctamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'Hubo un problema al actualizar el cliente'
                ]);
            }
            exit;
        }
    }

    public static function eliminar($id){
        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    
            // Verificar si el cliente existe
            $cliente = Cliente::find($id);
            if (!$cliente) {
                http_response_code(404);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => "El cliente no existe."
                ];
                echo json_encode($respuesta);
                exit;
            }
    
            // Intentar eliminar el cliente
            $resultado = $cliente->eliminar();
            if ($resultado) {
    
                // Auditoría de la eliminación
                $usuarioId = $_SESSION['id'];  
                $fechaHora = date('Y-m-d H:i:s');  
                $datosAuditoria = [
                    'id_usuario' => $usuarioId,
                    'accion' => 'ELIMINAR',
                    'tabla_afectada' => 'Clientes',
                    'id_registro_afectado' => $id,
                    'detalle' => "Eliminó Cliente con ID $id",
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
                    'mensaje' => "El Cliente con ID $id ha sido eliminado correctamente."
                ];
                echo json_encode($respuesta);
            } else {
                http_response_code(500);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => "Hubo un error al eliminar el cliente."
                ];
                echo json_encode($respuesta);
            }
    
            exit;
        }
    }
}
?>