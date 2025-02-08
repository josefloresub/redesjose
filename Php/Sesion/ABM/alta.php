<?php include 'conexion.php';session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
} ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Agregar Celular</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Agregar Celular</h1>
        <form action="procesar_alta.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="codigo" class="form-label">Código</label>
                <input type="text" name="codigo" id="codigo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="marca" class="form-label">Marca</label>
                <input type="text" name="marca" id="marca" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="color" class="form-label">Color</label>
                <input type="text" name="color" id="color" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="precio" class="form-label">Precio</label>
                <input type="number" step="0.01" name="precio" id="precio" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fecha_compra" class="form-label">Fecha de Compra</label>
                <input type="date" name="fecha_compra" id="fecha_compra" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="archivo" class="form-label">Archivo</label>
                <input type="file" name="archivo" id="archivo" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</body>
</html>