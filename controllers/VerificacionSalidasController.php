<?php

namespace Controllers;

use Model\Auditoria;
use Model\Checkout;
use Model\Hotel;
use Model\Reservacion;
use Model\TerminarReservacion;
use Model\Usuario;
use Model\VistaReservasTerminanHoy;
use MVC\Router;

class VerificacionSalidasController {
    public static function index(Router $router){
        // Verificar si el usuario está autenticado
        if (!is_auth()) {
            // Si no está autenticado, redirigir al login o página de acceso no autorizado
            header('Location: /login');  // O la ruta que uses para el login
            exit;
        }

        // Verificar si el usuario tiene el rol necesario
        tiene_rol([1,2]);

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);

        $reservasHoy = VistaReservasTerminanHoy::ReservacionesTerminanHoy();
        
        // Render a la vista 
        $router->render('admin/verificacion_salidas/index', [
            'titulo' => 'Verificacion de salidas',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'reservasHoy' => $reservasHoy
        ]);
    }

    public static function checkout(Router $router){
        
        // Verificar si el usuario está autenticado
        if (!is_auth()) {
            // Si no está autenticado, redirigir al login o página de acceso no autorizado
            header('Location: /login');  // O la ruta que uses para el login
            exit;
        }

        // Verificar si el usuario tiene el rol necesario
        tiene_rol([1,2]);
        
        $idReserva = $_GET['id'];
        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);
        $checkouts = Checkout::DatosHabitacionClienteHospedaje($idReserva);
        $reservaConHabitacionClienteHospedaje = array_shift($checkouts);
        $ventasReserva = Checkout::ServicioAlCuarto($idReserva);
        $dineroventas = 0;

        foreach ($ventasReserva as $venta) {
            if ((int)$venta->producto_estado === 0) {
                $dineroventas += (float) $venta->producto_monto;
            }
        }

        $totalPagar = $dineroventas + $reservaConHabitacionClienteHospedaje->precio_pendiente;

        // Render a la vista 
        $router->render('admin/verificacion_salidas/checkout', [
            'titulo' => 'Proceso de salida',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'reservaConHabitacionClienteHospedaje' => $reservaConHabitacionClienteHospedaje,
            'ventasReserva' => $ventasReserva,
            'totalPagar' => $totalPagar
        ]);
    }

    public static function terminarReservacion(){
        is_auth();   
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            $json = file_get_contents('php://input');
            $datos = json_decode($json, true);
    
            $id_reserva = $datos['id_reserva'] ?? null;
            $penalidad = $datos['penalidad'] ?? 0;
            $deudas_liquidadas = $datos['deudas_liquidadas'] ?? false;
    
            // Validaciones iniciales
            if (!$id_reserva || !$deudas_liquidadas) {
                http_response_code(400);
                echo json_encode(respuesta('error', 'Ooops...', 'No se pudo terminar la reservación. Verifica los datos enviados.'));
                return;
            }
    
            // Ejecutar el procedimiento almacenado
            $resultado = TerminarReservacion::terminarReserva($id_reserva,$penalidad);
            
            try {
                // Auditoría
                $usuarioId = $_SESSION['id'] ?? null;
                date_default_timezone_set("America/Mexico_City");
                $fechaHora = date('Y-m-d H:i:s'); 
    
                $datosAuditoria = [
                    'id_usuario' => $usuarioId,
                    'accion' => 'ACTUALIZAR',
                    'tabla_afectada' => 'Reservas',
                    'id_registro_afectado' => $id_reserva,
                    'detalle' => "Terminó la reservación #$id_reserva con penalidad de $$penalidad",
                    'fecha_hora' => $fechaHora
                ];
                
                $auditoria = new Auditoria();
                $auditoria->sincronizar($datosAuditoria);
                $auditoria->guardar();
            
            } catch (\Throwable $th) {
                // Registrar error en archivo log
                error_log("Error en auditoría de terminación de reserva: " . $th->getMessage());
            }            
    
            // Respuesta
            if ($resultado) {
                http_response_code(200);
                echo json_encode(respuesta('success', 'Finalizada', 'La reservación fue terminada correctamente.'));
            } else {
                http_response_code(500);
                echo json_encode(respuesta('error', 'Error', 'Ocurrió un problema al terminar la reservación.'));
            }
        }
    }    
}