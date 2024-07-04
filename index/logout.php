<?php 
#logout.php
require_once '../session.php'; ?>

<?php

// Eliminar todas las variables de sesión
$_SESSION = [];

// Destruir la sesión
session_destroy();

// Redirigir al usuario a la página de inicio de sesión

header('Location: login.php');

exit;

?>
