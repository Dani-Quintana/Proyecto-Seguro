<?php
// Aquí $usuarios viene del controlador
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <title>Lista de Usuarios</title>
    <link rel="stylesheet" href="../css/usuario.css" />
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
    <!-- Mensaje de eliminación exitosa -->
    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == '1'): ?>
        <div class="alert alert-success">
            Usuario eliminado correctamente.
        </div>
    <?php endif; ?>

    <!-- Header con título y botón crear -->
    <div class="list-header">
        <h2>Usuarios</h2>
        <?php if ($esAdmin): ?>
            <a href="../controller/usuarioCrearController.php" class="btn-create">+ Crear Usuario</a>
        <?php endif; ?>
    </div>

    <!-- Buscador -->
    <div class="search-container">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Buscar usuario..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
            <button type="submit">Buscar</button>
        </form>
    </div>

    <!-- Tabla de usuarios -->
    <table>
        <thead>
            <tr>
                <th>DPI</th>
                <th>Nombre</th>
                <th>Apellidos</th>
                <th>Usuario</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Rol</th>
                <?php if ($esAdmin): ?>
                    <th>Acciones</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['DPI']) ?></td>
                        <td><?= htmlspecialchars($user['nombres']) ?></td>
                        <td><?= htmlspecialchars($user['apellidos']) ?></td>
                        <td><?= htmlspecialchars($user['usuario']) ?></td>
                        <td><?= htmlspecialchars($user['telefono']) ?></td>
                        <td><?= htmlspecialchars($user['correo']) ?></td>
                        <td><?= htmlspecialchars($user['rol']) ?></td>
                        <?php if ($esAdmin): ?>
                            <td>
                                <div class="btn-actions">
                                    <a href="../controller/usuarioEditarController.php?dpi=<?= urlencode($user['DPI']) ?>" class="btn-edit">Editar</a>
                                    <a href="../controller/usuarioEliminarController.php?dpi=<?= urlencode($user['DPI']) ?>" class="btn-delete">Eliminar</a>
                                </div>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="<?= $esAdmin ? '8' : '7' ?>" class="no-results">
                        <?php if (isset($_GET['search']) && !empty($_GET['search'])): ?>
                            No se encontraron usuarios con el término "<?= htmlspecialchars($_GET['search']) ?>"
                        <?php else: ?>
                            No hay usuarios registrados.
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Información adicional -->
    <?php if (!empty($usuarios)): ?>
        <div class="table-info">
            <p>Total de usuarios: <?= count($usuarios) ?></p>
        </div>
    <?php endif; ?>
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