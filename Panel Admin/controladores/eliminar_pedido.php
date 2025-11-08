<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['s_usuario']) || empty($_SESSION['s_usuario'])) {
    echo "error: Acceso denegado.";
    exit;
}

include_once "../../logins/logouts/conexion.php";
$objeto = new Conexion();
$conexion = $objeto->Conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_venta = $_POST['id_venta'] ?? '';

    if (empty($id_venta)) {
        echo "error: ID de venta no proporcionado.";
        exit;
    }

    try {
        $conexion->beginTransaction(); // ← Inicia transacción

        // ========== NUEVO: OBTENER PRODUCTOS Y CANTIDADES PARA DEVOLVER STOCK ==========
        $sql_obtener_detalles = "SELECT id_producto, dp_cantidad FROM detalles_pedido WHERE id_venta = :id_venta";
        $stmt_obtener_detalles = $conexion->prepare($sql_obtener_detalles);
        $stmt_obtener_detalles->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
        $stmt_obtener_detalles->execute();
        $detalles_pedido = $stmt_obtener_detalles->fetchAll(PDO::FETCH_ASSOC);

        // ========== NUEVO: DEVOLVER STOCK A CADA PRODUCTO ==========
        foreach ($detalles_pedido as $detalle) {
            $sql_devolver_stock = "UPDATE productos SET cantidad = cantidad + :cantidad WHERE id_producto = :id_producto";
            $stmt_devolver_stock = $conexion->prepare($sql_devolver_stock);
            $stmt_devolver_stock->bindParam(':cantidad', $detalle['dp_cantidad'], PDO::PARAM_INT);
            $stmt_devolver_stock->bindParam(':id_producto', $detalle['id_producto'], PDO::PARAM_INT);
            $stmt_devolver_stock->execute();
            
            // Opcional: Log para debugging
            error_log("Stock devuelto - Producto ID: {$detalle['id_producto']}, Cantidad: {$detalle['dp_cantidad']}");
        }

        // 1. Eliminar detalles del pedido
        $sql_detalles = "DELETE FROM detalles_pedido WHERE id_venta = :id_venta";
        $stmt_detalles = $conexion->prepare($sql_detalles);
        $stmt_detalles->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);
        $stmt_detalles->execute();

        // 2. Eliminar el pedido
        $sql_venta = "DELETE FROM ventas WHERE id_venta = :id_venta";
        $stmt_venta = $conexion->prepare($sql_venta);
        $stmt_venta->bindParam(':id_venta', $id_venta, PDO::PARAM_INT);

        if ($stmt_venta->execute()) {
            $conexion->commit(); // ← Todo OK
            echo "success";
        } else {
            throw new Exception("Error al eliminar la venta");
        }

    } catch (Exception $e) {
        $conexion->rollBack(); // ← Deshace cambios
        echo "error: " . $e->getMessage();
        error_log("Error en eliminar_pedido.php: " . $e->getMessage());
    }
} else {
    echo "error: Método no permitido.";
}
?>