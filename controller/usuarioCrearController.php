<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../view/loginView.php");
    exit();
}

require_once '../model/usuarioModel.php';

$model = new UsuarioModel();

// Verificar si el usuario actual es administrador
$usuarioActual = $model->getUserByUsername($_SESSION['usuario']);
if (!$usuarioActual || $usuarioActual['id_rol'] != 1) {
    // Si no es administrador, redirigir al menú con mensaje de error
    header("Location: ../controller/menuController.php?error=no_permission");
    exit();
}

$error = '';
$success = '';

// Obtener todos los roles para el select
$roles = $model->getAllRoles();

// Procesar formulario si es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'DPI' => trim($_POST['DPI'] ?? ''),
        'usuario' => trim($_POST['usuario'] ?? ''),
        'nombres' => trim($_POST['nombres'] ?? ''),
        'apellidos' => trim($_POST['apellidos'] ?? ''),
        'telefono' => trim($_POST['telefono'] ?? ''),
        'correo' => trim($_POST['correo'] ?? ''),
        'id_rol' => (int)($_POST['id_rol'] ?? 0)
    ];

    // Validaciones básicas
    if (empty($datos['DPI']) || empty($datos['usuario']) || empty($datos['nombres']) || 
        empty($datos['apellidos']) || empty($datos['telefono']) || empty($datos['correo'])) {
        $error = "Todos los campos son obligatorios";
    } elseif (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
        $error = "El formato del correo no es válido";
    } elseif ($datos['id_rol'] <= 0) {
        $error = "Debe seleccionar un rol válido";
    } elseif (strlen($datos['DPI']) < 13) {
        $error = "El DPI debe tener al menos 13 caracteres";
    } elseif (strlen($datos['telefono']) < 8) {
        $error = "El teléfono debe tener al menos 8 dígitos";
    } elseif (!preg_match('/^\d{8,}$/', $datos['telefono'])) {
        $error = "El teléfono debe contener solo números y tener al menos 8 dígitos";
    } elseif (!preg_match('/^\d{13,}$/', $datos['DPI'])) {
        $error = "El DPI debe contener solo números y tener al menos 13 dígitos";
    } else {
        // Verificar si el DPI ya existe
        $existeDPI = $model->getUserByDPI($datos['DPI']);
        if ($existeDPI) {
            $error = "El DPI ya está registrado";
        } else {
            // Verificar si el usuario ya existe
            $existeUsuario = $model->getUserByUsername($datos['usuario']);
            if ($existeUsuario) {
                $error = "El nombre de usuario ya está registrado";
            } else {
                // Crear usuario con contraseña temporal
                $passwordTemporal = $datos['DPI']; // Usar DPI como contraseña inicial
                $resultado = $model->createUser($datos, $passwordTemporal);
                
                if ($resultado) {
                    $success = "Usuario creado correctamente. Contraseña temporal: " . $passwordTemporal;
                    // Limpiar el formulario
                    $datos = ['DPI' => '', 'usuario' => '', 'nombres' => '', 'apellidos' => '', 'telefono' => '', 'correo' => '', 'id_rol' => 0];
                } else {
                    $error = "Error al crear el usuario";
                }
            }
        }
    }
}

require_once '../view/usuarioCrearView.php';