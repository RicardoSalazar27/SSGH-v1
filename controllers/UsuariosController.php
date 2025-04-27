<?php

namespace Controllers;

use GuzzleHttp\Psr7\Header;
use Model\Auditoria;
use Model\Hotel;
use Model\Usuario;
use MVC\Router;

class UsuariosController {
    public static function index(Router $router) {

        //is_auth();
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
        $router->render('admin/usuarios/index', [
            'titulo' => 'Usuarios',
            'usuario' => $usuario,
            'hotel' => $hotel
        ]);
    }

    public static function listar(){
        is_auth();
        $usuarios = Usuario::all();
        echo json_encode($usuarios);
    }

    public static function obtener($id){
        is_auth();
        if ($_SERVER['REQUEST_METHOD'] === 'GET'){
            $usuario = Usuario::find($id);
            echo json_encode($usuario);
        }
    }

    public static function eliminar($id){
        is_auth();
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE'){

            $usuario = Usuario::find($id);
            if( !$usuario ){
                $respuesta = [
                    'tipo' => 'error',  // Cambié el tipo a 'success' porque el mensaje era de error
                    'titulo' => 'Error',
                    'mensaje' => "El usuario no existe."
                ];
                echo json_encode($respuesta);
                exit;
            }

            // Carpeta donde se guardarán las imágenes
            $carpeta_imagenes = '../public/build/img';
            if (!is_dir($carpeta_imagenes)) {
                mkdir($carpeta_imagenes, 0755, true);
            }

            $rutaDocumentoAnterior = "$carpeta_imagenes/{$usuario->img}.png";
            // Eliminar la imagen anterior si existe
            if (!empty($usuario->img) && file_exists($rutaDocumentoAnterior)) {
                if (!unlink($rutaDocumentoAnterior)) {
                    $respuesta = [
                        'tipo' => 'error',
                        'titulo' => 'Error',
                        'mensaje' => 'No se pudo eliminar la imagen anterior.'
                    ];
                    echo json_encode($respuesta);
                    exit;
                    }
            }

            $resultado = $usuario->eliminar();
            if( $resultado ){

                $usuarioId = $_SESSION['id'];  // Asegúrate que $_SESSION['id'] tenga un valor válido
                $auditoria = new Auditoria();
                $registro = NULL;  // Si id_registro_afectado es NULL, esto está bien
                date_default_timezone_set("America/Mexico_City");
                $fechaHora = date('Y-m-d H:i:s');  // Esto devuelve la fecha y hora actuales en formato "YYYY-MM-DD HH:MM:SS"
                $datosAuditoria = [
                    'id_usuario' => $usuarioId,
                    'accion' => 'ELIMINAR',
                    'tabla_afectada' => 'Usuarios',
                    'id_registro_afectado' => $registro,
                    'detalle' => "Elimino usuario $id",
                    'fecha_hora' => $fechaHora 
                ];
        
                $auditoria->sincronizar($datosAuditoria);
                $auditoria->guardar();
                // Ahora puedes usar el $id que viene de la URL
                $respuesta = [
                    'tipo' => 'success',  // Cambié el tipo a 'success' porque el mensaje era de error
                    'titulo' => 'Eliminado',
                    'mensaje' => "El usuario con ID $id ha sido eliminado correctamente."
                ];
                echo json_encode($respuesta);
                exit;
            } else {
                $respuesta = [
                    'tipo' => 'error',  // Cambié el tipo a 'success' porque el mensaje era de error
                    'titulo' => 'Error',
                    'mensaje' => "Hubo un error al eliminar el usuario."
                ];
                echo json_encode($respuesta);
                exit;
            }
        }
    }
    
    public static function actualizar($id){

        is_auth();
        $usuario = Usuario::find($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            if( !$usuario ){
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => "El usuario no existe."
                ];
                echo json_encode($respuesta);
                exit;
            }
    
            // Carpeta donde se guardarán las imágenes
            $carpeta_imagenes = '../public/build/img';
            if (!is_dir($carpeta_imagenes)) {
                mkdir($carpeta_imagenes, 0755, true);
            }
    
            // Manejo de la imagen (solo si se sube una nueva)
            if (!empty($_FILES['img']['tmp_name'])) {
                $rutaDocumentoAnterior = "$carpeta_imagenes/{$usuario->img}.png";
    
                // Eliminar la imagen anterior si existe
                if (!empty($usuario->img) && file_exists($rutaDocumentoAnterior)) {
                    if (!unlink($rutaDocumentoAnterior)) {
                        $respuesta = [
                            'tipo' => 'error',
                            'titulo' => 'Error',
                            'mensaje' => 'No se pudo eliminar la imagen anterior.'
                        ];
                        echo json_encode($respuesta);
                        exit;
                    }
                }
    
                // Crear un nuevo nombre para la imagen
                $nombreDocumento = md5(uniqid(rand(), true)) . '.png';
                $rutaDocumento = "$carpeta_imagenes/$nombreDocumento";
    
                // Mover el archivo a la carpeta de imágenes
                if (move_uploaded_file($_FILES['img']['tmp_name'], $rutaDocumento)) {
                    $usuario->img = pathinfo($nombreDocumento, PATHINFO_FILENAME); // Guardar solo el nombre sin extensión
                } else {
                    $respuesta = [
                        'tipo' => 'error',
                        'titulo' => 'Error',
                        'mensaje' => 'No se pudo guardar la nueva imagen.'
                    ];
                    echo json_encode($respuesta);
                    exit;
                }
            }
    
            // Sincronizar datos EXCLUYENDO 'img' si no se subió una nueva imagen
            $datosActualizar = $_POST;

            // Si la contraseña está vacía, no la actualizamos
            if (empty($_POST['password'])) {
                unset($datosActualizar['password']);  // No actualizamos la contraseña si está vacía
            }

            // Si no se subió una nueva imagen, no actualizamos el campo 'img'
            if (empty($_FILES['img']['tmp_name'])) {
                unset($datosActualizar['img']);
            }

            // Sincronizar los datos
            $usuario->sincronizar($datosActualizar);

            // Solo hashear la contraseña si viene en $_POST
            if (!empty($_POST['password'])) {
                $usuario->hashPassword();
            }

            $resultado = $usuario->guardar();
    
            if( $resultado ){

                $usuarioId = $_SESSION['id'];  // Asegúrate que $_SESSION['id'] tenga un valor válido
                $auditoria = new Auditoria();
                $registro = $id;  // Si id_registro_afectado es NULL, esto está bien
                date_default_timezone_set("America/Mexico_City");
                $fechaHora = date('Y-m-d H:i:s');  // Esto devuelve la fecha y hora actuales en formato "YYYY-MM-DD HH:MM:SS"
                $datosAuditoria = [
                    'id_usuario' => $usuarioId,
                    'accion' => 'EDITAR',
                    'tabla_afectada' => 'Usuarios',
                    'id_registro_afectado' => $registro,
                    'detalle' => "Actualizo usuario con $id",
                    'fecha_hora' => $fechaHora 
                ];
        
                $auditoria->sincronizar($datosAuditoria);
                $auditoria->guardar();

                $respuesta = [
                    'tipo' => 'success',
                    'titulo' => 'Actualizado',
                    'mensaje' => "El usuario ha sido actualizado correctamente."
                ];
                echo json_encode($respuesta);
                exit;
            } else {
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => "Hubo un error al actualizar el usuario."
                ];
                echo json_encode($respuesta);
                exit;
            }
        }
    }
    
    public static function crear(){
        is_auth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
            // Validar que el email esté presente
            if (empty($_POST['email'])) {
                http_response_code(400);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'El email es obligatorio'
                ];
                echo json_encode($respuesta);
                exit;
            }
    
            // Verificar si el email ya existe
            $existeUsuario = Usuario::where('email', $_POST['email']);
            if ($existeUsuario) {
                http_response_code(400);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Ooops...',
                    'mensaje' => 'Cliente existente'
                ];
                echo json_encode($respuesta);
                exit;
            }
    
            // Crear nuevo usuario
            $usuario = new Usuario();
            $usuario->sincronizar($_POST);
    
            // Carpeta donde se guardarán las imágenes
            $carpeta_imagenes = '../public/build/img';
            if (!is_dir($carpeta_imagenes)) {
                mkdir($carpeta_imagenes, 0755, true); // Crear la carpeta si no existe
            }
    
            // Manejo de la imagen (solo si se sube una nueva)
            if (!empty($_FILES['img']['tmp_name'])) {
                // Ruta de la imagen anterior (si existe)
                $rutaDocumentoAnterior = "$carpeta_imagenes/{$usuario->img}.png";
    
                // Eliminar la imagen anterior si existe
                if (!empty($usuario->img) && file_exists($rutaDocumentoAnterior)) {
                    if (!unlink($rutaDocumentoAnterior)) {
                        $respuesta = [
                            'tipo' => 'error',
                            'titulo' => 'Error',
                            'mensaje' => 'No se pudo eliminar la imagen anterior.'
                        ];
                        echo json_encode($respuesta);
                        exit;
                    }
                }
    
                // Crear un nuevo nombre para la imagen sin extensión
                $nombreDocumento = md5(uniqid(rand(), true)); // Eliminar la extensión en este punto
    
                // Ruta final para guardar la imagen
                $rutaDocumento = "$carpeta_imagenes/$nombreDocumento.png"; // Añadir la extensión al guardar
    
                // Mover el archivo a la carpeta de imágenes
                if (move_uploaded_file($_FILES['img']['tmp_name'], $rutaDocumento)) {
                    // Guardar solo el nombre sin la extensión en la base de datos
                    $usuario->img = $nombreDocumento;
                } else {
                    $respuesta = [
                        'tipo' => 'error',
                        'titulo' => 'Error',
                        'mensaje' => 'No se pudo guardar la nueva imagen.'
                    ];
                    echo json_encode($respuesta);
                    exit;
                }
            }
    
            // Hashear la contraseña del usuario
            $usuario->hashPassword();
    
            // Guardar el usuario en la base de datos
            $resultado = $usuario->guardar();
    
            // Responder según el resultado de la creación del usuario
            if ($resultado) {

                $usuarioId = $_SESSION['id'];  // Asegúrate que $_SESSION['id'] tenga un valor válido
                $auditoria = new Auditoria();
                $registro = NULL;  // Si id_registro_afectado es NULL, esto está bien
                date_default_timezone_set("America/Mexico_City");
                $fechaHora = date('Y-m-d H:i:s');  // Esto devuelve la fecha y hora actuales en formato "YYYY-MM-DD HH:MM:SS"
                $datosAuditoria = [
                    'id_usuario' => $usuarioId,
                    'accion' => 'CREAR',
                    'tabla_afectada' => 'Usuarios',
                    'id_registro_afectado' => $registro,
                    'detalle' => "Creo un nuevo usuario",
                    'fecha_hora' => $fechaHora 
                ];
        
                $auditoria->sincronizar($datosAuditoria);
                $auditoria->guardar();                
        
                $respuesta = [
                    'tipo' => 'success',
                    'titulo' => 'Creado',
                    'mensaje' => 'Usuario creado correctamente'
                ];
            } else {
                http_response_code(500);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'Hubo un problema al crear el usuario'
                ];
            }
    
            echo json_encode($respuesta);
            exit;
        }
    }
    
    public static function usuarioActivo() {
        is_auth(); // Asegura que el usuario esté autenticado
    
        // Headers
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        // Obtener ID de usuario desde la sesión
        $idUsuario = $_SESSION['id'] ?? null;
    
        if (!$idUsuario) {
            http_response_code(401); // No autorizado
            echo json_encode([
                'tipo' => 'error',
                'titulo' => 'No autorizado',
                'mensaje' => 'No se pudo identificar al usuario.'
            ]);
            return;
        }
    
        $usuario = Usuario::find($idUsuario);
    
        if (empty($usuario)) {
            http_response_code(204); // No Content
            return;
        }
        $usuario->img = '';
        $usuario->password = '';
        $usuario->email = '';
        $usuario->direccion = '';

        http_response_code(200); // OK
        echo json_encode($usuario);
    }
}