<?php
$servidor = "localhost";
$usuario = "root";
$password = "M7vQ2026Abc!92";
$base_datos = "paises_7A";

$conexion = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8mb4");
?>