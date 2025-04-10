<?php

namespace Controllers;

use Exception;
use Model\Auditoria;
use Model\Hotel;
use Model\Pago;
use Model\Producto;
use Model\Reservacion;
use Model\Usuario;
use MVC\Router;

class VenderProductosController {
    public static function index(Router $router){
        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);

        $reservaciones = Reservacion::detallesHabitacionesReservadasHoy();
        //debuguear($reservas);
        
        // Render a la vista 
        $router->render('admin/punto_de_venta/vender_productos/index', [
            'titulo' => 'Reservacion para realizar ventas',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'reservaciones' => $reservaciones
        ]);
    }

    public static function ventaReservacion(Router $router){
        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);

        //obtenermos la reserva de la url
        $idReserva = $_GET['id'] ?? null;
        if(!$idReserva){
            header('Location: /admin/puntodeventa/vender');
        }
        
        //obtenemos como objeto la reservacion con mas detalles, en base a la reserva
        $reservacion = array_shift(Reservacion::obtenerReservaConHabitaciones($idReserva));
        
        //si no existe la reservacion
        if(!$reservacion){
            header('Location: /admin/puntodeventa/vender');
        }
        //debuguear($reservacion);

        // Render a la vista 
        $router->render('admin/punto_de_venta/vender_productos/ventareservacion', [
            'titulo' => 'Proceso De Venta',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'reservacion' => $reservacion
        ]);
    }

    public static function obtenerProducto($codigo_barras){
        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        try {
            // Búsqueda con coincidencias parciales (LIKE)
            $productos = Producto::like('codigo_barras', $codigo_barras);
    
            if (empty($productos)) {
                http_response_code(404);
                echo json_encode(['message' => 'No se encontraron productos']);
                return;
            }
    
            http_response_code(200); // 200 OK
            echo json_encode($productos);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'message' => 'No se pudo hacer la búsqueda, contacta a soporte.'
                // 'error' => $e->getMessage() // Puedes quitar esto en producción
            ]);
        }
    }

    public static function registarVentasPorReservacion() {
        is_auth();
    
        // Establecer los headers para CORS y tipo de contenido
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $body = json_decode(file_get_contents('php://input'), true); // Arreglo asociativo
    
            // Validación mínima
            if (!isset($body['ventas']) || !is_array($body['ventas']) || empty($body['ventas'])) {
                http_response_code(400);
                echo json_encode(respuesta('error', 'Datos incompletos', 'No se recibieron ventas válidas.'));
                return;
            }
    
            $ventas = $body['ventas'];
            $resultado = Pago::insertarVentasYActualizarStock($body);
    
            if ($resultado) {
                // Auditoría
                $usuarioId = $_SESSION['id'] ?? null;
    
                if ($usuarioId) {
                    date_default_timezone_set("America/Mexico_City");
                    $fechaHora = date('Y-m-d H:i:s');
    
                    $datosAuditoria = [
                        'id_usuario' => $usuarioId,
                        'accion' => 'VENTA',
                        'tabla_afectada' => 'Pagos',
                        'id_registro_afectado' => $ventas[0]['reservacion_id'] ?? 'NULL',
                        'detalle' => "Vendió producto(s) o servicio(s)",
                        'fecha_hora' => $fechaHora
                    ];
    
                    $auditoria = new Auditoria();
                    $auditoria->sincronizar($datosAuditoria);
                    $auditoria->guardar();
                }
    
                http_response_code(201);
                echo json_encode(respuesta('success', 'Guardado', 'Venta terminada correctamente'));
            } else {
                http_response_code(500);
                echo json_encode(respuesta('error', 'Error', 'Hubo un problema al guardar la venta, intente de nuevo.'));
            }
        }
    }    
}