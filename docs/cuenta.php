<?php
session_start();

// Configuración de la conexión a la base de datos
$servername = "localhost";
$database = "id22213298_cinepop";
$username = "id22213298_cinepop";
$password = "Cinepop2667??";
$conn = mysqli_connect($servername, $username, $password, $database);

// Verificar conexión
if (!$conn) {
    die("Conexión con la base de datos fallida: " . mysqli_connect_error());
}

// Funciones para mensajes flash
function set_flash_message($type, $message) {
    $_SESSION['flash_message'] = ['type' => $type, 'message' => $message];
}

function display_flash_message() {
    if (isset($_SESSION['flash_message'])) {
        $flash = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        echo '<div class="alert alert-' . htmlspecialchars($flash['type']) . '">' . htmlspecialchars($flash['message']) . '</div>';
    }
}

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id'])) {
    header('Location: inicio_sesion.html');
    exit();
}

// Recuperar información del usuario desde la base de datos
$user_id = $_SESSION['id'];
$query = $conn->prepare('SELECT nombreUsuario, correo, imagenSeleccionada FROM usuarios WHERE id = ?');
$query->bind_param('i', $user_id);
$query->execute();
$query->bind_result($nombreUsuario, $correo, $imagenSeleccionada);
$query->fetch();
$query->close();

// Procesar actualización de información del perfil
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['nombreUsuario']) && isset($_POST['correo']) && isset($_POST['imagenSeleccionada'])) {
        $nombreUsuario = $_POST['nombreUsuario'];
        $correo = $_POST['correo'];
        $imagenSeleccionada = $_POST['imagenSeleccionada'];

        $updateQuery = $conn->prepare('UPDATE usuarios SET nombreUsuario = ?, correo = ?, imagenSeleccionada = ? WHERE id = ?');
        $updateQuery->bind_param('sssi', $nombreUsuario, $correo, $imagenSeleccionada, $user_id);

        if ($updateQuery->execute()) {
            set_flash_message('success', 'Información actualizada correctamente.');
            $_SESSION['nombreUsuario'] = $nombreUsuario;
            $_SESSION['correo'] = $correo;
        } else {
            set_flash_message('error', 'Error al actualizar la información.');
        }
        $updateQuery->close();
    }
    header('Location: perfil.php');
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        #imageContainer img {
            max-width: 100%;
            height: auto;
            display: none;
        }
    </style>
</head>

<body>
    <nav class="navbar bg-dark text-white">
        <div class="container-fluid">
            <a class="navbar-brand" href="inicio.html">
                <img src="cinepop.png" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
                Mi cuenta
            </a>
        </div>
    </nav>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php display_flash_message(); ?>
                <div class="card mt-5">
                    <div class="card-header bg-dark text-white text-center">
                        <h4>Mi cuenta</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <!-- Profile Image -->
                                <img id="profileImage" src="https://via.placeholder.com/300?text=Imagen+<?php echo $imagenSeleccionada; ?>" class="rounded-circle img-fluid" alt="Profile Picture">
                                
                                <!-- Image Selector -->
                                <div class="mt-3">
                                    <label for="imageSelector" class="form-label">Selecciona una imagen:</label>
                                    <select id="imageSelector" name="imagenSeleccionada" class="form-select" form="updateForm">
                                        <option value="1" <?php if($imagenSeleccionada == 1) echo 'selected'; ?>>Opción 1</option>
                                        <option value="2" <?php if($imagenSeleccionada == 2) echo 'selected'; ?>>Opción 2</option>
                                        <option value="3" <?php if($imagenSeleccionada == 3) echo 'selected'; ?>>Opción 3</option>
                                        <option value="4" <?php if($imagenSeleccionada == 4) echo 'selected'; ?>>Opción 4</option>
                                    </select>
                                    
                                    <!-- Image Container -->
                                    <div id="imageContainer" class="mt-3">
 <img id="image1" src="images/imagen1.png" alt="Imagen 1">
<img id="image2" src="images/imagen 2.png" alt="Imagen 2">
<img id="image3" src="images/imagen3.png" alt="Imagen 3">
<img id="image4" src="images/imagen4.png" alt="Imagen 4">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <h1 class="card-title"><?php echo htmlspecialchars($nombreUsuario); ?></h1>
                                <p class="card-text">
                                    <strong>Email:</strong> <?php echo htmlspecialchars($correo); ?>
                                </p>
                                <form method="post" action="" id="id">
                                    <div class="mb-3">
                                        <label for="nombreUsuario" class="form-label">Nombre de Usuario</label>
                                        <input type="text" class="form-control" id="nombreUsuario" name="nombreUsuario" value="<?php echo htmlspecialchars($nombreUsuario); ?>">
                                    </div>
                                    <div class="mb-3">
                                        <label for="correo" class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" id="correo" name="correo" value="<?php echo htmlspecialchars($correo); ?>">
                                    </div>
                                    <button type="submit" class="btn btn-primary" name="submit">Actualizar Información</button>
                                </form>
                            </div>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+6WT5LpvQ0a4gzX4Gpoc7NT1p6Oxk" crossorigin="anonymous"></script>
    <script>
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+6WT5LpvQ0a4gzX4Gpoc7NT1p6Oxk" crossorigin="anonymous"></script>
    <script>
        document.getElementById('imageSelector').addEventListener('change', function() {
            var selectedValue = this.value;
            var images = document.querySelectorAll('#imageContainer img');
            
            images.forEach(function(img) {
                img.style.display = 'none';
            });

            if (selectedValue !== '0') {
                document.getElementById('image' + selectedValue).style.display = 'block';
            }
        });

        // Mostrar la imagen seleccionada al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            var selectedValue = document.getElementById('imageSelector').value;
            if (selectedValue !== '0') {
                document.getElementById('image' + selectedValue).style.display = 'block';
            }
        });
    </script>
    </script>
</body>

</html>
