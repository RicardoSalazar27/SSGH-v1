<?php 

namespace Controllers;

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
}
?>