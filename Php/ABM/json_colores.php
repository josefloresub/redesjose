<?php
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