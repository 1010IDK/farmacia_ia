<?php
ob_start();
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['s_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    ob_end_flush();
    exit();
}

require_once __DIR__ . '/../../logins/logouts/conexion.php';
$conexion = Conexion::Conectar();

$codigo = trim($_POST['codigo_pedido'] ?? '');

if (empty($codigo)) {
    echo json_encode(['success' => false, 'message' => 'Código vacío']);
    ob_end_flush();
    exit();
}

try {
    $sql = "SELECT 
                v.codigo_unico,
                u.nombre AS nombre_cliente,
                u.correo,
                v.fecha_apartado,
                SUM(dp.dp_cantidad * dp.monto_unico) AS total
            FROM ventas v
            INNER JOIN usuarios u ON v.id_usuario = u.id_usuario
            INNER JOIN detalles_pedido dp ON v.id_venta = dp.id_venta
            WHERE v.codigo_unico = ? AND v.estado = 'apartado'
            GROUP BY v.id_venta, v.codigo_unico, u.nombre, u.correo, v.fecha_apartado";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([$codigo]);
    $venta = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$venta) {
        echo json_encode(['success' => false, 'message' => 'Pedido no encontrado o ya pagado']);
        ob_end_flush();
        exit();
    }

    $total = $venta['total'];

    $sql_productos = "SELECT p.nombre, dp.dp_cantidad, dp.monto_unico, (dp.dp_cantidad * dp.monto_unico) AS subtotal
                      FROM detalles_pedido dp
                      INNER JOIN productos p ON dp.id_producto = p.id_producto
                      WHERE dp.id_venta = (SELECT id_venta FROM ventas WHERE codigo_unico = ?)";
    $stmt_productos = $conexion->prepare($sql_productos);
    $stmt_productos->execute([$codigo]);
    $productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'pedido' => [
            'codigo_pedido' => $venta['codigo_unico'],
            'nombre_cliente' => $venta['nombre_cliente'],
            'correo' => $venta['correo'],
            'fecha_apartado' => date('d/m/Y H:i', strtotime($venta['fecha_apartado'])),
            'total' => $total,
            'productos' => $productos
        ]
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

ob_end_flush();
exit();
?>