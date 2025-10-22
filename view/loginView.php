 <!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Login</title>
    <link rel="stylesheet" href="../css/login.css" />
    <script>
        function togglePassword() {
            const passField = document.getElementById('password');
            if (passField.type === 'password') {
                passField.type = 'text';
            } else {
                passField.type = 'password';
            }
        }
    </script>
</head>
<body>
    <div class="login-container">
        <h2>Iniciar sesión</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required placeholder="Usuario" />

            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required placeholder="Contraseña" />
            <label><input type="checkbox" onclick="togglePassword()"> Mostrar contraseña</label>
            
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>