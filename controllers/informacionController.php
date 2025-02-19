<?php

namespace Controllers;

use Model\Hotel;
use Model\Usuario;
use MVC\Router;

class informacionController {
    public static function index(Router $router) {

        is_auth();

        $usuario = Usuario::where('email', $_SESSION['email']);

        $alertas = [];

        $hotel = Hotel::get(1);
        
        // Render a la vista 
        $router->render('admin/configuracion/informacion/index', [
            'titulo' => 'Informacion Del Hotel',
            'alertas' => $alertas,
            'usuario' => $usuario,
            'hotel' => $hotel
        ]);
    }

    public static function actualizar() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            // Buscar el hotel por correo
            $hotel = Hotel::where('correo', $_POST['correo']);
    
            // Si `where` devuelve una colección o array, tomar el primer resultado
            if (is_array($hotel) || is_object($hotel)) {
                $hotel = is_array($hotel) ? ($hotel[0] ?? null) : $hotel;
            }
    
            if (!$hotel) {
                echo json_encode(respuesta('error', 'Error', 'Error al actualizar'));
                return;
            }
    
            // Carpeta donde se guardarán las imágenes
            $carpeta_imagenes = '../public/build/img';
            if (!is_dir($carpeta_imagenes)) {
                mkdir($carpeta_imagenes, 0755, true);
            }
    
            // Manejo de la imagen
            if (!empty($_FILES['img']['tmp_name'])) {  // <-- Asegúrate de usar el mismo nombre clave en JS y PHP
                $rutaDocumentoAnterior = "$carpeta_imagenes/{$hotel->img}.png";
                
                // Eliminar la imagen anterior si existe
                if (!empty($hotel->img) && file_exists($rutaDocumentoAnterior)) {
                    if (!unlink($rutaDocumentoAnterior)) {
                        echo json_encode(respuesta('error', 'Error', 'No se pudo eliminar la imagen anterior'));
                        return;
                    }
                }
    
                // Crear un nuevo nombre para la imagen
                $nombreDocumento = md5(uniqid(rand(), true)) . '.png';
                $rutaDocumento = "$carpeta_imagenes/$nombreDocumento";
    
                // Mover el archivo a la carpeta de imágenes
                if (move_uploaded_file($_FILES['img']['tmp_name'], $rutaDocumento)) {
                    $hotel->img = pathinfo($nombreDocumento, PATHINFO_FILENAME); // Guardar solo el nombre sin extensión
                } else {
                    echo json_encode(respuesta('error', 'Error', 'No se pudo guardar la nueva imagen'));
                    return;
                }
            }
    
            // Sincronizar y guardar datos
            $hotel->sincronizar($_POST);
            if ($hotel->guardar()) {
                echo json_encode(respuesta('success', 'Actualizado', 'El hotel ha sido actualizado correctamente'));
            } else {
                echo json_encode(respuesta('error', 'Error', 'Hubo un problema al actualizar el hotel'));
            }
        }
    }    
    
}