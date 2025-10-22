<?php
require_once __DIR__ . '/../model/usuarioModel.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = $_POST['usuario'] ?? '';
    $password = $_POST['password'] ?? '';

    $usuarioModel = new UsuarioModel();
    $user = $usuarioModel->login($usuario, $password);

    if ($user) {
        $_SESSION['usuario'] = $user['usuario'];
        $_SESSION['rol'] = $user['id_rol'];

        if ($user['primer_login'] == 0) {
            // Redirigir a cambio de contraseña
            header("Location: ../view/changePasswordView.php");
            exit();
        } else {
            // Usuario con contraseña ya cambiada
            header("Location: ../controller/menuController.php");
            exit();
        }
    } else {
        $error = "Usuario o contraseña incorrectos";
        include __DIR__ . '/../view/loginView.php';
    }
} else {
    include __DIR__ . '/../view/loginView.php';
}
