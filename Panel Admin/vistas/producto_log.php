<?php
include("../config/conexion.php");
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
include("../config/conexion.php");

session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login_register_user.php");
    exit();
}
if (!isset($_GET['id'])) {
    echo "<script>alert('Producto no especificado'); window.location.href='catalogo_log.php';</script>";
    exit();
}

$id_producto = intval($_GET['id']);
$sql_producto = "SELECT * FROM productos WHERE id_producto = $id_producto";
$res_producto = mysqli_query($conexion, $sql_producto);

if (!$res_producto || mysqli_num_rows($res_producto) == 0) {
    echo "<script>alert('Producto no encontrado'); window.location.href='catalogo_log.php';</script>";
    exit();
}

$producto = mysqli_fetch_assoc($res_producto);

// Prepare variables with fallbacks and XSS protection
$imagen = htmlspecialchars($producto['imagen'] ?? '../diseno/img/default.png');
$id_producto = htmlspecialchars($producto['id_producto'] ?? 'N/A');
$nombre_producto = htmlspecialchars($producto['nombre'] ?? 'Producto sin nombre');
$precio = is_numeric($producto['precio'] ?? null) ? number_format((float)$producto['precio'], 2) : '0.00';
$descripcion = htmlspecialchars($producto['descripcion'] ?? 'Sin descripción');

// Consulta el nombre del usuario
$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT nombre FROM usuarios WHERE id_usuario = $id_usuario";
$resultado = mysqli_query(mysql: $conexion, query: $sql);
$nombre = "Usuario";

if ($resultado && $fila = mysqli_fetch_assoc(result: $resultado)) {
    $nombre = $fila['nombre'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--Boostrap-->
    <link href="../boostrap/css/bootstrap.min.css" rel="stylesheet">
    <!--Iconos-->
    <link rel="stylesheet" href="../diseno/icons/icons-1.13.1/font/bootstrap-icons.min.css">
    <!--Css-->
    <link rel="stylesheet" href="../diseno/css/nav_log.css">
    <link rel="stylesheet" href="../diseno/css/producto.css">
    <link rel="icon" href="../diseno/icons/catalogo.svg" type="image/svg+xml">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nombre del producto</title>
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
        <span class="badge text-black ms-1 text-white">
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
                                value="<?php echo isset($_GET['busqueda']) ? htmlspecialchars($_GET['busqueda']) : ''; ?>" />
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
                          <li><a class="dropdown-item" href=
                          "catalogo_log.php">Todos los productos</a></li>
                          <li><hr class="dropdown-divider"></li>
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
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item logout" href="../controladores/logout.php">Cerrar sesion</a></li>
                  </ul>
                </div>
              </ul>
          </div>
        </div>    
  </nav>
  <!-- Product section-->
<!-- Product section-->
<section class="py-5 producto" id="producto">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6">
                <?php
                // 1. Obtener la ruta de la base de datos
                $ruta_db = $producto['imagen'] ?? 'diseno/img/default.png';
                // 2. Preceder con '../' para salir de la carpeta 'vistas/'
                $ruta_final = '../' . $ruta_db;
                ?>
                <img class="card-img-top mb-5 mb-md-0" src="<?php echo $ruta_final; ?>" alt="Imagen de <?php echo $nombre_producto; ?>" />
            </div>
            <div class="col-md-6">
                <div class="small mb-1">ID: <?php echo $id_producto; ?></div>
                <h1 class="display-5 fw-bolder text-white"><?php echo $nombre_producto; ?></h1>
                <div class="fs-5 mb-5">
                    <span class="text-white">Bs.<?php echo $precio; ?></span>
                </div>
                <p class="lead text-white"><?php echo $descripcion; ?></p>
                
                <!-- STOCK Y CANTIDAD -->
                <div class="mb-3">
                    <strong class="text-white">Stock disponible: <?php echo $producto['cantidad']; ?></strong>
                </div>
                
                <!-- FORMULARIO PARA AGREGAR AL CARRITO -->
                <form action="../controladores/agregar_producto_carrito.php" method="post">
                    <input type="hidden" name="id_producto" value="<?php echo $id_producto; ?>">
                    <div class="d-flex align-items-center mb-3">
                        <label class="text-white me-2">Cantidad:</label>
                        <input class="form-control text-center me-3" name="cantidad" 
                               type="number" value="1" min="1" max="<?php echo $producto['cantidad']; ?>" 
                               style="max-width: 5rem" required />
                    </div>
                    <button class="btn text-white flex-shrink-0" type="submit" 
                            style="background-color: #1B3C53; border-color: #D2C1B6;">
                        <i class="bi-cart-fill me-1"></i>
                        Añadir al carrito
                    </button>
                </form>
                
                <!-- MENSAJES DE ALERTA -->
                <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
                    <div class="alert alert-success mt-3" role="alert">
                        ✅ Producto agregado al carrito correctamente
                    </div>
                <?php elseif (isset($_GET['error'])): ?>
                    <div class="alert alert-danger mt-3" role="alert">
                        ❌ Error: <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!-- Related items section-->
<section class="py-5 productos_cartas" id="productos_cartas">
    <div class="container px-4 px-lg-5 mt-5">
        <h2 class="fw-bolder mb-4 text-white">También te podrían interesar</h2>
        <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
            <?php
            // Consulta para productos relacionados (misma categoría, excluyendo el producto actual)
            $id_categoria_actual = $producto['id_categoria'];
            $sql_relacionados = "SELECT * FROM productos 
                               WHERE id_categoria = $id_categoria_actual 
                               AND id_producto != $id_producto 
                               AND cantidad > 0 
                               LIMIT 4";
            $resultado_relacionados = mysqli_query($conexion, $sql_relacionados);
            
            if ($resultado_relacionados && mysqli_num_rows($resultado_relacionados) > 0):
                while($producto_relacionado = mysqli_fetch_assoc($resultado_relacionados)):
                    $ruta_db_rel = $producto_relacionado['imagen'] ?? 'diseno/img/default.png';
                    $ruta_final_rel = '../' . $ruta_db_rel;
                    $precio_rel = number_format($producto_relacionado['precio'], 2);
            ?>
            <div class="col mb-5">
                <div class="card h-100 text-white" id="carta_producto">
                    <!-- Product image-->
                    <img class="card-img-top" src="<?php echo htmlspecialchars($ruta_final_rel); ?>" alt="<?php echo htmlspecialchars($producto_relacionado['nombre']); ?>" />
                    <!-- Product details-->
                    <div class="card-body p-4">
                        <div class="text-center">
                            <!-- Product name-->
                            <h5 class="fw-bolder"><?php echo htmlspecialchars($producto_relacionado['nombre']); ?></h5>
                            <!-- Product price-->
                            Bs.<?php echo $precio_rel; ?>
                        </div>
                    </div>
                    <!-- Product actions-->
                    <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                        <div class="text-center">
                            <a class="btn btn-outline-dark mt-auto" href="producto_log.php?id=<?php echo $producto_relacionado['id_producto']; ?>">
                                Ver producto
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                endwhile;
            else:
                // Si no hay productos relacionados, mostrar productos aleatorios
                $sql_aleatorios = "SELECT * FROM productos 
                                 WHERE id_producto != $id_producto 
                                 AND cantidad > 0 
                                 ORDER BY RAND() 
                                 LIMIT 4";
                $resultado_aleatorios = mysqli_query($conexion, $sql_aleatorios);
                
                if ($resultado_aleatorios && mysqli_num_rows($resultado_aleatorios) > 0):
                    while($producto_aleatorio = mysqli_fetch_assoc($resultado_aleatorios)):
                        $ruta_db_ale = $producto_aleatorio['imagen'] ?? 'diseno/img/default.png';
                        $ruta_final_ale = '../' . $ruta_db_ale;
                        $precio_ale = number_format($producto_aleatorio['precio'], 2);
            ?>
            <div class="col mb-5">
                <div class="card h-100 text-white" id="carta_producto">
                    <!-- Product image-->
                    <img class="card-img-top" src="<?php echo htmlspecialchars($ruta_final_ale); ?>" alt="<?php echo htmlspecialchars($producto_aleatorio['nombre']); ?>" />
                    <!-- Product details-->
                    <div class="card-body p-4">
                        <div class="text-center">
                            <!-- Product name-->
                            <h5 class="fw-bolder"><?php echo htmlspecialchars($producto_aleatorio['nombre']); ?></h5>
                            <!-- Product price-->
                            Bs.<?php echo $precio_ale; ?>
                        </div>
                    </div>
                    <!-- Product actions-->
                    <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                        <div class="text-center">
                            <a class="btn btn-outline-dark mt-auto" href="producto_log.php?id=<?php echo $producto_aleatorio['id_producto']; ?>">
                                Ver producto
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php
                    endwhile;
                else:
            ?>
            <div class="col-12 text-center text-white">
                <p>No hay productos disponibles en este momento.</p>
            </div>
            <?php
                endif;
            endif;
            ?>
        </div>
    </div>
</section>
        <!-- Footer-->
<footer class="bg-dark text-white pt-5 pb-4">
        <div class="container">
            <div class="row">
                <!-- Columna 1: Información de la farmacia -->
                <div class="col-lg-4 col-md-6 mb-4">
                    <h5 class="text-warning mb-3">
                        <i class="bi bi-capsule me-2"></i>Farmamigo IV
                    </h5>
                    <p class="text-light mb-3">
                        Tu salud es nuestra prioridad. Ofrecemos medicamentos de calidad,
                        asesoramiento profesional y servicio personalizado.
                    </p>
                    <div class="d-flex">
                        <a href="#" class="text-white me-3">
                            <i class="bi bi-facebook fs-5"></i>
                        </a>
                        <a href="#" class="text-white me-3">
                            <i class="bi bi-instagram fs-5"></i>
                        </a>
                        <a href="#" class="text-white me-3">
                            <i class="bi bi-whatsapp fs-5"></i>
                        </a>
                        <a href="#" class="text-white">
                            <i class="bi bi-telephone fs-5"></i>
                        </a>
                    </div>
                </div>

                <!-- Columna 2: Enlaces rápidos -->
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="text-warning mb-3">Enlaces Rápidos</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <a href="#" class="text-light text-decoration-none">
                                <i class="bi bi-chevron-right me-1 small"></i>Inicio
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="catalogo_log.php" class="text-light text-decoration-none">
                                <i class="bi bi-chevron-right me-1 small"></i>Medicamentos
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Columna 3: Servicios -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="text-warning mb-3">Nuestros Servicios</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <span class="text-light">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Asesoramiento farmacéutico
                            </span>
                        </li>
                        <li class="mb-2">
                            <span class="text-light">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Control de presión arterial
                            </span>
                        </li>
                        <li class="mb-2">
                            <span class="text-light">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Atencion de calidad
                            </span>
                        </li>
                        <li class="mb-2">
                            <span class="text-light">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Reserva online
                            </span>
                        </li>
                    </ul>
                </div>

                <!-- Columna 4: Contacto y horario -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="text-warning mb-3">Contacto</h6>
                    <div class="mb-3">
                        <p class="mb-1">
                            <i class="bi bi-geo-alt text-warning me-2"></i>
                            <small>San Pedro de los Altos en el centro comercial San Pedro, sector Ventorrillo Cipreces, calle proceso, Vía Principal de San Pedro, 1201, Miranda.</small>
                        </p>
                        <p class="mb-1">
                            <i class="bi bi-telephone text-warning me-2"></i>
                            <small>0424 5876950</small>
                        </p>
                        <p class="mb-1">
                            <i class="bi bi-whatsapp text-warning me-2"></i>
                            <small>0424 5876950</small>
                        </p>
                        <p class="mb-1">
                            <i class="bi bi-envelope text-warning me-2"></i>
                            <small>farmamigoiv5@gmail.com</small>
                        </p>
                    </div>

                    <h6 class="text-warning mb-2">Horario</h6>
                    <div class="mb-2">
                        <small class="text-light">Lunes a Viernes: 7:00 AM - 10:00 PM</small>
                    </div>
                    <div class="mb-2">
                        <small class="text-light">Sábados: 8:00 AM - 8:00 PM</small>
                    </div>
                    <div>
                        <small class="text-light">Domingos: 9:00 AM - 2:00 PM</small>
                    </div>
                </div>
            </div>

            <hr class="bg-warning my-4">

            <!-- Fila inferior -->
            <div class="row align-items-center">
                <div class="col-md-6 mb-2 mb-md-0">
                    <small class="text-light">
                        &copy; 2025 Programadores universitarios anónimos.
                    </small>
                </div>
            </div>
        </div>
    </footer>
    <script defer src="../boostrap/js/bootstrap.bundle.min.js"></script>
  </body>