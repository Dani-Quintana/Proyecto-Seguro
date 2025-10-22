<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../view/loginView.php");
    exit();
}

require_once '../model/usuarioModel.php';

$model = new UsuarioModel();

// Obtener información del usuario actual para verificar su rol
$usuarioActual = $model->getUserByUsername($_SESSION['usuario']);
$esAdmin = ($usuarioActual && $usuarioActual['id_rol'] == 1); // 1 = Administrador

// Obtener el término de búsqueda enviado por GET (si existe)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Si hay búsqueda, llamar a un método que filtre, si no, traer todos
if ($search !== '') {
    $usuarios = $model->searchUsers($search);
} else {
    $usuarios = $model->getAllUsers();
}

require_once '../view/usuarioView.php';