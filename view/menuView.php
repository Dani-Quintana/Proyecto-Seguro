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
    <title>Menú Principal</title>
    <link rel="stylesheet" href="../css/menu.css">
</head>
<body>

<nav class="navbar">
    <div class="navbar-logo">Mi Sistema</div>
    <ul class="navbar-menu">
        <li><a href="../controller/menuController.php" class="active">Menú Principal</a></li>
        <li><a href="../controller/usuarioController.php">Usuarios</a></li>
        <li><a href="#">Pedidos</a></li>
        <li><a href="#">Consumos</a></li>
        <li><a href="#">Inventario</a></li>
        <li><a href="../controller/ajustesController.php">Ajustes</a></li>
        <li><a href="#" id="btnLogout">Salir</a></li>
    </ul>
</nav>

<div class="container">
    <?php if ($errorPermiso): ?>
        <div class="alert alert-error">
            <strong>Acceso Denegado:</strong> No tienes permisos de administrador para realizar esta acción.
        </div>
    <?php endif; ?>
    
    <div class="welcome-header">
        <h1>Bienvenido, <?= htmlspecialchars($_SESSION['usuario']) ?>!</h1>
        <p class="user-info">
            <span class="role-badge <?= $esAdmin ? 'admin' : 'employee' ?>">
                <?= $esAdmin ? 'Administrador' : 'Empleado' ?>
            </span>
        </p>
    </div>

    <div class="dashboard-grid">
        <div class="dashboard-card">
            <div class="card-icon">👥</div>
            <h3>Usuarios</h3>
            <p>Gestión de usuarios del sistema</p>
            <a href="../controller/usuarioController.php" class="card-button">Ver usuarios</a>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">📋</div>
            <h3>Pedidos</h3>
            <p>Administrar pedidos y órdenes</p>
            <a href="#" class="card-button disabled">Próximamente</a>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">📊</div>
            <h3>Consumos</h3>
            <p>Control de consumo de ingredientes</p>
            <a href="#" class="card-button disabled">Próximamente</a>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">📦</div>
            <h3>Inventario</h3>
            <p>Gestión de stock y productos</p>
            <a href="#" class="card-button disabled">Próximamente</a>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">⚙️</div>
            <h3>Ajustes</h3>
            <p>Configuración personal y de la cuenta</p>
            <a href="../controller/ajustesController.php" class="card-button">Configurar perfil</a>
        </div>

        <div class="dashboard-card">
            <div class="card-icon">📈</div>
            <h3>Reportes</h3>
            <p>Estadísticas y reportes</p>
            <a href="#" class="card-button disabled">Próximamente</a>
        </div>
    </div>

    <div class="quick-stats">
        <div class="stat-item">
            <div class="stat-number">12</div>
            <div class="stat-label">Pedidos hoy</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">5</div>
            <div class="stat-label">Usuarios activos</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">8</div>
            <div class="stat-label">Productos disponibles</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">95%</div>
            <div class="stat-label">Stock disponible</div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="logoutModal" class="modal">
    <div class="modal-content">
        <h3>¿Cerrar sesión?</h3>
        <p>¿Estás seguro que deseas salir?</p>
        <div class="modal-actions">
            <button id="confirmLogout" class="btn-danger">Salir</button>
            <button id="cancelLogout" class="btn-secondary">Cancelar</button>
        </div>
    </div>
</div>

<script>
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