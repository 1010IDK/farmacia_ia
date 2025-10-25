<?php
// filepath: c:\xampp\htdocs\proyecto\controladores\logout.php
session_start();
session_unset();
session_destroy();
echo "<script>
    alert('Sesión cerrada correctamente');
    window.location.href = '../index_t.php';
</script>";
exit();