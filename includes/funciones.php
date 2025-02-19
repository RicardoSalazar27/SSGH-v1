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

function respuesta($tipo, $titulo, $mensaje){
    return [
        "tipo" => $tipo,
        "titulo" => $titulo,
        "mensaje" => $mensaje
    ];
}

