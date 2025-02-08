<?php

session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit;
}
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>ABM Celulares</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <style>
        /* Estilo global */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa; /* Fondo claro */
            color: #212529; /* Texto oscuro */
            padding-top: 80px; /* Altura del encabezado */
            padding-bottom: 60px; /* Altura del pie de página */
        }

        /* Encabezado */
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #2c3e50; /* Azul oscuro elegante */
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: bold;
        }

        header .btn-group {
            display: flex;
            gap: 10px;
        }

        header .btn-light {
            background-color: #ecf0f1; /* Botones claros */
            border: 1px solid #bdc3c7;
            color: #2c3e50;
            transition: all 0.3s ease;
        }

        header .btn-light:hover {
            background-color: #bdc3c7;
            color: white;
        }

        /* Pie de página */
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #2c3e50; /* Azul oscuro elegante */
            padding: 10px 0;
            text-align: center;
            color: white;
            border-top: 1px solid #1a252f;
            z-index: 1000;
        }

        /* Mensaje de espera */
        #mensajeEspera {
            display: none;
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
            color: #1abc9c; /* Verde turquesa */
        }
    </style>
</head>
<body>
    <!-- Encabezado -->
    <header>
        <h1>ABM Celulares</h1>
        <div class="btn-group">
            <form method="POST" style="display: inline;" id="cargarDatosForm">
                <button type="submit" name="cargar_datos" class="btn btn-light">Cargar datos</button>
            </form>
            <form method="POST" style="display: inline;" id="borrarDatosForm">
                <button type="submit" name="borrar_datos" class="btn btn-light">Borrar datos</button>
            </form>
            <button type="button" class="btn btn-light" id="limpiarFiltrosBtn">Limpiar filtros</button>
            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#altaModal">Alta registros</button>
            <a href="/mi_pagina/Php/Sesion/cerrarsesion.php" class="btn btn-light">Cerrar Sesión</a>
        </div>
    </header>

    <div class="container mt-5">
        <!-- Formulario de Filtros -->
        <form id="filtroForm" method="POST">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Marca</th>
                        <th>Color</th>
                        <th>Precio</th>
                        <th>Fecha de compra</th>
                        <th>PDFs</th>
                        <th>Acciones</th>
                    </tr>
                    <!-- Fila de Filtros -->
                    <tr>
                        <td><input type="text" name="filtro_codigo" class="form-control" placeholder="Filtrar por código"></td>
                        <td><input type="text" name="filtro_nombre" class="form-control" placeholder="Filtrar por nombre"></td>
                        <td><input type="text" name="filtro_marca" class="form-control" placeholder="Filtrar por marca"></td>
                        <td>
                            <select name="filtro_color" class="form-select">
                                <option value="">Todos</option>
                                <option value="Rojo">Rojo</option>
                                <option value="Azul">Azul</option>
                                <option value="Verde">Verde</option>
                                <option value="Negro">Negro</option>
                                <option value="Plateado">Plateado</option>
                                <option value="Gris">Gris</option>
                                <option value="Blanco">Blanco</option>
                            </select>
                        </td>
                        <td><input type="number" step="0.01" name="filtro_precio" class="form-control" placeholder="Filtrar por precio"></td>
                        <td><input type="date" name="filtro_fecha_compra" class="form-control"></td>
                        <td colspan="2"></td>
                    </tr>
                </thead>
                <tbody id="tablaResultados">
                    <tr>
                        <td colspan="8" class="text-center">No hay datos disponibles. Presiona "Cargar datos".</td>
                    </tr>
                </tbody>
            </table>
        </form>
        <!-- Mensaje de espera -->
        <div id="mensajeEspera">Esperando respuesta...</div>
    </div>

    <!-- Pie de página -->
    <footer>
        <p>Pie de página</p>
    </footer>

    <!-- Modal para Alta -->
    <div class="modal fade" id="altaModal" tabindex="-1" aria-labelledby="altaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="altaModalLabel">Encabezado modal formulario alta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                            <select name="color" id="color" class="form-select" required>
                                <option value="">Selecciona un color</option>
                                <option value="Rojo">Rojo</option>
                                <option value="Azul">Azul</option>
                                <option value="Verde">Verde</option>
                                <option value="Negro">Negro</option>
                                <option value="Plateado">Plateado</option>
                                <option value="Gris">Gris</option>
                                <option value="Blanco">Blanco</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" step="0.01" name="precio" id="precio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_compra" class="form-label">Fecha de compra</label>
                            <input type="date" name="fecha_compra" id="fecha_compra" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="archivo" class="form-label">Selecciona un archivo:</label>
                            <input type="file" name="archivo" id="archivo" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Dar de alta</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Modificación -->
    <div class="modal fade" id="modificarModal" tabindex="-1" aria-labelledby="modificarModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modificarModalLabel">Encabezado modal formulario modificación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="modificacionForm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id">
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
                            <select name="color" id="color" class="form-select" required>
                                <option value="">Selecciona un color</option>
                                <option value="Rojo">Rojo</option>
                                <option value="Azul">Azul</option>
                                <option value="Verde">Verde</option>
                                <option value="Negro">Negro</option>
                                <option value="Plateado">Plateado</option>
                                <option value="Gris">Gris</option>
                                <option value="Blanco">Blanco</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="precio" class="form-label">Precio</label>
                            <input type="number" step="0.01" name="precio" id="precio" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="fecha_compra" class="form-label">Fecha de compra</label>
                            <input type="date" name="fecha_compra" id="fecha_compra" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="archivo" class="form-label">Selecciona un archivo (opcional):</label>
                            <input type="file" name="archivo" id="archivo" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Confirmación de Cambios -->
    <div class="modal fade" id="confirmacionModal" tabindex="-1" aria-labelledby="confirmacionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmacionModalLabel">Cambios realizados</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="confirmacionMensaje">
                    <!-- Aquí se mostrará el mensaje de confirmación -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Visualización de Archivo -->
    <div class="modal fade" id="archivoModal" tabindex="-1" aria-labelledby="archivoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="archivoModalLabel">Vista previa del archivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="archivoImagen" src="" alt="Archivo" style="display: none; max-width: 100%; height: auto;">
                    <iframe id="archivoPDF" src="" style="display: none; width: 100%; height: 500px;"></iframe>
                    <p id="noArchivoMessage" style="display: none;">Archivo no encontrado.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cargarDatosForm = document.getElementById('cargarDatosForm');
            const borrarDatosForm = document.getElementById('borrarDatosForm');
            const limpiarFiltrosBtn = document.getElementById('limpiarFiltrosBtn');
            const filtroForm = document.getElementById('filtroForm');
            const tablaResultados = document.getElementById('tablaResultados');
            const mensajeEspera = document.getElementById('mensajeEspera');

            // Mostrar los colores disponibles al cargar la página
            fetch('filtrar.php?colores=true')
                .then(response => response.json())
                .then(colores => {
                    const mensajeColores = colores.map(color => `("color:${color}")`).join(',');
                    alert(`{"Colores":[${mensajeColores}]}`);
                })
                .catch(error => console.error('Error:', error));

            // Función para cargar datos filtrados
            function cargarDatos() {
                const formData = new FormData(filtroForm);

                // Agregar un campo oculto para indicar que se está cargando datos
                formData.append('cargar_datos', 'true');

                // Mostrar el mensaje "Esperando respuesta"
                mensajeEspera.style.display = 'block';

                fetch('filtrar.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Ocultar el mensaje "Esperando respuesta" después de 2 segundos
                    setTimeout(() => {
                        mensajeEspera.style.display = 'none';

                        // Mostrar alerta con los celulares filtrados si hay filtros activos
                        if (Object.values(Object.fromEntries(formData)).some(value => value)) {
                            if (data.length > 0) {
                                const mensajeCelulares = data.map(celular => `("codigo":"${celular.codigo}","nombre":"${celular.nombre}","marca":"${celular.marca}","color":"${celular.color}","precio":"${celular.precio}","fechadecompra":"${celular.fecha_compra}")`).join(',');
                                alert(`{"Celulares":[${mensajeCelulares}]}`);
                            } else {
                                alert('{"Celulares":[]}');
                            }
                        }

                        // Actualizar la tabla con los datos filtrados solo después de cerrar el mensaje de alerta
                        actualizarTabla(data);
                    }, 2000); // Retrasar la ocultación del mensaje y el alerta
                })
                .catch(error => {
                    console.error('Error:', error);
                    mensajeEspera.style.display = 'none'; // Ocultar el mensaje "Esperando respuesta" en caso de error
                });
            }

            // Función para actualizar la tabla
            function actualizarTabla(data) {
                if (data.length > 0) {
                    let filas = '';
                    data.forEach(celular => {
                        filas += `
                            <tr>
                                <td>${celular.codigo}</td>
                                <td>${celular.nombre}</td>
                                <td>${celular.marca}</td>
                                <td>${celular.color}</td>
                                <td>${celular.precio}</td>
                                <td>${celular.fecha_compra}</td>
                                <td>
                                    ${celular.archivo ? `<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#archivoModal" data-archivo="${celular.archivo}">PDF</button>` : 'Sin archivo'}
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modificarModal"
                                            data-id="${celular.id}"
                                            data-codigo="${celular.codigo}"
                                            data-nombre="${celular.nombre}"
                                            data-marca="${celular.marca}"
                                            data-color="${celular.color}"
                                            data-precio="${celular.precio}"
                                            data-fecha-compra="${celular.fecha_compra}">
                                            Modificar
                                        </button>
                                        <a href="baja.php?id=${celular.id}" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este registro?')">Eliminar</a>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    tablaResultados.innerHTML = filas;
                } else {
                    tablaResultados.innerHTML = '<tr><td colspan="8" class="text-center">No hay datos disponibles.</td></tr>';
                }
            }

            // Evento para cargar datos al presionar el botón "Cargar datos"
            cargarDatosForm.addEventListener('submit', function (event) {
                event.preventDefault(); // Evitar el envío tradicional del formulario
                cargarDatos();
            });

            // Evento para borrar datos al presionar el botón "Borrar datos"
            borrarDatosForm.addEventListener('submit', function (event) {
                event.preventDefault(); // Evitar el envío tradicional del formulario
                tablaResultados.innerHTML = '<tr><td colspan="8" class="text-center">No hay datos disponibles.</td></tr>';
            });

            // Evento para limpiar los filtros
            limpiarFiltrosBtn.addEventListener('click', function () {
                filtroForm.reset(); // Limpiar todos los campos del formulario de filtros
            });

            // Manejar el evento cuando se abre la ventana modal de modificación
            const modificarModal = document.getElementById('modificarModal');
            modificarModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Botón que abrió la modal
                const id = button.getAttribute('data-id');
                const codigo = button.getAttribute('data-codigo');
                const nombre = button.getAttribute('data-nombre');
                const marca = button.getAttribute('data-marca');
                const color = button.getAttribute('data-color');
                const precio = button.getAttribute('data-precio');
                const fechaCompra = button.getAttribute('data-fecha-compra');

                const modalBody = modificarModal.querySelector('.modal-body');
                modalBody.querySelector('#id').value = id;
                modalBody.querySelector('#codigo').value = codigo;
                modalBody.querySelector('#nombre').value = nombre;
                modalBody.querySelector('#marca').value = marca;

                // Seleccionar el color correspondiente en el desplegable
                const colorSelect = modalBody.querySelector('#color');
                colorSelect.value = color;

                modalBody.querySelector('#precio').value = precio;
                modalBody.querySelector('#fecha_compra').value = fechaCompra;
            });

            // Manejar el envío del formulario de modificación
            const modificacionForm = document.getElementById('modificacionForm');
            modificacionForm.addEventListener('submit', function (event) {
                event.preventDefault(); // Evitar el envío tradicional del formulario

                const formData = new FormData(modificacionForm);

                fetch('procesar_modificacion.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Mostrar la modal de confirmación con los cambios realizados
                        const confirmacionModal = new bootstrap.Modal(document.getElementById('confirmacionModal'));
                        const confirmacionMensaje = document.getElementById('confirmacionMensaje');

                        // Construir el mensaje de confirmación
                        const mensaje = `
                            <p>Los siguientes cambios han sido realizados:</p>
                            <ul>
                                <li>Código: ${formData.get('codigo')}</li>
                                <li>Nombre: ${formData.get('nombre')}</li>
                                <li>Marca: ${formData.get('marca')}</li>
                                <li>Color: ${formData.get('color')}</li>
                                <li>Precio: ${formData.get('precio')}</li>
                                <li>Fecha de compra: ${formData.get('fecha_compra')}</li>
                                <li>Archivo: ${formData.get('archivo') ? 'Actualizado' : 'Sin cambios'}</li>
                            </ul>
                            <p>Los cambios no se reflejarán en la tabla hasta que presione "Cargar datos".</p>
                        `;

                        confirmacionMensaje.innerHTML = mensaje;
                        confirmacionModal.show();

                        // Cerrar la modal de modificación
                        const modificarModalInstance = bootstrap.Modal.getInstance(document.getElementById('modificarModal'));
                        modificarModalInstance.hide();
                    } else {
                        alert('Error al guardar los cambios. Por favor, inténtelo de nuevo.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('¿Está seguro de modificar el registro?');
                });
            });

            // Manejar el evento cuando se abre la ventana modal de visualización de archivo
            const archivoModal = document.getElementById('archivoModal');
            archivoModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Botón que abrió la modal
                const archivoPath = button.getAttribute('data-archivo'); // Ruta del archivo

                const archivoImagen = document.getElementById('archivoImagen');
                const archivoPDF = document.getElementById('archivoPDF');
                const noArchivoMessage = document.getElementById('noArchivoMessage');

                if (archivoPath) {
                    const extension = archivoPath.split('.').pop().toLowerCase();

                    if (extension === 'pdf') {
                        // Si es un PDF, mostrarlo en el iframe
                        archivoPDF.src = `archivos/${archivoPath}`;
                        archivoPDF.style.display = 'block';
                        archivoImagen.style.display = 'none';
                        noArchivoMessage.style.display = 'none';
                    } else if (['jpg', 'jpeg', 'png', 'gif'].includes(extension)) {
                        // Si es una imagen, mostrarla en el elemento <img>
                        archivoImagen.src = `archivos/${archivoPath}`;
                        archivoImagen.style.display = 'block';
                        archivoPDF.style.display = 'none';
                        noArchivoMessage.style.display = 'none';
                    } else {
                        // Si el tipo de archivo no es compatible
                        archivoImagen.style.display = 'none';
                        archivoPDF.style.display = 'none';
                        noArchivoMessage.style.display = 'block';
                    }
                } else {
                    // Si no hay archivo, mostrar el mensaje
                    archivoImagen.style.display = 'none';
                    archivoPDF.style.display = 'none';
                    noArchivoMessage.style.display = 'block';
                }
            });

            // Limpiar el contenido cuando se cierra la modal
            archivoModal.addEventListener('hidden.bs.modal', function () {
                const archivoImagen = document.getElementById('archivoImagen');
                const archivoPDF = document.getElementById('archivoPDF');
                const noArchivoMessage = document.getElementById('noArchivoMessage');

                archivoImagen.src = '';
                archivoImagen.style.display = 'none';
                archivoPDF.src = '';
                archivoPDF.style.display = 'none';
                noArchivoMessage.style.display = 'none';
            });
        });
    </script>
</body>
</html>