<?php
session_start();
include_once '../config/conexion.php';

$contador = 0;

if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
    
    $sql = "SELECT SUM(dc.dc_cantidad) as total 
            FROM detalles_carrito dc 
            JOIN carrito_compras cc ON dc.id_carrito = cc.id_carrito 
            WHERE cc.id_usuario = $id_usuario";
    
    $result = mysqli_query($conexion, $sql);
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $contador = $row['total'] ? $row['total'] : 0;
    }
}

// SOLO ESTA LÍNEA - SIN DEBUG
echo json_encode(['contador' => $contador]);
?>