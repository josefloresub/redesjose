<?php
// index.php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: formulariodelogin.html");
    exit;
}

// Si está logeado, redirigir a ingresoalsistema.php
header("Location: ingresoalsistema.php");
exit;
?>