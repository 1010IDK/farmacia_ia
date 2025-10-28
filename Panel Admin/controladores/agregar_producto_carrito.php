<?php
session_start();
include("../config/conexion.php"); // Ajusta la ruta según tu estructura

if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../vistas/login_register_user.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = $_SESSION['id_usuario'];
    $id_producto = intval($_POST['id_producto']);
    $cantidad = intval($_POST['cantidad']);

    // 1. Verificar stock disponible
    $sql_stock = "SELECT cantidad, nombre FROM productos WHERE id_producto = $id_producto";
    $res_stock = mysqli_query($conexion, $sql_stock);
    $producto = mysqli_fetch_assoc($res_stock);

    if (!$producto) {
        header('Location: ../vistas/producto_log.php?id=' . $id_producto . '&error=Producto no encontrado');
        exit;
    }

    // CORRECCIÓN: Verificar que haya suficiente stock
    if ($producto['cantidad'] < $cantidad) {
        header('Location: ../vistas/producto_log.php?id=' . $id_producto . '&error=Stock insuficiente. Disponible: ' . $producto['cantidad']);
        exit;
    }

    // 2. Obtener o crear carrito del usuario
    $sql_carrito = "SELECT id_carrito FROM carrito_compras WHERE id_usuario = $id_usuario";
    $res_carrito = mysqli_query($conexion, $sql_carrito);
    $carrito = mysqli_fetch_assoc($res_carrito);

    if (!$carrito) {
        // Crear nuevo carrito
        $sql_nuevo_carrito = "INSERT INTO carrito_compras (id_usuario, fecha_creacion) VALUES ($id_usuario, NOW())";
        if (mysqli_query($conexion, $sql_nuevo_carrito)) {
            $id_carrito = mysqli_insert_id($conexion);
        } else {
            header('Location: ../vistas/producto_log.php?id=' . $id_producto . '&error=Error al crear carrito');
            exit;
        }
    } else {
        $id_carrito = $carrito['id_carrito'];
    }

    // 3. Verificar si el producto ya está en el carrito
    $sql_existente = "SELECT id_detalle_car, dc_cantidad FROM detalles_carrito WHERE id_carrito = $id_carrito AND id_producto = $id_producto";
    $res_existente = mysqli_query($conexion, $sql_existente);
    $existente = mysqli_fetch_assoc($res_existente);

    if ($existente) {
        // Actualizar cantidad existente
        $nueva_cantidad = $existente['dc_cantidad'] + $cantidad;
        
        // Verificar que no exceda el stock
        if ($nueva_cantidad > $producto['cantidad']) {
            header('Location: ../vistas/producto_log.php?id=' . $id_producto . '&error=No puedes agregar más de ' . $producto['cantidad'] . ' unidades');
            exit;
        }
        
        $sql_actualizar = "UPDATE detalles_carrito SET dc_cantidad = $nueva_cantidad WHERE id_detalle_car = " . $existente['id_detalle_car'];
        mysqli_query($conexion, $sql_actualizar);
    } else {
        // Agregar nuevo producto al carrito
        $sql_agregar = "INSERT INTO detalles_carrito (id_carrito, id_producto, dc_cantidad) VALUES ($id_carrito, $id_producto, $cantidad)";
        mysqli_query($conexion, $sql_agregar);
    }

    // 4. Redirigir con mensaje de éxito
    header('Location: ../vistas/producto_log.php?id=' . $id_producto . '&success=true');
    exit;
} else {
    header('Location: ../vistas/catalogo_log.php');
    exit;
}
?>