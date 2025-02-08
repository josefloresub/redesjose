<?php
include 'conexion.php';session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}

// Verificar si la solicitud es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Recibir los datos del formulario
        $id = $_POST['id'];
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

        // Actualizar en la base de datos
        $stmt = $pdo->prepare("UPDATE celulares SET codigo = ?, nombre = ?, marca = ?, color = ?, precio = ?, fecha_compra = ?, archivo = ? WHERE id = ?");
        $stmt->execute([$codigo, $nombre, $marca, $color, $precio, $fecha_compra, $archivo, $id]);

        // Devolver una respuesta JSON indicando éxito
        echo json_encode(['success' => true]);
        exit;
    } catch (Exception $e) {
        // Devolver una respuesta JSON indicando error
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
} else {
    // Si no es una solicitud POST, devolver un error
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}
?>