<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class DashboardController {
    public static function index(Router $router) {
        session_start();
        debuguear($_SESSION);
        // Render a la vista 
        $router->render('admin/dashboard/index', [
            'titulo' => 'Panel de control'
        ]);
    }
}