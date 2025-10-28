<?php
session_start();
include_once '../config/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];

    // Obtener el carrito del usuario
    $stmt = $conexion->prepare("SELECT id_carrito FROM carrito_compras WHERE id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $carrito = $stmt->fetch();

    if ($carrito) {
        // Eliminar todos los detalles del carrito
        $stmt = $conexion->prepare("DELETE FROM detalles_carrito WHERE id_carrito = ?");
        $stmt->execute([$carrito['id_carrito']]);
    }

    echo json_encode(['success' => true, 'message' => 'Carrito vaciado']);
}
?>