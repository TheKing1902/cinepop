<?php
//conexion a base de datos
$servername = "localhost";
$database = "cinepop";
$username = "root";
$password = "";
$conn = mysqli_connect($servername, $username, $password, $database);
//verificar conexion
if (!$conn){
    die("conexion con la base de datos fallida:".mysqli_connect_error());
}
echo"Conexion exitosa";

$nombre = $_POST['nombreUsuario'];
$pelicula = $_POST['nombrePelicula'];

mysqli_close($conn);
?>