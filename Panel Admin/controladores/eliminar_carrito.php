<?php
session_start();
include_once '../config/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $id_detalle_car = intval($_POST['id_detalle_car']);

    // Verificar que el detalle pertenece al usuario
    $stmt = $conexion->prepare("SELECT dc.id_detalle_car 
                          FROM detalles_carrito dc 
                          JOIN carrito_compras cc ON dc.id_carrito = cc.id_carrito 
                          WHERE dc.id_detalle_car = ? AND cc.id_usuario = ?");
    $stmt->execute([$id_detalle_car, $id_usuario]);
    $detalle = $stmt->fetch();

    if (!$detalle) {
        echo json_encode(['success' => false, 'message' => 'Item no encontrado']);
        exit;
    }

    $stmt = $conexion->prepare("DELETE FROM detalles_carrito WHERE id_detalle_car = ?");
    $stmt->execute([$id_detalle_car]);

    echo json_encode(['success' => true, 'message' => 'Producto eliminado del carrito']);
}
?>