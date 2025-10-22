<?php
session_start();

require_once __DIR__ . '/../model/usuarioModel.php';

if (!isset($_SESSION['usuario'])) {
    header("Location: ../view/loginView.php");
    exit();
}

$usuarioModel = new UsuarioModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($new_password !== $confirm_password) {
        $error = "Las contraseñas no coinciden";
        include __DIR__ . '/../view/changePasswordView.php';
        exit();
    }

    // Obtener usuario para validar contraseña actual
    $user = $usuarioModel->getUserByUsername($_SESSION['usuario']);

    if (!$user) {
        // Usuario no encontrado (por seguridad, cerrar sesión)
        session_destroy();
        header("Location: ../view/loginView.php");
        exit();
    }

    // Verificar que la nueva contraseña NO sea igual a la actual
    if (password_verify($new_password, $user['password'])) {
        $error = "La nueva contraseña no puede ser igual a la contraseña actual";
        include __DIR__ . '/../view/changePasswordView.php';
        exit();
    }

    // Hashear la nueva contraseña
    $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

    // Actualizar la contraseña y primer_login = 1
    $result = $usuarioModel->updatePasswordAndFirstLogin($user['id_usuario'], $hashedPassword);

    if ($result) {
         header("Location: ../controller/menuController.php");
        exit();
    } else {
        $error = "Error al actualizar la contraseña";
        include __DIR__ . '/../view/changePasswordView.php';
        exit();
    }
} else {
    // Mostrar formulario de cambio de contraseña
    include __DIR__ . '/../view/changePasswordView.php';
    exit();
}
