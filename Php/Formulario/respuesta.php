<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados</title>
</head>
<body>
    <h1>Valores ingresados</h1>
    <?php
    // Verificar si el formulario se ha enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verificar si las variables 'nombre' y 'apellido' están definidas
        if (isset($_POST['nombre']) && isset($_POST['apellido'])) {
            $nombre = htmlspecialchars($_POST['nombre']); // Evitar XSS
            $apellido = htmlspecialchars($_POST['apellido']); // Evitar XSS

            // Mostrar los valores ingresados
            echo "<p><strong>Nombre:</strong> $nombre</p>";
            echo "<p><strong>Apellido:</strong> $apellido</p>";
        } else {
            echo "<p>Por favor, completa todos los campos del formulario.</p>";
        }
    } else {
        echo "<p>No se han recibido datos del formulario.</p>";
    }
    ?>
    <!-- Botón para cerrar y volver al formulario -->
    <br>
    <a href="formulario.html"><button>Cerrar</button></a>
</body>
</html>