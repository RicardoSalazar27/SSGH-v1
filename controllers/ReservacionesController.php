<?php

namespace Controllers;

use Classes\Recibo;
use Model\Auditoria;
use Model\Crear_Reservacion;
use Model\Editar_Reservacion;
use Model\EstadoReservacion;
use Model\Hotel;
use Model\Reservacion;
use Model\Usuario;
use MVC\Router;

class ReservacionesController {
    public static function index(Router $router) {

        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);

        $hotel = Hotel::get(1);

        $estadosDeReservacion = EstadoReservacion::todosEstadoReservacion();

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
            'fecha' => $fecha,
            'estadosDeReservacion' => $estadosDeReservacion
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
            //debuguear($datos_json);
            // Recoger los datos del cliente y la reservación desde el JSON decodificado
            // $datos = [
            //     'cliente' => [
            //         'correo' => $datos_json['cliente']['correo'] ?? '',
            //         'nombre' => $datos_json['cliente']['nombre'] ?? '',
            //         'apellidos' => $datos_json['cliente']['apellidos'] ?? '',
            //         'documento_identidad' => $datos_json['cliente']['documento_identidad'] ?? '',
            //         'telefono' => $datos_json['cliente']['telefono'] ?? '',
            //         'direccion' => $datos_json['cliente']['direccion'] ?? ''
            //     ],
            //     'fechas' => [
            //         'entrada' => $datos_json['fechas']['entrada'] ?? '',
            //         'salida' => $datos_json['fechas']['salida'] ?? ''
            //     ],
            //     'pago' => [
            //         'totalPagar' => (float) ($datos_json['pago']['totalPagar'] ?? 0),
            //         'adelanto' => (float) ($datos_json['pago']['adelanto'] ?? 0),
            //         'descuento' => (float) ($datos_json['pago']['descuento'] ?? 0),
            //         'cobroExtra' => (float) ($datos_json['pago']['cobroExtra'] ?? 0),
            //         'totalPagarOriginal' => (float) ($datos_json['pago']['totalPagarOriginal'] ?? 0),
            //         'tipoDescuento' => $datos_json['pago']['tipoDescuento'] ?? 'MONTO', // MONTO o PORCENTAJE
            //         'metodo_pago' => $datos_json['metodo_pago'] ?? '' // Agregar metodoPago dentro de 'pago'
            //     ],
            //     'habitaciones' => array_map('intval', $datos_json['habitaciones'] ?? []), // Convertir habitaciones a enteros
            //     'observaciones' => $datos_json['observaciones'] ?? '',
            //     'usuario_id' => (int) ($datos_json['usuario_id'] ?? 1) // Convertir ID usuario a entero
            // ];
            // Llamar al modelo para crear la reservación
            //$resultado = Crear_Reservacion::crearReservacion($datos);
            $resultado = true;
            if ($resultado) {
                // Obtener el último registro insertado directamente
                // $query = "SELECT * FROM Reservas ORDER BY ID_reserva DESC LIMIT 1";
                // $resultadoUltimo = Reservacion::consultarSQL($query);
                // $ultimaReserva = array_shift($resultadoUltimo); // Obtener el primer resultado
                // $registro = isset($ultimaReserva->ID_reserva) ? $ultimaReserva->ID_reserva : 'NULL';
                // // Registrar en auditoría
                // $usuarioId = $_SESSION['id'];
                // $auditoria = new Auditoria();
                // date_default_timezone_set("America/Mexico_City");
                // $fechaHora = date('Y-m-d H:i:s');
                // $datosAuditoria = [
                //     'id_usuario' => $usuarioId,
                //     'accion' => 'CREAR',
                //     'tabla_afectada' => 'Reservas',
                //     'id_registro_afectado' => $registro,
                //     'detalle' => "Creo una nueva reservacion",
                //     'fecha_hora' => $fechaHora 
                // ];
                
                // $auditoria->sincronizar($datosAuditoria);
                // $auditoria->guardar();
                
                $recibo = new Recibo();
                $pdfUrl = $recibo->generarComprobante();

                echo json_encode(respuesta('success', 'Reserva Exitosa', 'La reservación se ha creado correctamente.'));
            }
             else {
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

        // Obtener la resrvacion por ID
        $reservacion = array_shift(Reservacion::obtenerReservaConHabitaciones($id)); 

        // Responder con los datos o con un mensaje si no hay registros
        if (empty($reservacion)) {
            http_response_code(204); // 204 No Content cuando no hay datos
            echo json_encode(respuesta('info', 'Sin reservacion', 'No hay reservacion registrada'));
        } else {
            http_response_code(200); // 200 OK
            echo json_encode($reservacion);
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
    
            // Preparar los datos para ser enviados al modelo
            $datos_reservacion = [
                'ID_reserva' => $id, // ID de la reservación a actualizar
                'cliente' => [
                    'correo' => $datos['cliente']['correo'] ?? '',
                    'nombre' => $datos['cliente']['nombre'] ?? '',
                    'apellidos' => $datos['cliente']['apellidos'] ?? '',
                    'documento_identidad' => $datos['cliente']['documento_identidad'] ?? '',
                    'telefono' => $datos['cliente']['telefono'] ?? '',
                    'direccion' => $datos['cliente']['direccion'] ?? ''
                ],
                'fechas' => [
                    'entrada' => $datos['fechas']['entrada'] ?? '',
                    'salida' => $datos['fechas']['salida'] ?? ''
                ],
                'pago' => [
                    'totalPagar' => (float) ($datos['pago']['totalPagar'] ?? 0),
                    'adelanto' => (float) ($datos['pago']['adelanto'] ?? 0),
                    'descuento' => (float) ($datos['pago']['descuento'] ?? 0),
                    'cobroExtra' => (float) ($datos['pago']['cobroExtra'] ?? 0),
                    'totalPagarOriginal' => (float) ($datos['pago']['totalPagarOriginal'] ?? 0),
                    'tipoDescuento' => $datos['pago']['tipoDescuento'] ?? 'MONTO', // Monto o Porcentaje
                    'metodo_pago' => $datos['metodo_pago'] ?? '' // Método de pago
                ],
                'habitaciones' => array_map('intval', $datos['habitaciones'] ?? []), // Convertir habitaciones a enteros
                'observaciones' => $datos['observaciones'] ?? '',
                'ID_estado' => (int) ($datos['ID_estado'] ?? 1) // Estado de la reservación
            ];
             //debuguear($datos_reservacion);
             //return;
            // Llamar al modelo para actualizar la reservación
            $resultado = Editar_Reservacion::editarReservacion($datos_reservacion);
    
            // Verificar si la actualización fue exitosa
            if ($resultado) {
                
                $usuarioId = $_SESSION['id'];  // Asegúrate que $_SESSION['id'] tenga un valor válido
                $auditoria = new Auditoria();
                $registro = $id;  // Si id_registro_afectado es NULL, esto está bien
                date_default_timezone_set("America/Mexico_City");
                $fechaHora = date('Y-m-d H:i:s');  // Esto devuelve la fecha y hora actuales en formato "YYYY-MM-DD HH:MM:SS"
                $datosAuditoria = [
                    'id_usuario' => $usuarioId,
                    'accion' => 'EDITAR',
                    'tabla_afectada' => 'Reservas',
                    'id_registro_afectado' => $registro,
                    'detalle' => "Editó Reservacion $id",
                    'fecha_hora' => $fechaHora 
                ];

                $auditoria->sincronizar($datosAuditoria);
                $auditoria->guardar();

                echo json_encode(respuesta('success', 'Actualización exitosa', 'La reservación se ha actualizado correctamente'));
            } else {
                http_response_code(500);
                echo json_encode(respuesta('error', 'Error al actualizar', 'Hubo un problema al actualizar la reservación'));
            }
        }
    }
}