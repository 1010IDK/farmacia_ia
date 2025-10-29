<?php
session_start();
include_once '../config/conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Debe iniciar sesión']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];

    // Obtener el carrito del usuario - CON MYSQLI
    $sql_carrito = "SELECT id_carrito FROM carrito_compras WHERE id_usuario = ?";
    $stmt_carrito = $conexion->prepare($sql_carrito);
    $stmt_carrito->bind_param("i", $id_usuario);
    $stmt_carrito->execute();
    $result_carrito = $stmt_carrito->get_result();
    $carrito = $result_carrito->fetch_assoc();

    if ($carrito) {
        // Eliminar todos los detalles del carrito - CON MYSQLI
        $sql_eliminar = "DELETE FROM detalles_carrito WHERE id_carrito = ?";
        $stmt_eliminar = $conexion->prepare($sql_eliminar);
        $stmt_eliminar->bind_param("i", $carrito['id_carrito']);
        
        if ($stmt_eliminar->execute()) {
            echo json_encode(['success' => true, 'message' => 'Carrito vaciado']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al vaciar carrito']);
        }
    } else {
        echo json_encode(['success' => true, 'message' => 'Carrito ya estaba vacío']);
    }
}
?>