<?php
// ingresoalsistema.php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = md5($_POST['contrasena']); // Usar password_verify() en producción

    // Verificar credenciales
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ? AND contrasena = ?");
    $stmt->execute([$usuario, $contrasena]);
    $usuario_db = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario_db) {
        // Iniciar sesión
        $_SESSION['usuario'] = $usuario_db['nombre_usuario'];
        $_SESSION['contador_sesion'] = $usuario_db['contador_sesion'] + 1;

        // Actualizar contador de sesiones
        $stmt = $pdo->prepare("UPDATE usuarios SET contador_sesion = ? WHERE id = ?");
        $stmt->execute([$_SESSION['contador_sesion'], $usuario_db['id']]);
    } else {
        header("Location: formulariodelogin.html");
        exit;
    }
}

// Mostrar página de acceso permitido
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso Permitido</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .info-sesion {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .info-sesion h2 {
            margin-top: 0;
        }
        .info-sesion p {
            margin: 5px 0;
        }
        .botones {
            margin-top: 20px;
        }
        .botones a {
            display: inline-block;
            padding: 10px 20px;
            margin-right: 10px;
            background-color: #2c3e50;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .botones a:hover {
            background-color: #1a252f;
        }
    </style>
</head>
<body>
    <h1>Acceso permitido</h1>
    <div class="info-sesion">
        <h2>Información de Sesión</h2>
        <p><strong>Identificador de sesión:</strong> <?= session_id() ?></p>
        <p><strong>Nombre de usuario:</strong> <?= htmlspecialchars($_SESSION['usuario']) ?></p>
        <p><strong>Contador de sesión:</strong> <?= htmlspecialchars($_SESSION['contador_sesion']) ?></p>
    </div>
    <div class="botones">
        <a href="ABM/abm.php">Ingrese a la aplicación</a>
        <a href="cerrarsesion.php">Cerrar sesión</a>
    </div>
</body>
</html>