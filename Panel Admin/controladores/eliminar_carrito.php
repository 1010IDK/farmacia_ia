/*elimina un producto del carrito de compras
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

    // Verificar que el detalle pertenece al usuario - CON MYSQLI
    $sql_verificar = "SELECT dc.id_detalle_car 
                      FROM detalles_carrito dc 
                      JOIN carrito_compras cc ON dc.id_carrito = cc.id_carrito 
                      WHERE dc.id_detalle_car = ? AND cc.id_usuario = ?";
    
    $stmt_verificar = $conexion->prepare($sql_verificar);
    $stmt_verificar->bind_param("ii", $id_detalle_car, $id_usuario);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();
    $detalle = $result_verificar->fetch_assoc();

    if (!$detalle) {
        echo json_encode(['success' => false, 'message' => 'Item no encontrado']);
        exit;
    }

    // Eliminar el producto del carrito - CON MYSQLI
    $sql_eliminar = "DELETE FROM detalles_carrito WHERE id_detalle_car = ?";
    $stmt_eliminar = $conexion->prepare($sql_eliminar);
    $stmt_eliminar->bind_param("i", $id_detalle_car);
    
    if ($stmt_eliminar->execute()) {
        echo json_encode(['success' => true, 'message' => 'Producto eliminado del carrito']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar producto']);
    }
}
?>