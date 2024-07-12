<?php
// Verificar si se han enviado datos desde el formulario de registro
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Configurar la conexión a la base de datos (debes reemplazar con tus propios datos)
    $servername = "databasecinepopcl2667";
    $username = "ZeroAdmin";
    $password = "2801260980090608";
    $dbname = "DataBaseCinepop";

    // Crear conexión
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Verificar la conexión
    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    // Recibir y sanear los datos del formulario
    $usuario = $conn->real_escape_string($_POST['usuario']);
    $correo = $conn->real_escape_string($_POST['correo']);
    $contrasena = $conn->real_escape_string($_POST['contrasena']); // Asegúrate de hashear la contraseña antes de almacenarla en producción

    // Validar y hashear la contraseña
    $hash_contrasena = password_hash($contrasena, PASSWORD_DEFAULT);

    // Preparar la consulta SQL para insertar el nuevo usuario
    $sql = "INSERT INTO usuarios (usuario, correo, contrasena) VALUES ('$usuario', '$correo', '$hash_contrasena')";

    // Ejecutar la consulta y verificar si fue exitosa
    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso. <a href='index.html'>Iniciar sesión</a>"; // Mensaje de éxito con enlace al formulario de inicio de sesión
    } else {
        echo "Error al registrar usuario: " . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
}
?>
