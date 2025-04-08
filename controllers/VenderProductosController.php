<?php

namespace Controllers;

use Exception;
use Model\Hotel;
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
}