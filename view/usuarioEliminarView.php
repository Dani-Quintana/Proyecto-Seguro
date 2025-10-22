<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Eliminar Usuario</title>
    <link rel="stylesheet" href="../css/usuarioForm.css" />
</head>
<body>
<nav class="navbar">
    <div class="navbar-logo">Mi Sistema</div>
    <ul class="navbar-menu">
        <li><a href="../controller/menuController.php">Menú Principal</a></li>
        <li><a href="../controller/usuarioController.php" class="active">Usuarios</a></li>
        <li><a href="#">Pedidos</a></li>
        <li><a href="#">Consumos</a></li>
        <li><a href="#">Inventario</a></li>
        <li><a href="../controller/ajustesController.php">Ajustes</a></li>
        <li><a href="#" id="btnLogout">Salir</a></li>
    </ul>
</nav>

<div class="container">
    <div class="form-header">
        <h2>Eliminar Usuario</h2>
        <a href="../controller/usuarioController.php" class="btn-back">← Volver a la lista</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="delete-confirmation">
        <div class="warning-icon">⚠️</div>
        <h3>¿Estás seguro que deseas eliminar este usuario?</h3>
        <p>Esta acción cambiará el estado del usuario a inactivo. Los datos no se eliminarán permanentemente.</p>
        
        <div class="user-details">
            <table>
                <tr>
                    <td><strong>DPI:</strong></td>
                    <td><?= htmlspecialchars($usuario['DPI']) ?></td>
                </tr>
                <tr>
                    <td><strong>Nombre:</strong></td>
                    <td><?= htmlspecialchars($usuario['nombres'] . ' ' . $usuario['apellidos']) ?></td>
                </tr>
                <tr>
                    <td><strong>Usuario:</strong></td>
                    <td><?= htmlspecialchars($usuario['usuario']) ?></td>
                </tr>
                <tr>
                    <td><strong>Correo:</strong></td>
                    <td><?= htmlspecialchars($usuario['correo']) ?></td>
                </tr>
                <tr>
                    <td><strong>Teléfono:</strong></td>
                    <td><?= htmlspecialchars($usuario['telefono']) ?></td>
                </tr>
            </table>
        </div>

        <form method="POST" class="delete-form">
            <div class="form-actions">
                <button type="submit" name="accion" value="eliminar" class="btn-danger">
                    Sí, Eliminar Usuario
                </button>
                <button type="submit" name="accion" value="cancelar" class="btn-secondary">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal para logout -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <h3>¿Cerrar sesión?</h3>
        <p>¿Estás seguro que deseas salir?</p>
        <div class="modal-actions">
            <button id="confirmLogout" class="btn-danger-modal">Salir</button>
            <button id="cancelLogout" class="btn-secondary-modal">Cancelar</button>
        </div>
    </div>
</div>

<script>
    // Modal logout
    const btnLogout = document.getElementById("btnLogout");
    const modal = document.getElementById("logoutModal");
    const cancelLogout = document.getElementById("cancelLogout");
    const confirmLogout = document.getElementById("confirmLogout");

    btnLogout.addEventListener("click", (e) => {
        e.preventDefault();
        modal.classList.add("show");
    });

    cancelLogout.addEventListener("click", () => {
        modal.classList.remove("show");
    });

    confirmLogout.addEventListener("click", () => {
        window.location.href = "../logout.php";
    });

    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.classList.remove("show");
        }
    });
</script>

</body>
</html>