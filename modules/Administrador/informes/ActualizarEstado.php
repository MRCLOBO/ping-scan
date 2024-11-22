<?php
session_start();

if (isset($_POST['ip']) && isset($_POST['estado'])) {
    $ip = $_POST['ip'];
    $estado = $_POST['estado'];

    // Guardar el estado en una variable de sesión
    $_SESSION['estados_dispositivos'][$ip] = $estado;
}
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
?>