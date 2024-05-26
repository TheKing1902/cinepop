<?php
// Conexión a la base de datos
$servername = "localhost";
$database = "cinepop";
$username = "root";
$password = "";
$conn = mysqli_connect($servername, $username, $password, $database);

// Verificar conexión
if (!$conn) {
    die("Conexión con la base de datos fallida: " . mysqli_connect_error());
}

// Verificar si los datos han sido enviados por el formulario
if (isset($_POST['nombreUsuario']) && isset($_POST['correo']) && isset($_POST['contra'])) {
    $nombreUsuario = $_POST['nombreUsuario'];
    $correo = $_POST['correo'];
    $contra = password_hash($_POST['contra'], PASSWORD_DEFAULT); // Encriptar la contraseña

    // Preparar la consulta para evitar inyección SQL
    $stmt = $conn->prepare('INSERT INTO usuarios (nombreUsuario, correo, contra) VALUES (?, ?, ?)');
    if ($stmt === false) {
        die('Error en la preparación de la consulta: ' . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param('sss', $nombreUsuario, $correo, $contra);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a una página de éxito
        header('Location: index.html');
        exit(); // Asegura que el script se detiene después de la redirección
    } else {
        // Mostrar el error en la ejecución de la consulta
        die('Error en la ejecución de la consulta: ' . htmlspecialchars($stmt->error));
    }

    // Cerrar la sentencia
    $stmt->close();
} else {
    // Mostrar error si los datos no fueron recibidos
    die('Error: Datos del formulario no recibidos.');
}

// Cerrar la conexión
mysqli_close($conn);
?>
