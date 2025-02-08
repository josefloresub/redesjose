<?php
include 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $marca = $_POST['marca'];
    $color = $_POST['color'];
    $precio = $_POST['precio'];
    $fecha_compra = $_POST['fecha_compra'];

    // Manejo del archivo principal
    $archivo = null;
    if ($_FILES['archivo']['error'] === UPLOAD_ERR_OK) {
        $archivo = $_FILES['archivo']['name'];
        move_uploaded_file($_FILES['archivo']['tmp_name'], "archivos/$archivo");
    }

    // Manejo del archivo PDF
    $pdf = null;
    if ($_FILES['pdf']['error'] === UPLOAD_ERR_OK) {
        $pdf = $_FILES['pdf']['name'];
        move_uploaded_file($_FILES['pdf']['tmp_name'], "pdfs/$pdf");
    }

    // Insertar en la base de datos
    $stmt = $pdo->prepare("INSERT INTO celulares (codigo, nombre, marca, color, precio, fecha_compra, archivo, pdf) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$codigo, $nombre, $marca, $color, $precio, $fecha_compra, $archivo, $pdf]);

    header("Location: abm.php");
    exit;
}
?>