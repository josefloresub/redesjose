<?php
// respuesta2.php

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoger los datos del formulario
    $idUsuario = $_POST['idUsuario'] ?? '';
    $login = $_POST['login'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $nombres = $_POST['nombres'] ?? '';
    $fechaNacimiento = $_POST['fechaNacimiento'] ?? '';

    // Crear un array asociativo con los datos
    $datosUsuario = [
        'idUsuario' => $idUsuario,
        'login' => $login,
        'apellido' => $apellido,
        'nombres' => $nombres,
        'fechaNacimiento' => $fechaNacimiento
    ];

    // Convertir el array a JSON
    $jsonResponse = json_encode($datosUsuario, JSON_PRETTY_PRINT);

    // Establecer el tipo de contenido como JSON
    header('Content-Type: application/json');

    // Devolver el JSON como respuesta
    echo $jsonResponse;
    exit; // Terminar la ejecución del script
} else {
    // Si no es una solicitud POST, devolver un error
    header('HTTP/1.1 405 Method Not Allowed');
    echo json_encode(['error' => 'Método no permitido']);
    exit; // Terminar la ejecución del script
}
?>