<?php

namespace Controllers;

use Classes\Email;
use Model\Hotel;
use Model\Usuario;
use MVC\Router;

class AuthController {
    public static function login(Router $router) {

        $hotel = Hotel::get(1);

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $usuario = Usuario::where('email', $_POST['email']);
            
            if(!$usuario){
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'El usuario no existente'
                ];
                echo json_encode($respuesta);
                exit;
            } elseif(!$usuario->estatus){
                $respuesta= $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'El usuario no esta confirmado'
                ];
                echo json_encode($respuesta);
                exit;
            } else{
                //El usuario existe
                if( password_verify($_POST['password'], $usuario->password) ) {
                     // Iniciar la sesión
                     session_start();    
                     $_SESSION['id'] = $usuario->id;
                     $_SESSION['nombre'] = $usuario->nombre;
                     $_SESSION['apellido'] = $usuario->apellido;
                     $_SESSION['email'] = $usuario->email;
                     $_SESSION['rol_id'] = $usuario->rol_id;
                     
                    $respuesta = [
                        'autorizado' => 1
                    ];
                    echo json_encode($respuesta);
                    exit;
                } else{
                    $respuesta = [
                        'tipo' => 'error',
                        'mensaje' => 'La contraseña es incorrecta'
                    ];
                    echo json_encode($respuesta);
                    exit;
                }
            }

        }
        
        // Render a la vista 
        $router->render('auth/login', [
            'titulo' => 'Inicia Sesion Para Comenzar',
            'hotel' => $hotel
        ]);
    }

    public static function logout() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            session_start();
            $_SESSION = [];
            header('Location: /login');
        }
       
    }

    public static function registro(Router $router) {

        $hotel = $hotel = Hotel::get(1);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
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
            $usuario->hashPassword();
            $resultado = $usuario->guardar();
    
            if ($resultado) {
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
        
        // Renderizar la vista
        $router->render('auth/registro', [
            'titulo' => 'Crea una nueva cuenta',
            'hotel' => $hotel
        ]);
    }
    
    public static function mensaje(Router $router) {

        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente'
        ]);
    }

    public static function mensaje2(Router $router) {
        $hotel = Hotel::get(1);

        $router->render('auth/mensajeOlvide', [
            'titulo' => 'Contacta al adminisrador del Hotel para que te genere una nueva contraseña',
            'hotel' => $hotel
        ]);
    }

    // public static function olvide(Router $router) {
    //     $alertas = [];

    //     $hotel = Hotel::get(1);
        
    //     if($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $usuario = new Usuario($_POST);
    //         $alertas = $usuario->validarEmail();

    //         if(empty($alertas)) {
    //             // Buscar el usuario
    //             $usuario = Usuario::where('email', $usuario->email);

    //             if($usuario && $usuario->confirmado) {

    //                 // Generar un nuevo token
    //                 $usuario->crearToken();
    //                 unset($usuario->password2);

    //                 // Actualizar el usuario
    //                 $usuario->guardar();

    //                 // Enviar el email
    //                 $email = new Email( $usuario->email, $usuario->nombre, $usuario->token );
    //                 $email->enviarInstrucciones();


    //                 // Imprimir la alerta
    //                 // Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');

    //                 $alertas['exito'][] = 'Hemos enviado las instrucciones a tu email';
    //             } else {
                 
    //                 // Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');

    //                 $alertas['error'][] = 'El Usuario no existe o no esta confirmado';
    //             }
    //         }
    //     }

    //     // Muestra la vista
    //     $router->render('auth/olvide', [
    //         'titulo' => 'Olvide mi Password',
    //         'hotel' => $hotel
    //     ]);
    // }

    // public static function reestablecer(Router $router) {

    //     $token = s($_GET['token']);

    //     $token_valido = true;

    //     if(!$token) header('Location: /');

    //     // Identificar el usuario con este token
    //     $usuario = Usuario::where('token', $token);

    //     if(empty($usuario)) {
    //         Usuario::setAlerta('error', 'Token No Válido, intenta de nuevo');
    //         $token_valido = false;
    //     }


    //     if($_SERVER['REQUEST_METHOD'] === 'POST') {

    //         // Añadir el nuevo password
    //         $usuario->sincronizar($_POST);

    //         // Validar el password
    //         $alertas = $usuario->validarPassword();

    //         if(empty($alertas)) {
    //             // Hashear el nuevo password
    //             $usuario->hashPassword();

    //             // Eliminar el Token
    //             $usuario->token = null;

    //             // Guardar el usuario en la BD
    //             $resultado = $usuario->guardar();

    //             // Redireccionar
    //             if($resultado) {
    //                 header('Location: /');
    //             }
    //         }
    //     }

    //     $alertas = Usuario::getAlertas();
        
    //     // Muestra la vista
    //     $router->render('auth/reestablecer', [
    //         'titulo' => 'Reestablecer Password',
    //         'alertas' => $alertas,
    //         'token_valido' => $token_valido
    //     ]);
    // }

    // public static function confirmar(Router $router) {
        
    //     $token = s($_GET['token']);

    //     if(!$token) header('Location: /');

    //     // Encontrar al usuario con este token
    //     $usuario = Usuario::where('token', $token);

    //     if(empty($usuario)) {
    //         // No se encontró un usuario con ese token
    //         Usuario::setAlerta('error', 'Token No Válido');
    //     } else {
    //         // Confirmar la cuenta
    //         $usuario->confirmado = 1;
    //         $usuario->token = '';
    //         unset($usuario->password2);
            
    //         // Guardar en la BD
    //         $usuario->guardar();

    //         Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
    //     }

    //     $router->render('auth/confirmar', [
    //         'titulo' => 'Confirma tu cuenta DevWebcamp',
    //         'alertas' => Usuario::getAlertas()
    //     ]);
    // }
}