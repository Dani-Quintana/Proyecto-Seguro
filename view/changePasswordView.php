<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['usuario'])) {
    header("Location: controller/loginController.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Cambiar contraseña</title>
    <link rel="stylesheet" href="../css/changePassword.css" />
</head>
<body>
    <div class="login-container">
        <h2>Cambiar contraseña</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form id="changePasswordForm" method="POST" action="../controller/changePasswordController.php" novalidate>
            <label for="new_password">Nueva contraseña:</label>
            <input type="password" id="new_password" name="new_password" required placeholder="Ingresa la nueva contraseña:" />

            <label for="confirm_password">Confirmar contraseña:</label>
            <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirmación de contraseña:" />

            <button type="submit">Actualizar contraseña</button>
        </form>
    </div>

    <!-- Modal de error -->
    <div id="modalError" class="modal-error">
        <div class="modal-error-content">
            <h3>Error de validación</h3>
            <p>La contraseña debe tener al menos 8 caracteres, incluyendo mayúsculas, minúsculas, números y símbolos.</p>
            <button id="closeModalBtn">Aceptar</button>
        </div>
    </div>

    <script>
        const form = document.getElementById('changePasswordForm');
        const modal = document.getElementById('modalError');
        const closeModalBtn = document.getElementById('closeModalBtn');

        function validarContrasena(pass) {
            // Al menos 8 caracteres, 1 mayúscula, 1 minúscula, 1 número, 1 símbolo
            const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
            return regex.test(pass);
        }

        form.addEventListener('submit', function(event) {
            const newPassword = form.new_password.value;

            if (!validarContrasena(newPassword)) {
                event.preventDefault();
                modal.classList.add('show');
                return;
            }
        });

        closeModalBtn.addEventListener('click', () => {
            modal.classList.remove('show');
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('show');
            }
        });
    </script>
</body>
</html>
