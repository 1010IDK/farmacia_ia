<?php
session_start();

// Cabeceras HTTP para evitar el caché del navegador
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

//código actual para destruir la sesión
unset($_SESSION["s_usuario"]);
session_destroy();

// Redireccionamiento a la página de login
header("Location: ../login_admin.php");
exit;
?>