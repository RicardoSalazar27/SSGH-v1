<?php

namespace Controllers;

use Model\Auditoria;
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
    
        // Obtener todos las categorias
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
    
    public static function obtener($id){
        is_auth();

        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Buscar la categoria
            $categoria = Categoria::find($id);
    
            // Verificar si se encontró el categoria
            if (!$categoria) {
                http_response_code(404);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'No encontrado',
                    'mensaje' => 'La categoria no existe'
                ];
                echo json_encode($respuesta);
                exit;
            }
    
            // Responder con el objeto encontrado
            http_response_code(200);
            echo json_encode($categoria);
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
    
            // Verificar si el categ$categoria existe
            $categoria = Categoria::find($id);
            if (!$categoria) {
                http_response_code(404);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => "El categoria no existe."
                ];
                echo json_encode($respuesta);
                exit;
            }
    
            // Intentar eliminar el categoria
            $resultado = $categoria->eliminar();
            if ($resultado) {
    
                // Auditoría de la eliminación
                $usuarioId = $_SESSION['id'];  
                $fechaHora = date('Y-m-d H:i:s');  
                $datosAuditoria = [
                    'id_usuario' => $usuarioId,
                    'accion' => 'ELIMINAR',
                    'tabla_afectada' => 'Categorias',
                    'id_registro_afectado' => $id,
                    'detalle' => "Eliminó Categoria con ID $id",
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
                    'mensaje' => "El categoria con ID $id ha sido eliminado correctamente."
                ];
                echo json_encode($respuesta);
            } else {
                http_response_code(500);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => "Hubo un error al eliminar el categoria."
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
    
            // Verificar si el categ$categoria ya existe
            $existeCategoria = Categoria::where('nombre', $_POST['nombre']);
            if ($existeCategoria) {
                http_response_code(400);
                echo json_encode([
                    'tipo' => 'error',
                    'titulo' => 'Ooops...',
                    'mensaje' => 'El Categoria ya existe'
                ]);
                exit;
            }
    
            // Crear nuevo categoria
            $categoria = new Categoria();
            $categoria->sincronizar($_POST);
    
            // Guardar la categoria en la base de datos
            $resultado = $categoria->guardar();
    
            // Auditoría de la acción
            $usuarioId = $_SESSION['id'];  
            // Definir la zona horaria
            date_default_timezone_set("America/Mexico_City");
            $fechaHora = date('Y-m-d H:i:s'); 
            $datosAuditoria = [
                'id_usuario' => $usuarioId,
                'accion' => 'CREAR',
                'tabla_afectada' => 'categorias',
                'id_registro_afectado' => $resultado ? $categoria->id : 'NULL',
                'detalle' => "Creó Categoria con nombre {$_POST['nombre']}",
                'fecha_hora' => $fechaHora
            ];
    
            $auditoria = new Auditoria();
            $auditoria->sincronizar($datosAuditoria);
            $auditoria->guardar();
    
            // Responder según el resultado de la creación del categ$categoria
            if ($resultado) {
                http_response_code(201);
                echo json_encode([
                    'tipo' => 'success',
                    'titulo' => 'Creado',
                    'mensaje' => 'Categoria creado correctamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'Hubo un problema al crear el Categoria'
                ]);
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
            $categoria = Categoria::find($id);
            if (!$categoria) {
                http_response_code(404);
                echo json_encode([
                    'tipo' => 'error',
                    'titulo' => 'No encontrado',
                    'mensaje' => 'La Categoria no existe'
                ]);
                exit;
            }
    
            // Determinar si es PUT o PATCH
            $resultado = ($_SERVER['REQUEST_METHOD'] === 'PUT') 
                ? $categoria->update($datos) 
                : $categoria->updatepartially($datos);

                $usuarioId = $_SESSION['id'];  // Asegúrate que $_SESSION['id'] tenga un valor válido
                $auditoria = new Auditoria();
                $registro = 'NULL';  // Si id_registro_afectado es NULL, esto está bien
                date_default_timezone_set("America/Mexico_City");
                $fechaHora = date('Y-m-d H:i:s');  // Esto devuelve la fecha y hora actuales en formato "YYYY-MM-DD HH:MM:SS"
                $datos = [
                    'id_usuario' => $usuarioId,
                    'accion' => 'EDITAR',
                    'tabla_afectada' => 'Categoria',
                    'id_registro_afectado' => $registro,
                    'detalle' => "Edito Categoria $id",
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
                    'mensaje' => 'Categoria actualizado correctamente'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'Hubo un problema al actualizar el categoria'
                ]);
            }
            exit;
        }
    }
}
?>