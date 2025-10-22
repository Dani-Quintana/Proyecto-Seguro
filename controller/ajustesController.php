<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../view/loginView.php");
    exit();
}

require_once '../model/usuarioModel.php';

$model = new UsuarioModel();

// Obtener información del usuario actual
$usuarioActual = $model->getUserByUsername($_SESSION['usuario']);

if (!$usuarioActual) {
    session_destroy();
    header("Location: ../view/loginView.php");
    exit();
}

$error = '';
$success = '';

// Procesar formulario si es POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_profile') {
        $datos = [
            'id_usuario' => $usuarioActual['id_usuario'],
            'DPI' => trim($_POST['DPI'] ?? ''),
            'usuario' => trim($_POST['usuario'] ?? ''),
            'nombres' => trim($_POST['nombres'] ?? ''),
            'apellidos' => trim($_POST['apellidos'] ?? ''),
            'telefono' => trim($_POST['telefono'] ?? ''),
            'correo' => trim($_POST['correo'] ?? '')
        ];

        // Validaciones básicas
        if (empty($datos['DPI']) || empty($datos['usuario']) || empty($datos['nombres']) || 
            empty($datos['apellidos']) || empty($datos['telefono']) || empty($datos['correo'])) {
            $error = "Todos los campos son obligatorios";
        } elseif (!filter_var($datos['correo'], FILTER_VALIDATE_EMAIL)) {
            $error = "El formato del correo no es válido";
        } elseif (strlen($datos['DPI']) < 13) {
            $error = "El DPI debe tener al menos 13 caracteres";
        } elseif (strlen($datos['telefono']) < 8) {
            $error = "El teléfono debe tener al menos 8 dígitos";
        } elseif (!preg_match('/^\d{13,}$/', $datos['DPI'])) {
            $error = "El DPI debe contener solo números y tener al menos 13 dígitos";
        } elseif (!preg_match('/^\d{8,}$/', $datos['telefono'])) {
            $error = "El teléfono debe contener solo números y tener al menos 8 dígitos";
        } else {
            // Verificar si el DPI ya existe en otro usuario
            if ($datos['DPI'] !== $usuarioActual['DPI']) {
                $existeDPI = $model->getUserByDPI($datos['DPI']);
                if ($existeDPI) {
                    $error = "El DPI ya está registrado en otro usuario";
                }
            }

            // Verificar si el usuario ya existe en otro usuario
            if ($datos['usuario'] !== $usuarioActual['usuario']) {
                $existeUsuario = $model->getUserByUsername($datos['usuario']);
                if ($existeUsuario) {
                    $error = "El nombre de usuario ya está registrado";
                }
            }

            if (empty($error)) {
                $resultado = $model->updateUserProfile($datos);
                if ($resultado) {
                    // Si cambió el nombre de usuario, actualizar la sesión
                    if ($datos['usuario'] !== $usuarioActual['usuario']) {
                        $_SESSION['usuario'] = $datos['usuario'];
                    }
                    $success = "Perfil actualizado correctamente";
                    // Recargar datos del usuario
                    $usuarioActual = $model->getUserByUsername($_SESSION['usuario']);
                } else {
                    $error = "Error al actualizar el perfil";
                }
            }
        }
    } elseif ($action === 'change_password') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = "Todos los campos de contraseña son obligatorios";
        } elseif ($new_password !== $confirm_password) {
            $error = "Las nuevas contraseñas no coinciden";
        } elseif (!password_verify($current_password, $usuarioActual['password'])) {
            $error = "La contraseña actual es incorrecta";
        } elseif (password_verify($new_password, $usuarioActual['password'])) {
            $error = "La nueva contraseña no puede ser igual a la actual";
        } else {
            // Validar nueva contraseña (al menos 8 caracteres, 1 mayúscula, 1 minúscula, 1 número, 1 símbolo)
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/', $new_password)) {
                $error = "La nueva contraseña debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, números y símbolos";
            } else {
                $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);
                $resultado = $model->updateUserPassword($usuarioActual['id_usuario'], $hashedPassword);
                
                if ($resultado) {
                    $success = "Contraseña actualizada correctamente";
                } else {
                    $error = "Error al actualizar la contraseña";
                }
            }
        }
    }
}

require_once '../view/ajustesView.php';