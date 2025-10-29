<?php
session_start();
include_once '../config/conexion.php';

$contador = 0;

if (isset($_SESSION['id_usuario'])) {
    $id_usuario = $_SESSION['id_usuario'];
    
    $stmt = $conexion->prepare("SELECT SUM(dc.dc_cantidad) as total 
                          FROM detalles_carrito dc 
                          JOIN carrito_compras cc ON dc.id_carrito = cc.id_carrito 
                          WHERE cc.id_usuario = ?");
    $stmt->execute([$id_usuario]);
    $result = $stmt->fetch();
    
    $contador = $result['total'] ? $result['total'] : 0;
}

echo json_encode(['contador' => $contador]);
?>