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
    $nueva_cantidad = intval($_POST['cantidad']);

    // Verificar que el detalle pertenece al usuario
    $sql = "SELECT dc.id_detalle_car, p.cantidad as stock, p.nombre
            FROM detalles_carrito dc 
            JOIN carrito_compras cc ON dc.id_carrito = cc.id_carrito 
            JOIN productos p ON dc.id_producto = p.id_producto 
            WHERE dc.id_detalle_car = ? AND cc.id_usuario = ?";
    
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("ii", $id_detalle_car, $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $detalle = $result->fetch_assoc();

    if (!$detalle) {
        echo json_encode(['success' => false, 'message' => 'Item no encontrado']);
        exit;
    }

    if ($nueva_cantidad <= 0) {
        // Eliminar si la cantidad es 0 o menor
        $sql_delete = "DELETE FROM detalles_carrito WHERE id_detalle_car = ?";
        $stmt_delete = $conexion->prepare($sql_delete);
        $stmt_delete->bind_param("i", $id_detalle_car);
        $stmt_delete->execute();
        echo json_encode(['success' => true, 'eliminado' => true]);
        exit;
    }

    if ($nueva_cantidad > $detalle['stock']) {
        $max_stock = $detalle['stock'] ?? 'desconocido';
        echo json_encode(['success' => false, 'message' => 'No hay suficiente stock. Máximo: ' . $max_stock]);
        exit;
    }

    // Actualizar cantidad
    $sql_update = "UPDATE detalles_carrito SET dc_cantidad = ? WHERE id_detalle_car = ?";
    $stmt_update = $conexion->prepare($sql_update);
    $stmt_update->bind_param("ii", $nueva_cantidad, $id_detalle_car);
    $stmt_update->execute();

    echo json_encode(['success' => true, 'message' => 'Cantidad actualizada']);
}
?>