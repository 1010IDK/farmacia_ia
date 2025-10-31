<?php
header('Content-Type: application/json; charset=utf-8');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Conexión usando ruta absoluta
include_once __DIR__ . '/../config/conexion.php';
if (!isset($conexion) || $conexion->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . ($conexion->connect_error ?? 'no connection')]);
    exit;
}

// Comprobar existencia de PHPMailer con ruta absoluta
if (!file_exists(__DIR__ . '/phpmailer/src/PHPMailer.php')) {
    echo json_encode(['success' => false, 'message' => 'PHPMailer file not found']);
    exit;
}

// Verificar si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener el correo del usuario
$sql_usuario = "SELECT correo FROM usuarios WHERE id_usuario = ?";
$stmt_usuario = $conexion->prepare($sql_usuario);
if (!$stmt_usuario) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed for usuario: ' . $conexion->error]);
    exit;
}
if (!$stmt_usuario->bind_param("i", $id_usuario)) {
    echo json_encode(['success' => false, 'message' => 'Bind failed for usuario: ' . $stmt_usuario->error]);
    exit;
}
if (!$stmt_usuario->execute()) {
    echo json_encode(['success' => false, 'message' => 'Execute failed for usuario: ' . $stmt_usuario->error]);
    exit;
}
$result_usuario = $stmt_usuario->get_result();
$usuario = $result_usuario->fetch_assoc();
$correo_usuario = $usuario['correo'] ?? '';

// Obtener los productos del carrito
$sql_carrito = "SELECT 
    dc.id_detalle_car,
    p.id_producto,
    p.precio,
    dc.dc_cantidad
FROM detalles_carrito dc
JOIN carrito_compras cc ON dc.id_carrito = cc.id_carrito
JOIN productos p ON dc.id_producto = p.id_producto
WHERE cc.id_usuario = ?";
$stmt_carrito = $conexion->prepare($sql_carrito);
if (!$stmt_carrito) {
    echo json_encode(['success' => false, 'message' => 'Prepare failed for carrito: ' . $conexion->error]);
    exit;
}
if (!$stmt_carrito->bind_param("i", $id_usuario)) {
    echo json_encode(['success' => false, 'message' => 'Bind failed for carrito: ' . $stmt_carrito->error]);
    exit;
}
if (!$stmt_carrito->execute()) {
    echo json_encode(['success' => false, 'message' => 'Execute failed for carrito: ' . $stmt_carrito->error]);
    exit;
}
$result_carrito = $stmt_carrito->get_result();

if ($result_carrito->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'El carrito está vacío']);
    exit;
}

// Generar código único (10 caracteres alfanuméricos)
function generarCodigoUnico($conexion) {
    do {
        $codigo = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
        $sql_check = "SELECT COUNT(*) as count FROM ventas WHERE codigo_unico = ?";
        $stmt_check = $conexion->prepare($sql_check);
        if (!$stmt_check) {
            echo json_encode(['success' => false, 'message' => 'Prepare failed for check codigo: ' . $conexion->error]);
            exit;
        }
        if (!$stmt_check->bind_param("s", $codigo)) {
            echo json_encode(['success' => false, 'message' => 'Bind failed for check codigo: ' . $stmt_check->error]);
            exit;
        }
        if (!$stmt_check->execute()) {
            echo json_encode(['success' => false, 'message' => 'Execute failed for check codigo: ' . $stmt_check->error]);
            exit;
        }
        $result_check = $stmt_check->get_result();
        $row = $result_check->fetch_assoc();
    } while ($row['count'] > 0);
    return $codigo;
}

$codigo_unico = generarCodigoUnico($conexion);

// Iniciar transacción para asegurar consistencia
$conexion->begin_transaction();

try {
    // Insertar en ventas
    $sql_venta = "INSERT INTO ventas (id_usuario, codigo_unico, estado, fecha_apartado) 
                  VALUES (?, ?, 'apartado', NOW())";
    $stmt_venta = $conexion->prepare($sql_venta);
    if (!$stmt_venta) {
        throw new Exception('Prepare failed for venta: ' . $conexion->error);
    }
    if (!$stmt_venta->bind_param("is", $id_usuario, $codigo_unico)) {
        throw new Exception('Bind failed for venta: ' . $stmt_venta->error);
    }
    if (!$stmt_venta->execute()) {
        throw new Exception('Execute failed for venta: ' . $stmt_venta->error);
    }
    $id_venta = $conexion->insert_id;

    // Re-ejecutar la query del carrito para los detalles
    if (!$stmt_carrito->execute()) {
        throw new Exception('Re-execute failed for carrito: ' . $stmt_carrito->error);
    }
    $result_carrito = $stmt_carrito->get_result();

    // Insertar detalles del pedido
    while ($item = $result_carrito->fetch_assoc()) {
        $monto_unico = (int)($item['precio'] * $item['dc_cantidad']);
        $sql_detalle = "INSERT INTO detalles_pedido (id_venta, id_producto, dp_cantidad, monto_unico) 
                        VALUES (?, ?, ?, ?)";
        $stmt_detalle = $conexion->prepare($sql_detalle);
        if (!$stmt_detalle) {
            throw new Exception('Prepare failed for detalle: ' . $conexion->error);
        }
        if (!$stmt_detalle->bind_param("iiii", $id_venta, $item['id_producto'], $item['dc_cantidad'], $monto_unico)) {
            throw new Exception('Bind failed for detalle: ' . $stmt_detalle->error);
        }
        if (!$stmt_detalle->execute()) {
            throw new Exception('Execute failed for detalle: ' . $stmt_detalle->error);
        }
    }

    // Obtener id_carrito
    $sql_id_carrito = "SELECT id_carrito FROM carrito_compras WHERE id_usuario = ?";
    $stmt_id_carrito = $conexion->prepare($sql_id_carrito);
    if (!$stmt_id_carrito) {
        throw new Exception('Prepare failed for id_carrito: ' . $conexion->error);
    }
    if (!$stmt_id_carrito->bind_param("i", $id_usuario)) {
        throw new Exception('Bind failed for id_carrito: ' . $stmt_id_carrito->error);
    }
    if (!$stmt_id_carrito->execute()) {
        throw new Exception('Execute failed for id_carrito: ' . $stmt_id_carrito->error);
    }
    $result_id_carrito = $stmt_id_carrito->get_result();
    $carrito = $result_id_carrito->fetch_assoc();
    $id_carrito = $carrito['id_carrito'] ?? 0;

    // Vaciar detalles_carrito
    $sql_vaciar_detalles = "DELETE FROM detalles_carrito WHERE id_carrito = ?";
    $stmt_vaciar_detalles = $conexion->prepare($sql_vaciar_detalles);
    if (!$stmt_vaciar_detalles) {
        throw new Exception('Prepare failed for vaciar_detalles: ' . $conexion->error);
    }
    if (!$stmt_vaciar_detalles->bind_param("i", $id_carrito)) {
        throw new Exception('Bind failed for vaciar_detalles: ' . $stmt_vaciar_detalles->error);
    }
    if (!$stmt_vaciar_detalles->execute()) {
        throw new Exception('Execute failed for vaciar_detalles: ' . $stmt_vaciar_detalles->error);
    }

    // Eliminar carrito_compras
    $sql_eliminar_carrito = "DELETE FROM carrito_compras WHERE id_carrito = ?";
    $stmt_eliminar_carrito = $conexion->prepare($sql_eliminar_carrito);
    if (!$stmt_eliminar_carrito) {
        throw new Exception('Prepare failed for eliminar_carrito: ' . $conexion->error);
    }
    if (!$stmt_eliminar_carrito->bind_param("i", $id_carrito)) {
        throw new Exception('Bind failed for eliminar_carrito: ' . $stmt_eliminar_carrito->error);
    }
    if (!$stmt_eliminar_carrito->execute()) {
        throw new Exception('Execute failed for eliminar_carrito: ' . $stmt_eliminar_carrito->error);
    }

    $conexion->commit();

    // Enviar email con PHPMailer (rutas absolutas y sin 'use' en medio del código)
    require __DIR__ . '/phpmailer/src/PHPMailer.php';
    require __DIR__ . '/phpmailer/src/SMTP.php';
    require __DIR__ . '/phpmailer/src/Exception.php';

    $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
    $email_enviado = false;

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'farmamigoiv5@gmail.com';
        $mail->Password = 'tyxy ztbz lldk xsiv';
        $mail->SMTPSecure = \PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('farmamigoiv5@gmail.com', 'FarmaMigo');
        if (!empty($correo_usuario)) {
            $mail->addAddress($correo_usuario);
        }

        $mail->isHTML(true);
        $mail->Subject = 'Codigo de Apartado de Pedido';
        $mail->Body = "
            <h2>¡Tu pedido ha sido apartado exitosamente!</h2>
            <p>Tu codigo unico es: <strong>$codigo_unico</strong></p>
            <p>Presenta este codigo en la farmacia para completar tu compra.</p>
            <p>Valido por 7 dias.</p>
            <p>Gracias por elegir FarmaMigo.</p>
        ";
        $mail->AltBody = "Tu codigo unico es: $codigo_unico. Presentalo en la farmacia. Valido por 7 dias.";

        if (!empty($correo_usuario)) {
            $mail->send();
            $email_enviado = true;
        }
    } catch (\PHPMailer\PHPMailer\Exception $e) {
        error_log("Error enviando email: {$e->getMessage()}");
    }

    echo json_encode([
        'success' => true,
        'message' => 'Pedido apartado exitosamente',
        'codigo' => $codigo_unico,
        'email_enviado' => $email_enviado
    ]);
    exit;

} catch (Exception $e) {
    $conexion->rollback();
    echo json_encode(['success' => false, 'message' => 'Error al procesar el apartado: ' . $e->getMessage()]);
    exit;
}