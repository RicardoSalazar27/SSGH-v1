<?php

namespace Controllers;
use MVC\Router;

class Erro500Controller {
    public static function index(Router $router) {

        // Render a la vista 
        $router->render('auth/error', [
            'titulo' => 'Inicia Sesion Para Comenzar'
        ]);
    }
}