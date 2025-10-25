<?php
// Asegura que se reporten todos los errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "../../logins/conexion.php";
$objeto = new Conexion();
$conexion = $objeto->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge el ID del trabajador a eliminar
    $id_trabajador = $_POST['id_trabajador'] ?? '';

    // Validación básica: asegura que el ID no esté vacío
    if (empty($id_trabajador)) {
        echo "error: ID de trabajador no proporcionado.";
        exit;
    }

    // Prepara y ejecuta la consulta para eliminar el usuario
    $consulta = "DELETE FROM admin_trabajadores WHERE id_trabajador = :id_trabajador";
    $resultado = $conexion->prepare($consulta);
    $resultado->bindParam(':id_trabajador', $id_trabajador, PDO::PARAM_INT);

    if ($resultado->execute()) {
        echo "success";
    } else {
        $errorInfo = $resultado->errorInfo();
        echo "error: " . $errorInfo[2];
    }
}
?>