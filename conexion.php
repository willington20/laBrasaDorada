<?php
// ================================================
// CONEXION A LA BASE DE DATOS
// Cambia estos valores por los de tu servidor
// ================================================

define('DB_HOST', 'localhost');
define('DB_USER', 'root');        // Tu usuario MySQL
define('DB_PASS', '');            // Tu contrasena MySQL
define('DB_NAME', 'brasa_dorada');

function conectar() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $conn->set_charset('utf8mb4');
    if ($conn->connect_error) {
        die('Error de conexion: ' . $conn->connect_error);
    }
    return $conn;
}
?>
