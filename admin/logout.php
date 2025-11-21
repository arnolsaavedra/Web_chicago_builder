<?php
// admin/logout.php - Cerrar sesión

session_start();

// Destruir todas las variables de sesión
session_unset();
session_destroy();

// Redirigir al login
header('Location: login.php');
exit;
?>