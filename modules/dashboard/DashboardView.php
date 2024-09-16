<?php require 'DashboardController.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>

<body>
    <h2>Bienvenido, <?php echo htmlspecialchars($user->nombre); ?>!</h2>
    <div class="accesos-rapidos">
        <h3>Accesos Rápidos</h3>
        
    <div class="accesos-Administrador">
    
    <?php if ($user->rol === 'admin'):?>
                <h3>Opciones de Administrador</h3>
                <ul>
                <li><a href="../seguridad_autenticacion/view.php">Gestión de Usuarios</a></li>
                </ul>
            <?php endif; ?>
    </div>
    <?php if ($user->rol === 'admin' || $user->rol === 'tec' ): ?>
            <ul>
            <li><a href="gestion_dispositivos.php">Gestión de Dispositivos</a></li>
            <?php endif; ?>

            <li><a href="informes.php">Informes Históricos</a></li>
        </ul>
    </div>

    <form action="DashboardController.php?action=logout" method="POST">
        <button type="submit">Cerrar Sesión</button>
    </form>


    

</body>

</html>