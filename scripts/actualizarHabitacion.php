<?php
require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Cargar variables de entorno
$dotenv = Dotenv::createImmutable(__DIR__ . '/../includes');
$dotenv->safeLoad();

// Conexión
require __DIR__ . '/../includes/database.php';

date_default_timezone_set('America/Mexico_City');

$now = date('Y-m-d H:i:s');
$today = date('Y-m-d');
$hora = (int) date('H');
$minuto = (int) date('i');

// Función para ejecutar bloques
function ejecutarBloque($db, $sql, $bloque) {
    if ($db->query($sql)) {
        echo "[" . date('Y-m-d H:i:s') . "] Bloque $bloque ejecutado correctamente.\n";
    } else {
        echo "[" . date('Y-m-d H:i:s') . "] Error en Bloque $bloque: " . $db->error . "\n";
    }
}

// === BLOQUE 0 ===
// A las 00:01 AM → Disponible (1) o Limpieza (6) → Reservación (4)
if ($hora === 0 && $minuto < 10) {
    $sql = "
        UPDATE Habitaciones h
        JOIN Reservas_Habitaciones rh ON h.id = rh.ID_habitacion
        JOIN Reservas r ON rh.ID_reserva = r.ID_reserva
        SET h.id_estado_habitacion = 4
        WHERE DATE(r.fecha_entrada) = '$today'
          AND h.id_estado_habitacion IN (1, 6)
          AND r.ID_estado IN (1, 2);
    ";
    ejecutarBloque($db, $sql, 0);
}

// === BLOQUE 1 ===
// A las 13:00 → Limpieza (6) → Limpieza Urgente (8)
if ($hora === 13 && $minuto < 10) {
    $sql = "
        UPDATE Habitaciones h
        JOIN Reservas_Habitaciones rh ON h.id = rh.ID_habitacion
        JOIN Reservas r ON rh.ID_reserva = r.ID_reserva
        SET h.id_estado_habitacion = 8
        WHERE DATE(r.fecha_entrada) = '$today'
          AND h.id_estado_habitacion = 6
          AND r.ID_estado IN (1, 2);
    ";
    ejecutarBloque($db, $sql, 1);
}

// === BLOQUE 2 ===
// Cada 10 minutos entre 12:00 y 14:00 → Disponible (1) → Reservación (4)
if ($hora >= 12 && $hora < 14) {
    $sql = "
        UPDATE Habitaciones h
        JOIN Reservas_Habitaciones rh ON h.id = rh.ID_habitacion
        JOIN Reservas r ON rh.ID_reserva = r.ID_reserva
        SET h.id_estado_habitacion = 4
        WHERE DATE(r.fecha_entrada) = '$today'
          AND h.id_estado_habitacion = 1
          AND r.ID_estado IN (1, 2);
    ";
    ejecutarBloque($db, $sql, 2);
}

// === BLOQUE 3 ===
// Desde las 14:01 → Disponible (1), Reservación (4) o Limpieza (6) → Reservación con retraso (5)
if ($hora === 14 && $minuto >= 1 || $hora > 14) {
    $sql = "
        UPDATE Habitaciones h
        JOIN Reservas_Habitaciones rh ON h.id = rh.ID_habitacion
        JOIN Reservas r ON rh.ID_reserva = r.ID_reserva
        SET h.id_estado_habitacion = 5
        WHERE DATE(r.fecha_entrada) = '$today'
          AND h.id_estado_habitacion IN (1, 4, 6)
          AND r.ID_estado IN (1, 2);
    ";
    ejecutarBloque($db, $sql, 3);
}
