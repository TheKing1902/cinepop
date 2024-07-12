<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
ini_set('log_errors', 1);
// Cambia esta ruta a una ruta permitida por open_basedir
ini_set('error_log', '/storage/ssd5/298/22213298/php-error.log');
$servername = "localhost";
$username = "id22213298_cinepop";
$password = "Cinepop2667??";
$dbname = "id22213298_cinepop";
// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$pelicula = $_POST['pelicula'];

// Si el nombre está vacío, asignar "Anónimo"
if (empty($nombre)) {
    $nombre = "Anónimo";
}

// Preparar y vincular
$stmt = $conn->prepare("INSERT INTO pelicula_db (nombre, nombre_peli) VALUES (?, ?)");
$stmt->bind_param("ss", $nombre, $pelicula);

// Ejecutar la consulta
if ($stmt->execute()) {
 header('Location: inicio.html');
} else {
    echo "Error: " . $stmt->error;
}

// Cerrar conexión
$stmt->close();
$conn->close();
?>
