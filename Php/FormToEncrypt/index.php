<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de Encriptación</title>
</head>
<body>
    <h1>Resultados de Encriptación</h1>
    <?php
    // Verificar si el formulario se ha enviado
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Verificar si la variable 'clave' está definida
        if (isset($_POST['clave'])) {
            $clave = htmlspecialchars($_POST['clave']); // Evitar XSS

            // Mostrar la clave ingresada
            echo "<p><strong>Clave:</strong> $clave</p>";

            // Encriptar la clave en MD5
            $clave_md5 = md5($clave);
            echo "<p><strong>Clave encriptada en MD5 (128 bits o 16 pares hexadecimales):</strong> $clave_md5</p>";

            // Encriptar la clave en SHA1
            $clave_sha1 = sha1($clave);
            echo "<p><strong>Clave encriptada en SHA1 (160 bits o 20 pares hexadecimales):</strong> $clave_sha1</p>";
        } else {
            echo "<p>Por favor, ingrese una clave.</p>";
        }
    } else {
        echo "<p>No se han recibido datos del formulario.</p>";
    }
    ?>
    <!-- Botón para volver al formulario -->
    <br>
    <a href="formulario_encriptar.html"><button>Volver</button></a>
</body>
</html>