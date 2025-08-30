<?php

namespace Controllers;

use Model\Auditoria;
use Model\Hotel;
use Model\Nivel;
use Model\Recibo;
use Model\Usuario;
use MVC\Router;

require_once __DIR__ . '/../utilities/comprobanteTemplate/index.php';

class RecibosController {
    public static function index(Router $router) {
        //is_auth();
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

        $reservas = Recibo::tablaRecibos();
        
        // Render a la vista 
        $router->render('admin/recibos/index', [
            'titulo' => 'Recibos de reservaciones',
            'usuario' => $usuario,
            'hotel' => $hotel,
            'reservas' => $reservas
        ]);
    }

    public static function generarPDF() {
        if (!is_auth()) {
            header('Location: /login');
            exit;
        }
    
        tiene_rol([1]);
    
        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);

        $id_reserva = $_GET['id'];

        $reserva = Recibo::datosReserva($id_reserva);
        $reserva = array_shift($reserva);
        
        $habitaciones = Recibo::datosHabitaciones($id_reserva);

        $cliente = Recibo::datosCliente($id_reserva);
        $cliente = array_shift($cliente);

        $plantilla = getPlantilla($reserva, $habitaciones, $cliente, $hotel);
        $css = file_get_contents(__DIR__ . '/../utilities/comprobanteTemplate/style.css');
        
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($plantilla, \Mpdf\HTMLParserMode::HTML_BODY);
        
        $nombreArchivo = "recibo_reserva_" . $id_reserva . ".pdf";

        $mpdf->Output($nombreArchivo, \Mpdf\Output\Destination::INLINE);
        
    }
    
}