<?php

namespace Controllers;

use Model\Hotel;
use Model\Reserva;
use Model\Reservacion;
use Model\Usuario;
use MVC\Router;

class ReservacionesController {
    public static function index(Router $router) {

        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);

        $hotel = Hotel::get(1);

        // Crear arrays de traducción
        $dias = ['Sunday' => 'Domingo', 'Monday' => 'Lunes', 'Tuesday' => 'Martes', 'Wednesday' => 'Miércoles', 'Thursday' => 'Jueves', 'Friday' => 'Viernes', 'Saturday' => 'Sábado'];
        $meses = ['January' => 'Enero', 'February' => 'Febrero', 'March' => 'Marzo', 'April' => 'Abril', 'May' => 'Mayo', 'June' => 'Junio', 'July' => 'Julio', 'August' => 'Agosto', 'September' => 'Septiembre', 'October' => 'Octubre', 'November' => 'Noviembre', 'December' => 'Diciembre'];

        // Obtener fecha en inglés
        $fecha_ingles = date('l, F j, Y');

        // Reemplazar día y mes en español
        $fecha = str_replace(array_keys($dias), array_values($dias), $fecha_ingles);
        $fecha = str_replace(array_keys($meses), array_values($meses), $fecha);

        // Render a la vista 
        $router->render('admin/reservaciones/index', [
            'titulo' => 'Reservaciones',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'fecha' => $fecha
        ]);
    }

    public static function crear() {
        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Decodificar el JSON recibido bajo la clave 'reserva'
            $datos_json = json_decode($_POST['reserva'], true); // true para convertirlo a un array asociativo
    
            // Verificar si el JSON fue decodificado correctamente
            if ($datos_json === null) {
                echo json_encode(respuesta('error', 'Error', 'Los datos enviados no son válidos (JSON mal formado).'));
                exit;
            }            
    
            // Recoger los datos del cliente y la reservación desde el JSON decodificado
            $datos = [
                'cliente' => [
                    'correo' => $datos_json['cliente']['correo'] ?? '',
                    'nombre' => $datos_json['cliente']['nombre'] ?? '',
                    'apellidos' => $datos_json['cliente']['apellidos'] ?? '',
                    'documento_identidad' => $datos_json['cliente']['documento_identidad'] ?? '',
                    'telefono' => $datos_json['cliente']['telefono'] ?? '',
                    'direccion' => $datos_json['cliente']['direccion'] ?? ''
                ],
                'fechas' => [
                    'entrada' => $datos_json['fechas']['entrada'] ?? '',
                    'salida' => $datos_json['fechas']['salida'] ?? ''
                ],
                'pago' => [
                    'totalPagar' => (float) $datos_json['pago']['totalPagar'], // Convertir a número flotante
                    'adelanto' => (float) $datos_json['pago']['adelanto'], // Convertir a número flotante
                    'descuento' => (float) $datos_json['pago']['descuento'], // Convertir a número flotante
                    'cobroExtra' => (float) $datos_json['pago']['cobroExtra'], // Convertir a número flotante
                    'totalPagarOriginal' => (float) $datos_json['pago']['totalPagarOriginal'] // Convertir a número flotante
                ],
                'metodoPago' => $datos_json['metodoPago'] ?? '',
                'habitaciones' => array_map('intval', $datos_json['habitaciones'] ?? []), // Convertir todas las habitaciones a enteros
                'observaciones' => $datos_json['observaciones'] ?? '', // Observaciones opcionales
                'usuario_id' => $datos_json['usuario_id'] ?? 1  // ID de usuario, puede venir de sesión o de POST
            ];
    
            // Debug para ver los datos decodificados
            //debuguear($datos);
    
            // Llamar al modelo para crear la reservación
            $resultado = Reserva::crearReservacion($datos);
    
            if ($resultado) {
                echo json_encode(respuesta('success', 'Reserva Exitosa', 'La reservación se ha creado correctamente.'));
            } else {
                echo json_encode(respuesta('error', 'Error', 'Hubo un problema al crear la reservación.'));
            }            
        }
    }
    
    public static function listar(){

        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        // Obtener todos las reservaciones
        $reservaciones = Reservacion::obtenerReservasConHabitaciones();

        // Responder con los datos o con un mensaje si no hay registros
        if (empty($reservaciones)) {
            http_response_code(204); // 204 No Content cuando no hay datos
            echo json_encode(respuesta('info', 'Sin reservaciones', 'No hay reservaciones registradas'));
        } else {
            http_response_code(200); // 200 OK
            echo json_encode($reservaciones);
        }
    }

    public static function obtener($id){

        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');

        // Obtener todos las reservaciones
        $reservaciones = Reservacion::obtenerReservaConHabitaciones($id);

        // Responder con los datos o con un mensaje si no hay registros
        if (empty($reservaciones)) {
            http_response_code(204); // 204 No Content cuando no hay datos
            echo json_encode(respuesta('info', 'Sin reservaciones', 'No hay reservaciones registradas'));
        } else {
            http_response_code(200); // 200 OK
            echo json_encode($reservaciones);
        }
    }
}