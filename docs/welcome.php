<?php
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['nombreUsuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="icon" href="cinepop1.jpg" type="image/x-icon">
</head>
<body>
    <div class="container mt-5">
        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombreUsuario']); ?>!</h2>
        <p>Tu correo es: <?php echo htmlspecialchars($_SESSION['correo']); ?></p>
        <p><a href="inicio_sesion.html" class="btn btn-primary">Iniciar sesion</a></p>
    </div>
</body>
</html>
