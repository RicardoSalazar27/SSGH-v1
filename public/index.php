<?php 

require_once __DIR__ . '/../includes/app.php';

use MVC\Router;
use Controllers\AuthController;
use Controllers\DashboardController;
use Controllers\informacionController;
use Controllers\NivelesController;
use Controllers\UsuariosController;

$router = new Router();


// Login
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// Crear Cuenta
$router->get('/registro', [AuthController::class, 'registro']);
$router->post('/registro', [AuthController::class, 'registro']);

// Formulario de olvide mi password
$router->get('/olvide', [AuthController::class, 'olvide']);
$router->post('/olvide', [AuthController::class, 'olvide']);

// Colocar el nuevo password
$router->get('/reestablecer', [AuthController::class, 'reestablecer']);
$router->post('/reestablecer', [AuthController::class, 'reestablecer']);

// Confirmación de Cuenta
$router->get('/mensaje', [AuthController::class, 'mensaje']);
$router->get('/confirmar-cuenta', [AuthController::class, 'confirmar']);

// Area de Administracion
$router->get('/admin/index', [DashboardController:: class, 'index']);
$router->get('/admin/configuracion/informacion', [informacionController::class, 'index']);
$router->post('/admin/configuracion/informacion/actualizar', [informacionController::class, 'actualizar']);
// $router->get('/admin/configuracion/habitaciones', [HabitacionesController::class, 'index']);
// $router->get('/admin/configuracion/categorias', [CategoriasController::class, 'index']);
$router->get('/admin/configuracion/niveles', [NivelesController::class, 'index']);
$router->get('/admin/usuarios', [UsuariosController::class, 'index']);
// $router->get('/admin/clientes', [ClientesController::class, 'index']);


// API'S
$router->get('/api/usuarios', [UsuariosController::class, 'listar']);
$router->post('/api/usuarios', [UsuariosController::class, 'crear']);
$router->delete('/api/usuarios/{id}', [UsuariosController::class, 'eliminar']);
$router->get('/api/usuarios/{id}', [UsuariosController::class, 'obtener']);
$router->post('/api/usuarios/{id}', [UsuariosController::class, 'actualizar']);

$router->get('/api/niveles', [NivelesController::class, 'listar']);
$router->post('/api/niveles', [NivelesController::class, 'crear']);
$router->get('/api/niveles/{id}', [NivelesController::class, 'obtener']);
$router->delete('/api/niveles/{id}', [NivelesController::class, 'eliminar']);
$router->put('/api/niveles/{id}', [NivelesController::class, 'actualizar']);
$router->patch('/api/niveles/{id}', [NivelesController::class, 'actualizar']);

$router->comprobarRutas();