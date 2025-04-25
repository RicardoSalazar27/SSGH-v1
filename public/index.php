<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\APIGananciasNetas;
use Controllers\AuditoriaController;
use MVC\Router;
use Controllers\AuthController;
use Controllers\CatalogoProductosController;
use Controllers\CategoriasController;
use Controllers\ClientesController;
use Controllers\DashboardController;
use Controllers\HabitacionController;
use Controllers\informacionController;
use Controllers\NivelesController;
use Controllers\RecepcionController;
use Controllers\ReporteController;
use Controllers\ReservacionesController;
use Controllers\UsuariosController;
use Controllers\VenderProductosController;
use Controllers\VerificacionSalidasController;

$router = new Router();


// Login
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);

// Crear Cuenta
$router->get('/registro', [AuthController::class, 'registro']);
$router->post('/registro', [AuthController::class, 'registro']);

// Formulario de olvide mi password
// $router->get('/olvide', [AuthController::class, 'olvide']);
// $router->post('/olvide', [AuthController::class, 'olvide']);
// Confirmación de Cuenta
$router->get('/olvide', [AuthController::class, 'mensaje2']);

// // Colocar el nuevo password
// $router->get('/reestablecer', [AuthController::class, 'reestablecer']);
// $router->post('/reestablecer', [AuthController::class, 'reestablecer']);

// Confirmación de Cuenta
$router->get('/mensaje', [AuthController::class, 'mensaje']);
$router->get('/confirmar-cuenta', [AuthController::class, 'confirmar']);

// Area de Administracion
$router->get('/admin/index', [DashboardController:: class, 'index']);
$router->get('/admin/configuracion/informacion', [informacionController::class, 'index']);
$router->post('/admin/configuracion/informacion/actualizar', [informacionController::class, 'actualizar']);
$router->get('/admin/configuracion/habitaciones', [HabitacionController::class, 'index']);
$router->get('/admin/configuracion/categorias', [CategoriasController::class, 'index']);
$router->get('/admin/configuracion/niveles', [NivelesController::class, 'index']);
$router->get('/admin/usuarios', [UsuariosController::class, 'index']);
$router->get('/admin/clientes', [ClientesController::class, 'index']);
$router->get('/admin/puntodeventa/catalogo', [CatalogoProductosController::class, 'index']);
$router->get('/admin/puntodeventa/vender', [VenderProductosController::class, 'index']);
$router->get('/admin/puntodeventa/vender/reserva', [VenderProductosController::class, 'ventaReservacion']);
$router->get('/admin/puntodeventa/venta/directa', [VenderProductosController::class, 'ventaDirectaIndex']);
$router->get('/admin/salidas', [VerificacionSalidasController::class, 'index']);
$router->get('/admin/salidas/checkout', [VerificacionSalidasController::class, 'checkout']);
$router->get('/admin/reservaciones', [ReservacionesController::class, 'index']);
$router->get('/admin/recepcion', [RecepcionController::class, 'index']);
$router->get('/admin/recepcion/habitacion', [RecepcionController::class, 'checkin']);
$router->get('/admin/registro-actividades', [AuditoriaController::class, 'index']);
$router->get('/admin/reporte-diario', [ReporteController::class, 'indexReporteDiario']);
$router->get('/admin/reporte-mensual', [ReporteController::class, 'indexReporteMensual']);

// API'S
$router->get('/api/usuarios', [UsuariosController::class, 'listar']);
$router->post('/api/usuarios', [UsuariosController::class, 'crear']);
$router->delete('/api/usuarios/{id}', [UsuariosController::class, 'eliminar']);
$router->get('/api/usuarios/{id}', [UsuariosController::class, 'obtener']);
$router->get('/api/usuario/activo', [UsuariosController::class, 'usuarioActivo']);
$router->post('/api/usuarios/{id}', [UsuariosController::class, 'actualizar']);

$router->get('/api/niveles', [NivelesController::class, 'listar']);
$router->post('/api/niveles', [NivelesController::class, 'crear']);
$router->get('/api/niveles/{id}', [NivelesController::class, 'obtener']);
$router->delete('/api/niveles/{id}', [NivelesController::class, 'eliminar']);
$router->put('/api/niveles/{id}', [NivelesController::class, 'actualizar']);
$router->patch('/api/niveles/{id}', [NivelesController::class, 'actualizar']);

$router->get('/api/categorias', [CategoriasController::class, 'listar']);
$router->post('/api/categorias', [CategoriasController::class, 'crear']);
$router->get('/api/categorias/{id}', [CategoriasController::class, 'obtener']);
$router->delete('/api/categorias/{id}', [CategoriasController::class, 'eliminar']);
$router->put('/api/categorias/{id}', [CategoriasController::class, 'actualizar']);
$router->patch('/api/categorias/{id}', [CategoriasController::class, 'actualizar']);


$router->get('/api/estadoHabitaciones', [HabitacionController::class, 'listar_estado_habitaciones']);
$router->get('/api/habitaciones', [HabitacionController::class, 'listar']);
$router->post('/api/habitaciones', [HabitacionController::class, 'crear']);
$router->get('/api/habitaciones/{id}', [HabitacionController::class, 'obtener']);
$router->get('/api/habitaciones/disponibles/{fechainicio}/{fechafin}', [HabitacionController::class, 'disponibles']);
$router->delete('/api/habitaciones/{id}', [HabitacionController::class, 'eliminar']);
$router->put('/api/habitaciones/{id}', [HabitacionController::class, 'actualizar']);
$router->patch('/api/habitaciones/{id}', [HabitacionController::class, 'actualizar']);

$router->get('/api/clientes', [ClientesController::class, 'listar']);
$router->get('/api/clientes/{id}', [ClientesController::class, 'obtener']);
$router->get('/api/clientes/correo/{correo}', [ClientesController::class, 'obtenercorreo']);
$router->put('/api/clientes/{id}', [ClientesController::class, 'actualizar']);
$router->patch('/api/clientes/{id}', [ClientesController::class, 'actualizar']);
$router->delete('/api/clientes/{id}', [ClientesController::class, 'eliminar']);

$router->get('/api/productos', [CatalogoProductosController::class, 'listar']);
$router->get('/api/productos/{id}', [CatalogoProductosController::class, 'obtener']);
$router->post('/api/productos/{id}', [CatalogoProductosController::class, 'actualizar']);
$router->delete('/api/productos/{id}', [CatalogoProductosController::class, 'eliminar']);
$router->post('/api/productos', [CatalogoProductosController::class, 'crear']);

$router->get('/api/productos/codigo/{codigo_barras}', [VenderProductosController::class, 'obtenerProducto']);
$router->post('/api/productos/reservacion/vender', [VenderProductosController::class, 'registarVentasPorReservacion']);

$router->post('/api/reservaciones', [ReservacionesController::class, 'crear']);
$router->get('/api/reservaciones', [ReservacionesController::class, 'listar']);
$router->get('/api/reservaciones/{id}', [ReservacionesController::class, 'obtener']);
$router->patch('/api/reservaciones/{id}', [ReservacionesController::class, 'actualizar']);

$router->get('/api/ganancias', [APIGananciasNetas::class, 'gananciasPorPeriodo']);

$router->post('/api/reservacion/terminar', [VerificacionSalidasController::class,'terminarReservacion']);

$router->get('/api/registro-actividades', [AuditoriaController::class, 'RegistroActividades']);

$router->get('/api/reporte-diario/{usuario_id}/{fecha}', [ReporteController::class, 'obtenerReporteDiario']);
$router->get('/api/reporte-mensual/{usuario_id}/{mes}/{anio}', [ReporteController::class, 'obtenerReporteMensual']);

$router->comprobarRutas();