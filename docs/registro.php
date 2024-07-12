<?php
session_start(); // Iniciar la sesión al principio del archivo

// Habilitar la visualización de errores
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', '/storage/ssd5/298/22213298/php-error.log');

// Conexión a la base de datos en 000webhost
$servername = "localhost";
$database = "id22213298_cinepop";
$username = "id22213298_cinepop";
$password = "Cinepop2667??";

// Crear conexión
$conn = mysqli_connect($servername, $username, $password, $database);

// Verificar conexión
if (!$conn) {
    error_log("Conexión con la base de datos fallida: " . mysqli_connect_error());
    die("Conexión con la base de datos fallida.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Registro de usuario
    if (isset($_POST['nombreUsuario']) && isset($_POST['correo']) && isset($_POST['contra'])) {
        $nombreUsuario = $_POST['nombreUsuario'];
        $correo = $_POST['correo'];
        $contra = password_hash($_POST['contra'], PASSWORD_DEFAULT); // Encriptar la contraseña

        // Verificar si el correo ya está registrado
        $checkEmailQuery = $conn->prepare('SELECT id FROM usuarios WHERE correo = ?');
        if ($checkEmailQuery === false) {
            error_log('Error en la preparación de la consulta: ' . htmlspecialchars($conn->error));
            die('Error en la preparación de la consulta.');
        }

        $checkEmailQuery->bind_param('s', $correo);
        $checkEmailQuery->execute();
        $checkEmailQuery->store_result();

        if ($checkEmailQuery->num_rows > 0) {
            // El correo ya está registrado
            die('Error: El correo ya está registrado. Por favor, usa uno diferente.');
        } else {
            // El correo no está registrado, proceder con la inserción
            $stmt = $conn->prepare('INSERT INTO usuarios (nombreUsuario, correo, contra) VALUES (?, ?, ?)');
            if ($stmt === false) {
                error_log('Error en la preparación de la consulta: ' . htmlspecialchars($conn->error));
                die('Error en la preparación de la consulta.');
            }

            $stmt->bind_param('sss', $nombreUsuario, $correo, $contra);

            // Ejecutar la consulta
            if ($stmt->execute()) {
                // Almacenar la información del usuario en la sesión
                $_SESSION['nombreUsuario'] = $nombreUsuario;
                $_SESSION['correo'] = $correo;

                // Redirigir a una página de éxito o bienvenida
                header('Location: welcome.php');
                exit(); // Asegura que el script se detiene después de la redirección
            } else {
                // Mostrar el error en la ejecución de la consulta
                error_log('Error en la ejecución de la consulta: ' . htmlspecialchars($stmt->error));
                die('Error en la ejecución de la consulta.');
            }

            // Cerrar la sentencia
            $stmt->close();
        }

        // Cerrar la consulta de verificación de correo
        $checkEmailQuery->close();
    } elseif (isset($_POST['correo']) && isset($_POST['contra'])) {
        // Inicio de sesión de usuario
        $correo = $_POST['correo'];
        $contra = $_POST['contra'];

        // Consultar la contraseña almacenada para el correo dado
        $stmt = $conn->prepare('SELECT id, nombreUsuario, correo, contra FROM usuarios WHERE correo = ?');
        if ($stmt === false) {
            error_log('Error en la preparación de la consulta: ' . htmlspecialchars($conn->error));
            die('Error en la preparación de la consulta.');
        }

        $stmt->bind_param('s', $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            // El correo existe en la base de datos
            $stmt->bind_result($id, $nombreUsuario, $correo_db, $contra_db);
            $stmt->fetch();

            // Verificar si la contraseña ingresada coincide con la almacenada
            if (password_verify($contra, $contra_db)) {
                // Contraseña correcta, iniciar sesión y redirigir al usuario
                $_SESSION['id'] = $id;
                $_SESSION['nombreUsuario'] = $nombreUsuario;
                $_SESSION['correo'] = $correo_db;

                // Redirigir al usuario a la página de inicio
                header('Location: inicio.html');
                exit();
            } else {
                // Contraseña incorrecta
                echo 'Error: Contraseña incorrecta. <a href="inicio_sesion.html">Volver a intentar</a>';
            }
        } else {
            // El correo no está registrado
            echo 'Error: El correo electrónico no está registrado. <a href="registro.html">Regístrate aquí</a>';
        }

        // Cerrar la sentencia
        $stmt->close();
    } else {
        // Mostrar error si los datos no fueron recibidos
        die('Error: Datos del formulario no recibidos.');
    }
}

// Cerrar la conexión
mysqli_close($conn);
?>
