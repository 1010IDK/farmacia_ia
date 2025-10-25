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
    // Recoge el ID del producto a eliminar
    $id_producto = $_POST['id_producto'] ?? '';

    // Validación básica: asegura que el ID no esté vacío
    if (empty($id_producto)) {
        echo "error: ID de producto no proporcionado.";
        exit;
    }

    // Obtener la imagen para eliminar el archivo
    $consulta_imagen = "SELECT imagen FROM productos WHERE id_producto = :id_producto";
    $res_imagen = $conexion->prepare($consulta_imagen);
    $res_imagen->bindParam(':id_producto', $id_producto);
    $res_imagen->execute();
    $imagen = $res_imagen->fetchColumn();

    // Eliminar el producto de la DB
    $consulta = "DELETE FROM productos WHERE id_producto = :id_producto";
    $resultado = $conexion->prepare($consulta);
    $resultado->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);

    if ($resultado->execute()) {
        // Eliminar la imagen del servidor si existe
        if ($imagen && file_exists('../../' . $imagen)) {
            unlink('../../' . $imagen);
        }
        echo "success";
    } else {
        $errorInfo = $resultado->errorInfo();
        echo "error: " . $errorInfo[2];
    }
}
?>