<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Ajustes de Perfil</title>
    <link rel="stylesheet" href="../css/ajustes.css" />
</head>
<body>
<nav class="navbar">
    <div class="navbar-logo">Mi Sistema</div>
    <ul class="navbar-menu">
        <li><a href="../controller/menuController.php">Menú Principal</a></li>
        <li><a href="../controller/usuarioController.php">Usuarios</a></li>
        <li><a href="#">Pedidos</a></li>
        <li><a href="#">Consumos</a></li>
        <li><a href="#">Inventario</a></li>
        <li><a href="../controller/ajustesController.php" class="active">Ajustes</a></li>
        <li><a href="#" id="btnLogout">Salir</a></li>
    </ul>
</nav>

<div class="container">
    <div class="settings-header">
        <h2>Configuración de Perfil</h2>
        <p>Actualiza tu información personal y configuración de cuenta</p>
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

    <div class="settings-tabs">
        <button class="tab-button active" onclick="showTab('profile')">Información Personal</button>
        <button class="tab-button" onclick="showTab('password')">Cambiar Contraseña</button>
    </div>

    <!-- Tab de Información Personal -->
    <div id="profile-tab" class="tab-content active">
        <div class="form-section">
            <h3>Información Personal</h3>
            <form method="POST" class="settings-form" id="profileForm">
                <input type="hidden" name="action" value="update_profile">
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="DPI">DPI *</label>
                        <input type="text" id="DPI" name="DPI" value="<?= htmlspecialchars($usuarioActual['DPI']) ?>" required maxlength="20" pattern="\d{13,}" title="El DPI debe tener al menos 13 dígitos numéricos" />
                        <small>Mínimo 13 dígitos</small>
                    </div>
                    <div class="form-group">
                        <label for="usuario">Nombre de Usuario *</label>
                        <input type="text" id="usuario" name="usuario" value="<?= htmlspecialchars($usuarioActual['usuario']) ?>" required maxlength="100" />
                        <small>Este será tu nombre de usuario para iniciar sesión</small>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="nombres">Nombres *</label>
                        <input type="text" id="nombres" name="nombres" value="<?= htmlspecialchars($usuarioActual['nombres']) ?>" required maxlength="100" />
                    </div>
                    <div class="form-group">
                        <label for="apellidos">Apellidos *</label>
                        <input type="text" id="apellidos" name="apellidos" value="<?= htmlspecialchars($usuarioActual['apellidos']) ?>" required maxlength="100" />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="telefono">Teléfono *</label>
                        <input type="text" id="telefono" name="telefono" value="<?= htmlspecialchars($usuarioActual['telefono']) ?>" required maxlength="20" pattern="\d{8,}" title="El teléfono debe tener al menos 8 dígitos numéricos" />
                        <small>Mínimo 8 dígitos</small>
                    </div>
                    <div class="form-group">
                        <label for="correo">Correo Electrónico *</label>
                        <input type="email" id="correo" name="correo" value="<?= htmlspecialchars($usuarioActual['correo']) ?>" required maxlength="50" />
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Actualizar Información</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tab de Cambiar Contraseña -->
    <div id="password-tab" class="tab-content">
        <div class="form-section">
            <h3>Cambiar Contraseña</h3>
            <div class="info-box">
                <p><strong>Requisitos para la nueva contraseña:</strong></p>
                <ul>
                    <li>Al menos 8 caracteres</li>
                    <li>Al menos una letra mayúscula</li>
                    <li>Al menos una letra minúscula</li>
                    <li>Al menos un número</li>
                    <li>Al menos un símbolo especial</li>
                </ul>
            </div>

            <form method="POST" class="settings-form" id="passwordForm">
                <input type="hidden" name="action" value="change_password">
                
                <div class="form-group">
                    <label for="current_password">Contraseña Actual *</label>
                    <input type="password" id="current_password" name="current_password" required placeholder="Ingresa tu contraseña actual" />
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="new_password">Nueva Contraseña *</label>
                        <input type="password" id="new_password" name="new_password" required placeholder="Nueva contraseña" />
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirmar Nueva Contraseña *</label>
                        <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirma la nueva contraseña" />
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">Cambiar Contraseña</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Información del Usuario Actual -->
    <div class="user-info-section">
        <h3>Información de la Cuenta</h3>
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Rol:</span>
                <span class="info-value">
                    <span class="role-badge <?= ($usuarioActual['id_rol'] == 1) ? 'admin' : 'employee' ?>">
                        <?= ($usuarioActual['id_rol'] == 1) ? 'Administrador' : 'Empleado' ?>
                    </span>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Estado:</span>
                <span class="info-value">
                    <span class="status-badge active">Activo</span>
                </span>
            </div>
        </div>
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

<!-- Modal de validación de contraseña -->
<div id="modalPasswordError" class="modal-error">
    <div class="modal-error-content">
        <h3>Error de validación</h3>
        <p>La contraseña debe cumplir con todos los requisitos de seguridad.</p>
        <button id="closePasswordModalBtn">Aceptar</button>
    </div>
</div>

<script>
    // Gestión de tabs
    function showTab(tabName) {
        // Ocultar todos los tabs
        const tabs = document.querySelectorAll('.tab-content');
        tabs.forEach(tab => tab.classList.remove('active'));
        
        // Quitar active de todos los botones
        const buttons = document.querySelectorAll('.tab-button');
        buttons.forEach(btn => btn.classList.remove('active'));
        
        // Mostrar el tab seleccionado
        document.getElementById(tabName + '-tab').classList.add('active');
        event.target.classList.add('active');
    }

    // Validación de formulario de perfil
    const profileForm = document.getElementById('profileForm');
    const dpiInput = document.getElementById('DPI');
    const telefonoInput = document.getElementById('telefono');

    // Validar DPI en tiempo real
    dpiInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 13) {
            this.value = this.value.slice(0, 13);
        }
    });

    // Validar teléfono en tiempo real
    telefonoInput.addEventListener('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
        if (this.value.length > 8) {
            this.value = this.value.slice(0, 8);
        }
    });

    // Validación antes de enviar perfil
    profileForm.addEventListener('submit', function(e) {
        const dpi = dpiInput.value;
        const telefono = telefonoInput.value;

        if (dpi.length < 13) {
            e.preventDefault();
            alert('El DPI debe tener al menos 13 dígitos');
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

    // Validación de contraseña
    const passwordForm = document.getElementById('passwordForm');
    const passwordModal = document.getElementById('modalPasswordError');
    const closePasswordModalBtn = document.getElementById('closePasswordModalBtn');

    function validarContrasena(pass) {
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/;
        return regex.test(pass);
    }

    passwordForm.addEventListener('submit', function(event) {
        const newPassword = passwordForm.new_password.value;
        const confirmPassword = passwordForm.confirm_password.value;

        if (newPassword !== confirmPassword) {
            event.preventDefault();
            alert('Las contraseñas no coinciden');
            return;
        }

        if (!validarContrasena(newPassword)) {
            event.preventDefault();
            passwordModal.classList.add('show');
            return;
        }
    });

    closePasswordModalBtn.addEventListener('click', () => {
        passwordModal.classList.remove('show');
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
        if (e.target === passwordModal) {
            passwordModal.classList.remove('show');
        }
    });
</script>

</body>
</html>