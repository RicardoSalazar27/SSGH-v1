<?php

namespace Controllers;

use Model\Hotel;
use Model\Usuario;
use MVC\Router;

class informacionController {
    public static function index(Router $router) {

        // is_auth();
        // Verificar si el usuario está autenticado
        if (!is_auth()) {
            // Si no está autenticado, redirigir al login o página de acceso no autorizado
            header('Location: /login');  // O la ruta que uses para el login
            exit;
        }

        // Verificar si el usuario tiene el rol necesario
        tiene_rol([1]);

        $usuario = Usuario::where('email', $_SESSION['email']);
        $hotel = Hotel::get(1);
        
        // Render a la vista 
        $router->render('admin/configuracion/informacion/index', [
            'titulo' => 'Informacion Del Hotel',
            'usuario' => $usuario,
            'hotel' => $hotel
        ]);
    }

    public static function actualizar() {

        is_auth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        
            // Buscar el hotel por correo
            $hotel = Hotel::find($_POST['id']);
    
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
    
            // Manejo de la imagen (solo si se sube una nueva)
            if (!empty($_FILES['img']['tmp_name'])) {
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
    
            // Sincronizar datos EXCLUYENDO 'img' si no se subió una nueva imagen
            $datosActualizar = $_POST;
            if (empty($_FILES['img']['tmp_name'])) {
                unset($datosActualizar['img']); // Elimina la clave 'img' para no modificar el campo en la BD
            }
            $hotel->sincronizar($datosActualizar);
    
            // Guardar los cambios en la base de datos
            if ($hotel->guardar()) {
                echo json_encode(respuesta('success', 'Actualizado', 'El hotel ha sido actualizado correctamente', [
                    'nombre' => $hotel->nombre,
                    'telefono' => $hotel->telefono,
                    'correo' => $hotel->correo,
                    'ubicacion' => $hotel->ubicacion,
                    'img' => $hotel->img ?? null // Retorna la imagen solo si se actualizó
                ]));
            } else {
                echo json_encode(respuesta('error', 'Error', 'Hubo un problema al actualizar el hotel'));
            }
        }
    }
    
    public static function APIHotel(){
        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        // Obtener todos los niveles
        $hotel = Hotel::get(1);
    
        // Responder con los datos o con un mensaje si no hay registros
        if (empty($hotel)) {
            http_response_code(204); // 204 No Content cuando no hay datos
            $respuesta = [
                'tipo' => 'info',
                'titulo' => 'Sin contenido',
                'mensaje' => 'No hay hotel'
            ];
            echo json_encode($respuesta);
        } else {
            http_response_code(200); // 200 OK
            echo json_encode($hotel);
        }
    }
    
}