<?php

namespace Controllers;

use Model\Auditoria;
use Model\Categoria_Producto;
use Model\Hotel;
use Model\Producto;
use Model\Usuario;
use MVC\Router;

class CatalogoProductosController{

    public static function index(Router $router){
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
        $categoriasProductos = Categoria_Producto::all();
        
        // Render a la vista 
        $router->render('admin/punto_de_venta/catalogo_de_productos/index', [
            'titulo' => 'Productos/Servicios',
            'usuario' => $usuario,
            'categoriasProductos' => $categoriasProductos,
            'hotel' => $hotel
        ]);
    }

    public static function listar() {
        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        // Obtener todos los niveles
        $productos = Producto::all();

        foreach($productos as $producto){
            $categoria = Categoria_Producto::find($producto->categoria_producto_id);

            $producto->categoria_producto_id = $categoria;
        }
    
        // Responder con los datos o con un mensaje si no hay registros
        if (empty($productos)) {
            http_response_code(204); // 204 No Content cuando no hay datos
            $respuesta = [
                'tipo' => 'info',
                'titulo' => 'Sin contenido',
                'mensaje' => 'No hay productos registrados'
            ];
            echo json_encode($respuesta);
        } else {
            http_response_code(200); // 200 OK
            echo json_encode($productos);
        }
    }

    public static function obtener($id) {
        is_auth();
    
        // Establecer los headers al inicio
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
    
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            // Buscar el nivel
            $producto = Producto::find($id);
    
            // Verificar si se encontró el producto
            if (!$producto) {
                http_response_code(404);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'No encontrado',
                    'mensaje' => 'El producto no existe'
                ];
                echo json_encode($respuesta);
                exit;
            }
    
            // Responder con el objeto encontrado
            http_response_code(200);
            echo json_encode($producto);
        }
    }

    public static function actualizar($id){

        is_auth();
        $producto = Producto::find($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            if( !$producto ){
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => "El producto no existe."
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
            if (!empty($_FILES['foto']['tmp_name'])) {
                $rutaDocumentoAnterior = "$carpeta_imagenes/{$producto->foto}.png";
    
                // Eliminar la imagen anterior si existe
                if (!empty($producto->foto) && file_exists($rutaDocumentoAnterior)) {
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
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDocumento)) {
                    $producto->foto = pathinfo($nombreDocumento, PATHINFO_FILENAME); // Guardar solo el nombre sin extensión
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

            // Si se subió una nueva imagen, aseguramos que se guarde en los datos a actualizar
            if (empty($_FILES['foto']['tmp_name'])) {
                unset($datosActualizar['foto']); // Elimina la clave 'img' para no modificar el campo en la BD
            }
    
            $producto->sincronizar($datosActualizar);
            $resultado = $producto->guardar();
    
            if( $resultado ){
                // Ahora puedes usar el $id que viene de la URL
                $usuarioId = $_SESSION['id'];  // Asegúrate que $_SESSION['id'] tenga un valor válido
                $auditoria = new Auditoria();
                $registro = $id;  // Si id_registro_afectado es NULL, esto está bien
                date_default_timezone_set("America/Mexico_City");
                $fechaHora = date('Y-m-d H:i:s');  // Esto devuelve la fecha y hora actuales en formato "YYYY-MM-DD HH:MM:SS"
                $datos = [
                    'id_usuario' => $usuarioId,
                    'accion' => 'Editar',
                    'tabla_afectada' => 'Productos',
                    'id_registro_afectado' => $registro,
                    'detalle' => "Edito Producto $registro",
                    'fecha_hora' => $fechaHora 
                ];
                
                $auditoria->sincronizar($datos);
                $auditoria->guardar();
                $respuesta = [
                    'tipo' => 'success',
                    'titulo' => 'Actualizado',
                    'mensaje' => "El producto ha sido actualizado correctamente."
                ];
                echo json_encode($respuesta);
                exit;
            } else {
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => "Hubo un error al actualizar el producto."
                ];
                echo json_encode($respuesta);
                exit;
            }
        }
    }

    public static function eliminar($id){
        is_auth();
        if ($_SERVER['REQUEST_METHOD'] === 'DELETE'){

            $producto = Producto::find($id);
            if( !$producto ){
                $respuesta = [
                    'tipo' => 'error',  // Cambié el tipo a 'success' porque el mensaje era de error
                    'titulo' => 'Error',
                    'mensaje' => "El producto no existe."
                ];
                echo json_encode($respuesta);
                exit;
            }

            // Carpeta donde se guardarán las imágenes
            $carpeta_imagenes = '../public/build/img';
            if (!is_dir($carpeta_imagenes)) {
                mkdir($carpeta_imagenes, 0755, true);
            }

            $rutaDocumentoAnterior = "$carpeta_imagenes/{$producto->foto}.png";
            
            // Eliminar la imagen anterior si existe
            if (!empty($producto->foto) && file_exists($rutaDocumentoAnterior)) {
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

            $resultado = $producto->eliminar();

            if( $resultado ){
                // Ahora puedes usar el $id que viene de la URL
                $usuarioId = $_SESSION['id'];  // Asegúrate que $_SESSION['id'] tenga un valor válido
                $auditoria = new Auditoria();
                $registro = $id;  // Si id_registro_afectado es NULL, esto está bien
                date_default_timezone_set("America/Mexico_City");
                $fechaHora = date('Y-m-d H:i:s');  // Esto devuelve la fecha y hora actuales en formato "YYYY-MM-DD HH:MM:SS"
                $datos = [
                    'id_usuario' => $usuarioId,
                    'accion' => 'Eliminar',
                    'tabla_afectada' => 'Productos',
                    'id_registro_afectado' => $registro,
                    'detalle' => "Elimino Producto $registro",
                    'fecha_hora' => $fechaHora 
                ];
                
                $auditoria->sincronizar($datos);
                $auditoria->guardar();
                $respuesta = [
                    'tipo' => 'success',  // Cambié el tipo a 'success' porque el mensaje era de error
                    'titulo' => 'Eliminado',
                    'mensaje' => "El producto con ID $id ha sido eliminado correctamente."
                ];
                echo json_encode($respuesta);
                exit;
            } else {
                $respuesta = [
                    'tipo' => 'error',  // Cambié el tipo a 'success' porque el mensaje era de error
                    'titulo' => 'Error',
                    'mensaje' => "Hubo un error al eliminar el producto."
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
            if (empty($_POST['codigo_barras'])) {
                http_response_code(400);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'El codigo es obligatorio'
                ];
                echo json_encode($respuesta);
                exit;
            }
    
            // Verificar si el email ya existe
            $existeProducto = Producto::where('codigo_barras', $_POST['codigo_barras']);
            if ($existeProducto) {
                http_response_code(400);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Ooops...',
                    'mensaje' => 'Producto existente'
                ];
                echo json_encode($respuesta);
                exit;
            }
    
            // Crear nuevo producto
            $producto = new Producto();
            $producto->sincronizar($_POST);
    
            // Carpeta donde se guardarán las imágenes
            $carpeta_imagenes = '../public/build/img';
            if (!is_dir($carpeta_imagenes)) {
                mkdir($carpeta_imagenes, 0755, true); // Crear la carpeta si no existe
            }
    
            //debuguear($_FILES);
            // Manejo de la imagen (solo si se sube una nueva)
            if (!empty($_FILES['foto']['tmp_name'])) {
                // Ruta de la imagen anterior (si existe)
                $rutaDocumentoAnterior = "$carpeta_imagenes/{$producto->foto}.png";
    
                // Eliminar la imagen anterior si existe
                if (!empty($producto->foto) && file_exists($rutaDocumentoAnterior)) {
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
                //debuguear($nombreDocumento);
    
                // Ruta final para guardar la imagen
                $rutaDocumento = "$carpeta_imagenes/$nombreDocumento.png"; // Añadir la extensión al guardar
                //debuguear($rutaDocumento);
    
                // Mover el archivo a la carpeta de imágenes
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $rutaDocumento)) {
                    // Guardar solo el nombre sin la extensión en la base de datos
                    $producto->foto = $nombreDocumento;
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
            //debuguear($producto);
    
            // Guardar el producto en la base de datos
            $resultado = $producto->guardar();
            $productoA = Producto::where('codigo_barras', $producto->codigo_barras);
    
            // Responder según el resultado de la creación del producto
            if ($resultado) {
                $usuarioId = $_SESSION['id'];  // Asegúrate que $_SESSION['id'] tenga un valor válido
                $auditoria = new Auditoria();
                $registro = $productoA->id;  // Si id_registro_afectado es NULL, esto está bien
                date_default_timezone_set("America/Mexico_City");
                $fechaHora = date('Y-m-d H:i:s');  // Esto devuelve la fecha y hora actuales en formato "YYYY-MM-DD HH:MM:SS"
                $datos = [
                    'id_usuario' => $usuarioId,
                    'accion' => 'CREAR',
                    'tabla_afectada' => 'Productos',
                    'id_registro_afectado' => $registro,
                    'detalle' => "Creo Producto $registro",
                    'fecha_hora' => $fechaHora 
                ];
                
                $auditoria->sincronizar($datos);
                $auditoria->guardar();

                $respuesta = [
                    'tipo' => 'success',
                    'titulo' => 'Creado',
                    'mensaje' => 'Producto creado correctamente'
                ];
            } else {
                http_response_code(500);
                $respuesta = [
                    'tipo' => 'error',
                    'titulo' => 'Error',
                    'mensaje' => 'Hubo un problema al crear el producto'
                ];
            }
    
            echo json_encode($respuesta);
            exit;
        }
    }
}

?>