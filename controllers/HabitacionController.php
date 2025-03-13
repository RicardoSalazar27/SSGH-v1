<?php 

namespace Controllers;

use Model\Auditoria;
use Model\Categoria;
use Model\EstadoHabitacion;
use Model\Habitacion;
use Model\Hotel;
use Model\Nivel;
use Model\Usuario;
use MVC\Router;

class HabitacionController{

    public static function index (Router $router){
        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);
        $categorias = Categoria::all('ASC');
        $niveles = Nivel::all('ASC');
        
        // Render a la vista 
        $router->render('admin/configuracion/habitaciones/index', [
            'titulo' => 'Habitaciones',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'categorias'=> $categorias,
            'niveles' => $niveles
        ]);
    }

    public static function listar_estado_habitaciones(){
        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        // Obtener todos los estadosHabitacion
        $estadosHabitacion = EstadoHabitacion::all();
    
        // Responder con los datos o con un mensaje si no hay registros
        if (empty($estadosHabitacion)) {
            http_response_code(204); // 204 No Content cuando no hay datos
            $respuesta = [
                'tipo' => 'info',
                'titulo' => 'Sin contenido',
                'mensaje' => 'No hay estados Habitacion registrados'
            ];
            echo json_encode($respuesta);
        } else {
            http_response_code(200); // 200 OK
            echo json_encode($estadosHabitacion);
        }
    }

    public static function listar(){
        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        // Obtener todos los niveles
        $habitaciones = Habitacion::all();

        foreach($habitaciones as $habitacion){
            $categoria = Categoria::find($habitacion->id_categoria);
            $habitacion->id_categoria = $categoria;

            $nivel = Nivel::find($habitacion->id_nivel);
            $habitacion->id_nivel = $nivel;
        }
    
        // Responder con los datos o con un mensaje si no hay registros
        if (empty($habitaciones)) {
            http_response_code(204); // 204 No Content cuando no hay datos
            $respuesta = [
                'tipo' => 'info',
                'titulo' => 'Sin contenido',
                'mensaje' => 'No hay habitaciones registrados'
            ];
            echo json_encode($respuesta);
        } else {
            http_response_code(200); // 200 OK
            echo json_encode($habitaciones);
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
            $habitacion = Habitacion::find($id);
            $categoria = Categoria::find($habitacion->id_categoria);
            $habitacion->id_categoria = $categoria;

            $nivel = Nivel::find($habitacion->id_nivel);
            $habitacion->id_nivel = $nivel;
    
            // Verificar si se encontró el habitacion
            if (!$habitacion) {
                http_response_code(404);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'No encontrado',
                    'mensaje' => 'El habitacion no existe'
                ];
                echo json_encode($respuesta);
                exit;
            }
    
            // Responder con el objeto encontrado
            http_response_code(200);
            echo json_encode($habitacion);
        }
    }

    public static function disponibles($fechainicio, $fechafin) {
        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Validar que las fechas no estén vacías
            if (empty($fechainicio) || empty($fechafin)) {
                echo json_encode([
                    'error' => 'Las fechas de inicio y fin son obligatorias'
                ]);
                http_response_code(400); // Bad Request
                exit;
            }
    
            // Obtener habitaciones disponibles
             $habitaciones = Habitacion::habitacionesDisponibles($fechainicio, $fechafin);
             foreach($habitaciones as $habitacion){
                $habitacion->id_categoria = Categoria::find($habitacion->id_categoria); 
             }
    
            // Responder en JSON
            http_response_code(200);
            echo json_encode($habitaciones);
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
            $habitacion = Habitacion::find($id);
            if (!$habitacion) {
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
                ? $habitacion->update($datos) 
                : $habitacion->updatepartially($datos);                
    
            // Responder según el resultado
            if ($resultado) {

                $usuarioId = $_SESSION['id'];  // Asegúrate que $_SESSION['id'] tenga un valor válido
                $auditoria = new Auditoria();
                $registro = $habitacion->id;  // Si id_registro_afectado es NULL, esto está bien
                date_default_timezone_set("America/Mexico_City");
                $fechaHora = date('Y-m-d H:i:s');  // Esto devuelve la fecha y hora actuales en formato "YYYY-MM-DD HH:MM:SS"
                $datos = [
                    'id_usuario' => $usuarioId,
                    'accion' => 'EDITAR',
                    'tabla_afectada' => 'Habitaciones',
                    'id_registro_afectado' => $registro,
                    'detalle' => "Edito Habitacion $id",
                    'fecha_hora' => $fechaHora 
                ];
                
                $auditoria->sincronizar($datos);
                $auditoria->guardar();

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
                    'mensaje' => 'Hubo un problema al actualizar el habitacion'
                ]);
            }
            exit;
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
            $habitacion = Habitacion::find($id);
            if (!$habitacion) {
                http_response_code(404);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => "La habitacion no existe."
                ];
                echo json_encode($respuesta);
                exit;
            }
    
            $registro = $habitacion->id;
            $numero = $habitacion->numero;
            // Intentar eliminar el nivel
            $resultado = $habitacion->eliminar();
            if ($resultado) {
    
                // Auditoría de la eliminación
                $usuarioId = $_SESSION['id'];  
                $fechaHora = date('Y-m-d H:i:s');  
                $datosAuditoria = [
                    'id_usuario' => $usuarioId,
                    'accion' => 'ELIMINAR',
                    'tabla_afectada' => 'Habitaciones',
                    'id_registro_afectado' => $registro,
                    'detalle' => "Eliminó Habitacion con ID $registro y NUMERO $numero",
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
                    'mensaje' => "La habitacion con ID $registro ha sido eliminado correctamente."
                ];
                echo json_encode($respuesta);
            } else {
                http_response_code(500);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => "Hubo un error al eliminar la habitacion."
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
    
        // Validar entrada
        if (empty($_POST['numero']) || !is_numeric($_POST['numero'])) {
            http_response_code(400);
            echo json_encode([
                'tipo' => 'error',
                'titulo' => 'Datos inválidos',
                'mensaje' => 'El número de habitación es requerido y debe ser numérico.'
            ]);
            exit;
        }
    
        // Verificar si la habitación ya existe
        $existeHabitacion = Habitacion::where('numero', $_POST['numero']);
        if ($existeHabitacion) {
            http_response_code(400);
            echo json_encode([
                'tipo' => 'error',
                'titulo' => 'Ooops...',
                'mensaje' => 'La habitación ya existe'
            ]);
            exit;
        }
    
        // Crear nueva habitación
        $habitacion = new Habitacion();
        $habitacion->sincronizar($_POST);
        //debuguear($habitacion);
        
        // Guardar en la base de datos
        $resultado = $habitacion->guardar();

        // Responder según el resultado de la creación del nivel
        if ($resultado) {

            // Auditoría de la acción
            $usuarioId = $_SESSION['id'] ?? null; // Validar que haya sesión
            date_default_timezone_set("America/Mexico_City");
            $fechaHora = date('Y-m-d H:i:s'); 
        
            // Obtener el ID de la nueva habitación
            $habitacionN = Habitacion::where('numero', $_POST['numero']);
            //error_log(print_r($habitacionN, true)); // Esto imprimirá en el log de PHP

            $registro = $habitacionN ? $habitacionN->id : null;
        
            $datosAuditoria = [
                'id_usuario' => $usuarioId,
                'accion' => 'CREAR',
                'tabla_afectada' => 'Habitaciones',
                'id_registro_afectado' => $registro,
                'detalle' => "Creó habitación con número {$_POST['numero']}",
                'fecha_hora' => $fechaHora
            ];
        
            $auditoria = new Auditoria();
            $auditoria->sincronizar($datosAuditoria);
            $auditoria->guardar();

            http_response_code(201);
            echo json_encode([
            'tipo' => 'success',
            'titulo' => 'Creado',
            'mensaje' => 'Habitacion creada correctamente'
            ]);

        } else {
            http_response_code(500);
            echo json_encode([
                'tipo' => 'error',
                'titulo' => 'Error',
                'mensaje' => 'Hubo un problema al crear la Habitacion'
            ]);
        }
    
        exit;    
    }
}
?>