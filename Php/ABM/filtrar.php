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

// Variable para almacenar los datos de la tabla
$celulares = [];

// Si se solicitan los colores disponibles
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['colores'])) {
    // Lista de colores disponibles
    $colores = [
        "Rojo","Azul","Verde", "Negro","Plateado","Gris","Blanco"
    ];

    // Devolver los colores en formato JSON
    header('Content-Type: application/json');
    echo json_encode($colores);
    exit;
}

// Si se presiona el botón "Cargar datos"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cargar_datos'])) {
    // Construir la consulta SQL con filtros
    $sql = "SELECT * FROM celulares WHERE 1=1";
    $params = [];

    if (!empty($_POST['filtro_codigo'])) {
        $sql .= " AND codigo LIKE :codigo";
        $params[':codigo'] = '%' . $_POST['filtro_codigo'] . '%';
    }
    if (!empty($_POST['filtro_nombre'])) {
        $sql .= " AND nombre LIKE :nombre";
        $params[':nombre'] = '%' . $_POST['filtro_nombre'] . '%';
    }
    if (!empty($_POST['filtro_marca'])) {
        $sql .= " AND marca LIKE :marca";
        $params[':marca'] = '%' . $_POST['filtro_marca'] . '%';
    }
    if (!empty($_POST['filtro_color'])) {
        $sql .= " AND color = :color";
        $params[':color'] = $_POST['filtro_color'];
    }
    if (!empty($_POST['filtro_precio'])) {
        $sql .= " AND precio = :precio";
        $params[':precio'] = $_POST['filtro_precio'];
    }
    if (!empty($_POST['filtro_fecha_compra'])) {
        $sql .= " AND fecha_compra = :fecha_compra";
        $params[':fecha_compra'] = $_POST['filtro_fecha_compra'];
    }

    // Ejecutar la consulta
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $celulares = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los datos en formato JSON
    header('Content-Type: application/json');
    echo json_encode($celulares);
    exit;
}
?>