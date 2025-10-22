<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../view/loginView.php");
    exit();
}

require_once '../model/usuarioModel.php';

$model = new UsuarioModel();

// Verificar si el usuario actual es administrador
$usuarioActualData = $model->getUserByUsername($_SESSION['usuario']);
if (!$usuarioActualData || $usuarioActualData['id_rol'] != 1) {
    // Si no es administrador, redirigir al menú con mensaje de error
    header("Location: ../controller/menuController.php?error=no_permission");
    exit();
}

// Obtener DPI del parámetro GET
$dpi = isset($_GET['dpi']) ? trim($_GET['dpi']) : '';

if (empty($dpi)) {
    header("Location: ../controller/usuarioController.php");
    exit();
}

// Obtener datos del usuario
$usuario = $model->getUserByDPI($dpi);

if (!$usuario) {
    header("Location: ../controller/usuarioController.php");
    exit();
}

$error = '';
$success = '';

// Procesar eliminación si es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    
    if ($accion === 'eliminar') {
        // Verificar que no se esté eliminando a sí mismo
        if ($usuarioActualData && $usuarioActualData['id_usuario'] == $usuario['id_usuario']) {
            $error = "No puedes eliminarte a ti mismo";
        } else {
            // Eliminar usuario (cambiar estado a 0)
            $resultado = $model->deleteUser($usuario['id_usuario']);
            if ($resultado) {
                // Redirigir con mensaje de éxito
                header("Location: ../controller/usuarioController.php?deleted=1");
                exit();
            } else {
                $error = "Error al eliminar el usuario";
            }
        }
    } else {
        // Cancelar - redirigir a la lista
        header("Location: ../controller/usuarioController.php");
        exit();
    }
}

require_once '../view/usuarioEliminarView.php';
