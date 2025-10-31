<?php
session_start();
include_once "../config/conexion.php";

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login_register_user.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];
// Consulta el nombre del usuario
$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT nombre FROM usuarios WHERE id_usuario = $id_usuario";
$resultado = mysqli_query($conexion, $sql);
$nombre = "Usuario";
if ($resultado && $fila = mysqli_fetch_assoc($resultado)) {
    $nombre = $fila['nombre'];
}

// CAMBIAR ESTO:
$stmt = $conexion->prepare("SELECT 
    dc.id_detalle_car,
    p.id_producto,
    p.nombre,
    p.descripcion,
    p.precio,
    p.cantidad as stock,
    p.imagen,
    dc.dc_cantidad,
    c.categoria
FROM detalles_carrito dc
JOIN carrito_compras cc ON dc.id_carrito = cc.id_carrito
JOIN productos p ON dc.id_producto = p.id_producto
JOIN categorias c ON p.id_categoria = c.id_categoria
WHERE cc.id_usuario = ?");

$stmt->bind_param("i", $id_usuario); // "i" = integer
$stmt->execute();

// EN MYSQLI SE USA get_result() y fetch_assoc()
$result = $stmt->get_result();
$productos_carrito = [];
while ($row = $result->fetch_assoc()) {
    $productos_carrito[] = $row;
}
// Calcular totales
$subtotal = 0;
foreach ($productos_carrito as $producto) {
    $subtotal += $producto['precio'] * $producto['dc_cantidad'];
}
$envio = 5.00; // Costo fijo de envío
$impuestos = $subtotal * 0.16; // 16% de impuestos
$total = $subtotal + $envio + $impuestos;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!--Boostrap-->
    <link href="../boostrap/css/bootstrap.min.css" rel="stylesheet">
    <!--Iconos-->
    <link rel="stylesheet" href="../diseno/icons/icons-1.13.1/font/bootstrap-icons.min.css">
    <!--Css-->
    <link rel="stylesheet" href="../diseno/css/carrito.css">
    <link rel="stylesheet" href="../diseno/css/nav_log.css">
    <link rel="icon" href="../diseno/icons/cart.svg" type="image/svg+xml">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
</head>

<body style="background-color: #1B3C53;">
    <nav class="navbar navbar-expand-lg bg-body-tertiary" id="navegador">
        <div class="container-fluid d-flex flex-row">
            <a class="navbar-brand" href="../index_logeado.php"><img src="../diseno/img/navbar2.png" alt="farmacia" id="farmacia"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse p-2 text-white" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
                    <!-- <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
              </li> -->
                    <li class="nav-item" id="carrito">
    <a href="carrito.php" class="btn btn-outline-dark">
        <i class="bi-cart-fill me-1 text-white"></i>
        Cart
        <span class="badge text-black ms-1 text-white carrito-counter carrito-counter"> <!-- AGREGAR CLASE AQUÍ -->
            <?php
            // Mostrar contador actual del carrito
            if (isset($_SESSION['id_usuario'])) {
                $id_usuario = $_SESSION['id_usuario'];
                $sql_contador = "SELECT SUM(dc.dc_cantidad) as total 
                                FROM detalles_carrito dc 
                                JOIN carrito_compras cc ON dc.id_carrito = cc.id_carrito 
                                WHERE cc.id_usuario = $id_usuario";
                $res_contador = mysqli_query($conexion, $sql_contador);
                $contador = mysqli_fetch_assoc($res_contador);
                echo $contador['total'] ? $contador['total'] : 0;
            } else {
                echo 0;
            }
            ?>
        </span>
    </a>
</li>
                </ul>
                <div class="d-flex flex-row-reverse" id="search">
                <div class="p-2 ">
                    <form class="d-flex" role="search" method="GET" action="catalogo_log.php">
                        <input class="form-control me-2" type="search" name="busqueda" placeholder="Buscar" aria-label="Search" 
                               value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>"/>
                        <button class="btn" type="submit" id="search">
                            <i style="font-size: 15px;" class="bi bi-search text-white"></i>
                        </button>
                    </form>
                </div>
                    <div class="p-2 text-white" id="buscar">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="dropdown">
                                Categorias
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="catalogo_log.php">Todos los productos</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="catalogo_spec_log.php?categoria=1">Comida y bebida</a></li>
                                <li><a class="dropdown-item" href="catalogo_spec_log.php?categoria=2">Medicina</a></li>
                                <li><a class="dropdown-item" href="catalogo_spec_log.php?categoria=3">Cuidado personal</a></li>
                            </ul>
                        </li>
                    </div>
                </div>
                <ul class="navbar-nav mb-2 mb-lg-0 ">
                    <div class="btn-group dropstart logeado">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars($nombre); ?>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="informacion_user.php">Informacion personal</a></li>
                            <li><a class="dropdown-item" href="#">Configuracion</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item logout" href="../controladores/logout.php">Cerrar sesion</a></li>
                        </ul>
                    </div>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Contenido del carrito -->
    <div class="container mt-4" id="carrito-container">
        <?php if (empty($productos_carrito)): ?>
            <!-- Carrito Vacío -->
            <div class="card text-center" style="background-color: #456882; border-color: #D2C1B6;">
                <div class="card-body py-5">
                    <i class="bi-cart-x display-1 text-white mb-3"></i>
                    <h3 class="text-white mb-3">Tu carrito está vacío</h3>
                    <p class="text-light mb-4">Agrega algunos productos para continuar</p>
                    <a href="catalogo_log.php" class="btn text-white" style="background-color: #1B3C53; border-color: #D2C1B6;">
                        <i class="bi-arrow-left me-2"></i>Ir a Comprar
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8">
                    <!-- Header del carrito -->
                    <div class="card mb-4" style="background-color: #456882; border-color: #D2C1B6;">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="card-title text-white mb-0">
                                    <i class="bi-cart-fill me-2"></i>Tu Carrito de Compras
                                </h2>
                                <button class="btn btn-sm text-white vaciar-carrito" style="background-color: #dc3545;">
                                    <i class="bi-trash me-1"></i>Vaciar Carrito
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de productos -->
                    <?php foreach ($productos_carrito as $producto): ?>
                        <div class="card mb-3 producto-item" style="background-color: #456882; border-color: #D2C1B6;" data-id="<?php echo $producto['id_detalle_car']; ?>">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <img src="../<?php echo $producto['imagen']; ?>" alt="<?php echo $producto['nombre']; ?>"
                                        class="img-thumbnail me-3" style="width: 100px; height: 100px; object-fit: cover; background-color: #F9F3EF;">
                                    <div class="flex-grow-1">
                                        <h5 class="text-white mb-1"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                        <p class="text-light mb-1">Categoría: <?php echo htmlspecialchars($producto['categoria']); ?></p>
                                        <p class="text-light mb-1">Stock disponible: <?php echo $producto['stock']; ?></p>
                                        <p class="text-warning mb-0 fw-bold">Bs.<?php echo number_format($producto['precio'], 2); ?></p>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex align-items-center me-3">
                                            <button class="btn btn-sm text-white restar-cantidad" style="background-color: #1B3C53;">
                                                <i class="bi-dash"></i>
                                            </button>
                                            <span class="mx-3 text-white cantidad"><?php echo $producto['dc_cantidad']; ?></span>
                                            <button class="btn btn-sm text-white sumar-cantidad" style="background-color: #1B3C53;">
                                                <i class="bi-plus"></i>
                                            </button>
                                        </div>
                                        <button class="btn btn-sm text-danger eliminar-producto">
                                            <i class="bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-2 text-end">
                                    <strong class="text-white">Subtotal: Bs.<?php echo number_format($producto['precio'] * $producto['dc_cantidad'], 2); ?></strong>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Resumen del pedido -->
                <div class="col-lg-4">
                    <div class="card sticky-top" style="top: 80px; background-color: #456882; border-color: #D2C1B6;">
                        <div class="card-body">
                            <h4 class="text-white mb-4">Resumen del Pedido</h4>

                            <div class="d-flex justify-content-between text-light mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotal-general">Bs.<?php echo number_format($subtotal, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between text-light mb-2">
                                <span>Envío:</span>
                                <span id="envio">Bs.<?php echo number_format($envio, 2); ?></span>
                            </div>
                            <div class="d-flex justify-content-between text-light mb-3">
                                <span>Impuestos (16%):</span>
                                <span id="impuestos">Bs.<?php echo number_format($impuestos, 2); ?></span>
                            </div>

                            <div class="d-flex justify-content-between text-white mb-4">
                                <strong>Total:</strong>
                                <strong id="total-final">Bs.<?php echo number_format($total, 2); ?></strong>
                            </div>

                            <button class="btn w-100 text-white mb-3 proceder-apartado" 
                            style="background-color: #1B3C53; border-color: #D2C1B6;">
                            <i class="bi-lock-fill me-2"></i>Apartar Pedido
                            </button>

                            <a href="catalogo_log.php" class="btn w-100 text-white"
                                style="background-color: transparent; border-color: #D2C1B6;">
                                <i class="bi-arrow-left me-2"></i>Seguir Comprando
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <footer class="py-5" style="background-color: #151110;">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p>
        </div>
    </footer>
    <script src="../boostrap/js/bootstrap.bundle.min.js"></script>
    <script src="../js/carrito.js"></script>
</body>

</html>