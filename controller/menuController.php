<?php
session_start();

// Verifica que el usuario esté logueado
if (!isset($_SESSION['usuario'])) {
    header("Location: ../controller/loginController.php");
    exit();
}

// Obtener información del usuario para saber su rol
require_once __DIR__ . '/../model/usuarioModel.php';
$usuarioModel = new UsuarioModel();
$usuarioActual = $usuarioModel->getUserByUsername($_SESSION['usuario']);
$esAdmin = ($usuarioActual && $usuarioActual['id_rol'] == 1); // 1 = Administrador

// Verificar si hay mensaje de error por permisos
$errorPermiso = isset($_GET['error']) && $_GET['error'] === 'no_permission';

// Si está logueado, carga la vista del menú
include __DIR__ . '/../view/menuView.php';