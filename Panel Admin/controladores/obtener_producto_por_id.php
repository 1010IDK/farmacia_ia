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
    $id_producto = $_POST['id_producto'] ?? '';

    if (empty($id_producto)) {
        echo json_encode(['error' => 'ID de producto no proporcionado.']);
        exit;
    }

    $consulta = "SELECT * FROM productos WHERE id_producto = :id_producto";
    $resultado = $conexion->prepare($consulta);
    $resultado->bindParam(':id_producto', $id_producto, PDO::PARAM_INT);
    $resultado->execute();

    $datos_producto = $resultado->fetch(PDO::FETCH_ASSOC);

    if ($datos_producto) {
        echo json_encode($datos_producto);
    } else {
        echo json_encode(['error' => 'Producto no encontrado.']);
    }
}
?>