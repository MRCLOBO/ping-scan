<?php

function check_access($required_role) {
    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $required_role) {
        header("Location: no_access.php");
        exit();
    }
}
?>
