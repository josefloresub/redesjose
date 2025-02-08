<?php
// Conexión a la base de datos
$host = 'localhost';
$dbname = 'abm_celulares';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error al conectar a la base de datos: " . $e->getMessage());
}

// Verificar si se proporcionó un ID válido
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Eliminar el registro de la base de datos
    $stmt = $pdo->prepare("DELETE FROM celulares WHERE id = ?");
    $stmt->execute([$id]);

    // Redirigir de vuelta a la página principal
    header("Location: abm.php");
    exit;
} else {
    die("ID no válido.");
}
?>