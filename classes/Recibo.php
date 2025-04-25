<?php
namespace Classes;

use Model\Cliente;
use Model\HabitacionReservacion;
use Model\Hotel;
use Model\Reservacion;
use Mpdf\Mpdf as MpdfMpdf;
use Mpdf\HTMLParserMode;

require_once __DIR__ . '/../utilities/comprobanteTemplate/index.php';

class Recibo {

    public function generarComprobante() {
        $mpdf = new MpdfMpdf();
    
        // Cargar estilos CSS
        $css = file_get_contents(__DIR__ . '/../utilities/comprobanteTemplate/style.css');
        $mpdf->WriteHTML($css, HTMLParserMode::HEADER_CSS);
    
        // Obtener la última reservación
        $query = "SELECT * FROM Reservas ORDER BY ID_reserva DESC LIMIT 1";
        $resultado = Reservacion::consultarSQL($query);
        $reservacion = array_shift($resultado);
    
        // Verificar si se obtuvo una reservación
        if (!$reservacion) {
            throw new \Exception("No se encontró ninguna reservación.");
        }
    
        // Obtener habitaciones asociadas a la reserva
        $query = "
            SELECT 
                h.numero AS numero, 
                r.fecha_entrada AS fecha_entrada, 
                r.fecha_salida AS fecha_salida, 
                n.nombre AS nivel, 
                c.nombre AS categoria, 
                c.precio_base AS precio,
                (c.precio_base * DATEDIFF(r.fecha_salida, r.fecha_entrada)) AS precio_total
            FROM 
                Reservas r
            JOIN Reservas_Habitaciones rh ON r.ID_reserva = rh.ID_reserva
            JOIN Habitaciones h ON rh.ID_habitacion = h.id
            JOIN Niveles n ON h.ID_nivel = n.id
            JOIN Categoria c ON h.ID_categoria = c.id
            WHERE r.ID_reserva = {$reservacion->ID_reserva}
        ";
        $habitaciones = HabitacionReservacion::consultarSQL($query);
    
        // Obtener cliente y hotel
        $cliente = Cliente::find($reservacion->ID_cliente);
        $hotel = Hotel::get(1);
    
        // Generar HTML del comprobante
        $contenido = getPlantilla($reservacion, $habitaciones, $cliente, $hotel);
        $mpdf->WriteHTML($contenido, HTMLParserMode::HTML_BODY);
    
        // Descargar directamente el PDF
        $nombreArchivo = "comprobante_{$reservacion->ID_reserva}.pdf";
        $mpdf->Output($nombreArchivo, 'D'); // 'D' = Download
        return null;
    }        
}
?>
