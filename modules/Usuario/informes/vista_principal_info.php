<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informe</title>
</head>
<body>
    <h1>Generar Informe</h1>
    <form method="POST" action="controlador_informe.php">
        <label for="fecha_inicio">Fecha Inicio:</label>
        <input type="date" name="fecha_inicio" required>
        
        <label for="fecha_fin">Fecha Fin:</label>
        <input type="date" name="fecha_fin" required>

        <?php if ($_SESSION['rol'] == 'admin'): ?>
            <label for="local_id">Local:</label>
            <select name="local_id">
                <!-- Aquí deberías cargar los locales disponibles desde la base de datos -->
                 <p>datos de local</p>
            </select>
        <?php endif; ?>

        <input type="submit" value="Generar Informe">
    </form>
</body>
</html>
