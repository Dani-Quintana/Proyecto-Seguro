<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Crear Usuario</title>
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
        <h2>Crear Nuevo Usuario</h2>
        <a href="../controller/usuarioController.php" class="btn-back">← Volver a la lista</a>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="user-form" id="userForm">
        <div class="form-row">
            <div class="form-group">
                <label for="DPI">DPI *</label>
                <input type="text" id="DPI" name="DPI" value="<?= htmlspecialchars($datos['DPI'] ?? '') ?>" required maxlength="20" placeholder="Ej: 1234567890123" pattern="\d{13,}" title="El DPI debe tener al menos 13 dígitos numéricos" />
                <small>El DPI se usará como contraseña temporal (mínimo 13 dígitos)</small>
            </div>
            <div class="form-group">
                <label for="usuario">Usuario *</label>
                <input type="text" id="usuario" name="usuario" value="<?= htmlspecialchars($datos['usuario'] ?? '') ?>" required maxlength="100" placeholder="Nombre de usuario único" />
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="nombres">Nombres *</label>
                <input type="text" id="nombres" name="nombres" value="<?= htmlspecialchars($datos['nombres'] ?? '') ?>" required maxlength="100" placeholder="Nombres completos" />
            </div>
            <div class="form-group">
                <label for="apellidos">Apellidos *</label>
                <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($datos['apellidos'] ?? '') ?>" required maxlength="100" placeholder="Apellidos completos" />
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="telefono">Teléfono *</label>
                <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($datos['telefono'] ?? '') ?>" required maxlength="20" placeholder="Ej: 12345678" pattern="\d{8,}" title="El teléfono debe tener al menos 8 dígitos numéricos" />
                <small>Mínimo 8 dígitos</small>
            </div>
            <div class="form-group">
                <label for="correo">Correo *</label>
                <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($datos['correo'] ?? '') ?>" required maxlength="50" placeholder="usuario@ejemplo.com" />
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="id_rol">Rol *</label>
                <select id="id_rol" name="id_rol" required>
                    <option value="">Seleccionar rol...</option>
                    <?php foreach ($roles as $rol): ?>
                        <option value="<?= $rol['id_rol'] ?>" <?= (isset($datos['id_rol']) && $rol['id_rol'] == $datos['id_rol']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($rol['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="info-box">
            <p><strong>Información importante:</strong></p>
            <ul>
                <li>El usuario será creado con el DPI como contraseña temporal</li>
                <li>Deberá cambiar su contraseña en el primer inicio de sesión</li>
                <li>Todos los campos marcados con * son obligatorios</li>
                <li>El DPI debe tener exactamente 13 dígitos</li>
                <li>El teléfono debe tener al menos 8 dígitos</li>
            </ul>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn-primary">Crear Usuario</button>
            <a href="../controller/usuarioController.php" class="btn-secondary">Cancelar</a>
        </div>
    </form>
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
    // Validación de formulario
    const form = document.getElementById('userForm');
    const dpiInput = document.getElementById('DPI');
    const telefonoInput = document.getElementById('telefono');

    // Validar DPI en tiempo real
    dpiInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, ''); // Solo números
        if (this.value.length > 13) {
            this.value = this.value.slice(0, 13); // Máximo 13 dígitos
        }
    });

    // Validar teléfono en tiempo real
    telefonoInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, ''); // Solo números
        if (this.value.length > 8) {
            this.value = this.value.slice(0, 8); // Máximo 8 dígitos para teléfono
        }
    });

    // Validación antes de enviar
    form.addEventListener('submit', function(e) {
        const dpi = dpiInput.value;
        const telefono = telefonoInput.value;

        if (dpi.length !== 13) {
            e.preventDefault();
            alert('El DPI debe tener exactamente 13 dígitos');
            dpiInput.focus();
            return;
        }

        if (telefono.length < 8) {
            e.preventDefault();
            alert('El teléfono debe tener al menos 8 dígitos');
            telefonoInput.focus();
            return;
        }
    });

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
