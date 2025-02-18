<?php

namespace MVC;

class Router
{
    public array $getRoutes = [];
    public array $postRoutes = [];
    public array $putRoutes = [];
    public array $patchRoutes = [];
    public array $deleteRoutes = [];

   public function get($url, $fn) {
        $this->getRoutes[$url] = $fn;
    }

    public function post($url, $fn) {
        $this->postRoutes[$url] = $fn;
    }

    public function put($url, $fn) {
        $this->putRoutes[$url] = $fn;
    }

    public function patch($url, $fn) {
        $this->patchRoutes[$url] = $fn;
    }

    public function delete($url, $fn) {
        $this->deleteRoutes[$url] = $fn;
    }

    // public function comprobarRutas() {
    //         $url_actual = $_SERVER['PATH_INFO'] ?? '/';
    //         $method = $_SERVER['REQUEST_METHOD'];

    //         switch ($method) {
    //             case 'GET':
    //                 $fn = $this->getRoutes[$url_actual] ?? null;
    //                 break;
    //             case 'POST':
    //                 $fn = $this->postRoutes[$url_actual] ?? null;
    //                 break;
    //             case 'PUT':
    //                 $fn = $this->putRoutes[$url_actual] ?? null;
    //                 break;
    //             case 'PATCH':
    //                 $fn = $this->patchRoutes[$url_actual] ?? null;
    //                 break;
    //             case 'DELETE':
    //                 $fn = $this->deleteRoutes[$url_actual] ?? null;
    //                 break;
    //             default:
    //                 $fn = null;
    //         }

    //         if ($fn) {
    //             call_user_func($fn, $this);
    //         } else {
    //             echo "Página No Encontrada o Ruta no válida";
    //         }
    // }

    public function comprobarRutas() {
        $url_actual = $_SERVER['PATH_INFO'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'];
    
        $routes = match ($method) {
            'GET' => $this->getRoutes,
            'POST' => $this->postRoutes,
            'PUT' => $this->putRoutes,
            'PATCH' => $this->patchRoutes,
            'DELETE' => $this->deleteRoutes,
            default => []
        };
    
        // 🔹 1. Buscar coincidencia exacta
        if (isset($routes[$url_actual])) {
            return call_user_func($routes[$url_actual], $this);
        }
    
        // 🔹 2. Buscar coincidencia en rutas dinámicas
        // Si no se encuentra una ruta exacta, busca dinámicamente.
        foreach ($routes as $route => $callback) {
            $pattern = preg_replace('/\{([^\/]+)\}/', '([^/]+)', $route); // Convierte {id} en regex
            if (preg_match("#^$pattern$#", $url_actual, $matches)) {
                array_shift($matches); // Elimina el primer match (URL completa)
                return call_user_func_array($callback, $matches); // Pasa los valores capturados como argumentos
            }
        }
    
        // 🔹 3. Si no hay coincidencias, mostrar error
        echo "Página No Encontrada o Ruta no válida";
    }    
    

    public function render($view, $datos = [])
    {
        foreach ($datos as $key => $value) {
            $$key = $value; 
        }

        ob_start(); 

        include_once __DIR__ . "/views/$view.php";

        $contenido = ob_get_clean(); // Limpia el Buffer

        // Utilizar el Layout de acuerdo a la URL
        $url_actual = $_SERVER['PATH_INFO'] ?? '/';

        if(str_contains($url_actual, '/admin')){
            include_once __DIR__ . '/views/admin_layout.php';
        } else{
            include_once __DIR__ . '/views/layout.php';
        }
    }
}
