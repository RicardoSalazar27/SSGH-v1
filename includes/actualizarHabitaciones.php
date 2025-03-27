<?php

// CRON JOB que actuliza el estado de las habitaciones reservadas y por reservar

$conexion = new mysqli("localhost", "usuario", "contraseña", "base_datos");

if ($conexion->connect_error) {
    die("Conexión fallida: " . $conexion->connect_error);
}

// Ejecutar el procedimiento almacenado
$sql = "CALL actualizar_estados_habitaciones()";
if ($conexion->query($sql) === TRUE) {
    echo "Estados de habitaciones actualizados.";
} else {
    echo "Error al actualizar estados: " . $conexion->error;
}

$conexion->close();

?>