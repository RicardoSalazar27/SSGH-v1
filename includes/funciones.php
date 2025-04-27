<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

function is_auth() : bool{
    if(!isset($_SESSION)){
        session_start();
    }
    return isset($_SESSION['nombre']) && !empty($_SESSION);
}

// function tiene_rol(String $rol_id): bool {
//     if(!$_SESSION['rol_id'] || $_SESSION['rol_id'] !== $rol_id){
//         header('Location: /');
//     }
//     return true;
// }

function tiene_rol(array $roles): bool {
    // Verificar si el rol del usuario está en el arreglo de roles permitido
    if (!isset($_SESSION['rol_id']) || !in_array($_SESSION['rol_id'], $roles)) {
        // Redirigir si no tiene el rol necesario
        header('Location: /acceso-denegado');  // O la ruta que quieras para acceso denegado
        exit;
    }
    return true;
}


function respuesta($tipo, $titulo, $mensaje){
    return [
        "tipo" => $tipo,
        "titulo" => $titulo,
        "mensaje" => $mensaje
    ];
}

function limpiarClavesRecursivamente($array) {
    $nuevo = [];
    foreach ($array as $clave => $valor) {
        $claveLimpia = trim($clave);
        $nuevo[$claveLimpia] = is_array($valor) ? limpiarClavesRecursivamente($valor) : $valor;
    }
    return $nuevo;
}

