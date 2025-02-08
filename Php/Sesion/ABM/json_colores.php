<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}
// json_colores.php

// Lista de colores disponibles
$colores = [
    "Rojo",
    "Azul",
    "Verde",
    "Negro",
    "Plateado",
    "Gris",
    "Blanco"
];

// Devolver los colores en formato JSON
header('Content-Type: application/json');
echo json_encode($colores);
?>