<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: controller/loginController.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Panel Principal</title>
</head>
<body>
    <h1>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?></h1>
    <p>Rol: <?= htmlspecialchars($_SESSION['rol']) ?></p>
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>
