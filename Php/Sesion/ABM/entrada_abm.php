<?php
session_start();

// Verificar si se solicita cerrar sesión
if (isset($_GET['accion']) && $_GET['accion'] === 'cerrar_sesion') {
    session_destroy();
    header("Location: ../index.php");
    exit;
}

// Resto del código para validar la sesión y cargar archivos
?>