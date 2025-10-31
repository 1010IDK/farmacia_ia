<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");
include("config/conexion.php");

// Verifica si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: vistas/login_register_user.php");
    exit();
}

// Consulta el nombre del usuario
$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT nombre FROM usuarios WHERE id_usuario = $id_usuario";
$resultado = mysqli_query($conexion, $sql);
$nombre = "Usuario";

if ($resultado && $fila = mysqli_fetch_assoc($resultado)) {
    $nombre = $fila['nombre'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--Boostrap-->
    <link href="./boostrap/css/bootstrap.min.css" rel="stylesheet">
    <!--Iconos-->
    <link rel="stylesheet" href="diseno/icons/icons-1.13.1/font/bootstrap-icons.min.css">
    <!--Css-->
    <link rel="stylesheet" href="diseno/css/login.css">
    <link rel="icon" href="diseno/icons/house.svg" type="image/svg+xml">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body style="background-color: #1B3C53;">
    <!--barra de navegacion-->
  <nav class="navbar navbar-expand-lg bg-body-tertiary" id="navegador">
        <div class="container-fluid d-flex flex-row">
          <a class="navbar-brand" href="index_logeado.php"><img src="diseno/img/navbar2.png" alt="farmacia" id="farmacia"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse p-2 text-white" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
              <!-- <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
              </li> -->
             <li class="nav-item" id="carrito">
    <a href="vistas/carrito.php" class="btn btn-outline-dark">
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
                        <form class="d-flex" role="search" method="GET" action="vistas/catalogo_log.php">
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
                          "vistas/catalogo_log.php">Todos los productos</a></li>
                          <li><hr class="dropdown-divider"></li>
                          <li><a class="dropdown-item" href="vistas/catalogo_spec_log.php?categoria=1">Comida y bebida</a></li>
                          <li><a class="dropdown-item" href="vistas/catalogo_spec_log.php?categoria=2">Medicina</a></li>
                          <li><a class="dropdown-item" href="vistas/catalogo_spec_log.php?categoria=3">Cuidado personal</a></li>
                        </ul>
                      </li>
                </div>
              </div>
              <ul class="navbar-nav mb-2 mb-lg-0">
                <div class="btn-group dropstart logeado">
                  <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <?php echo htmlspecialchars(string: $nombre); ?>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="vistas/informacion_user.php">Informacion personal</a></li>
                    <li><a class="dropdown-item" href="#">Configuracion</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item logout" href="controladores/logout.php">Cerrar sesion</a></li>
                  </ul>
                </div>
              </ul>
          </div>
        </div>    
  </nav>
  <!--Contenido principal-->
  <div class="container px-4 px-lg-5">
    <!--carrusel-->
    <div class="row gx-4 gx-lg-5 align-items-center my-5 content-1">
        <div class="col-lg-7"><div id="carouselExampleRide" class="carousel slide" data-bs-ride="true">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="diseno/img/carrusel/carrusel (1).png" class="d-block w-100" alt="">
              </div>
              <div class="carousel-item">
                <img src="diseno/img/carrusel/carrusel (3).png" class="d-block w-100" alt="">
              </div>
              <div class="carousel-item">
                <img src="diseno/img/carrusel/carrusel (4).png" class="d-block w-100" alt="">
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleRide" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Next</span>
            </button>
          </div></div>
        <div class="col-lg-5" id="carrusel-contenido">
            <h1 class="font-weight-light">Farmamigo IV, tu farmacia de confianza</h1>
            <p>Nos enorgullece ofrecer productos de alta calidad, precios competitivos y una atención al cliente cálida y extraordinaria. Ahora, con el lanzamiento de nuestra nueva página web, ampliamos nuestros servicios para que tu experiencia sea aún mejor. <br> 🚀 ¿Qué estás esperando? <br> Haz tu pedido hoy y descubre por qué somos tu mejor opción. </p>
            <a class="btn btn-primary" href="#productos_cartas">Ver productos</a>
        </div>
    </div>
    <!--Carta explicativa-->
    <div class="card text-white bg-secondary my-5 py-4 text-center">
        <div class="card-body"><p class="text-white m-0">Ofrecemos una diversidad de productos notables pensados para satisfacer las necesidades de nuestra clientela.
        <br> 📲 Realiza tu pedido fácilmente a través de nuestra página web. 
        <br> 🏪 Retíralo en nuestro comercio con total seguridad y comodidad.</p></div>
    </div>
    <!--Cartas de ayuda-->
    <div class="row gx-4 gx-lg-5 ">
        <div class="col-md-4 mb-5 ">
            <div class="card h-100 ">
                <div class="card-body text-white" id="ayuda">
                    <h2 class="card-title">🛍️ Aparta tus productos sin salir de casa</h2>
                    <p class="card-text ">Ofrecemos un sistema de apartado en línea para que no tengas que venir al local. Nuestro inventario se actualiza en tiempo real con cada venta, así que siempre verás la disponibilidad actualizada. ¿Viste algo que te interesa? ¡Aparta con confianza! Al llegar, tu producto estará reservado y listo para ti.</p>
                </div>
                <div class="card-footer"><a class="btn btn-outline-dark mt-auto text-white" href="vistas/catalogo_log.php">Empezar a pedir</a></div>
            </div>
        </div>
        <div class="col-md-4 mb-5">
            <div class="card h-100">
                <div class="card-body text-white">
                    <h2 class="card-title">🛒 ¿Te interesa un producto? ¡Apartarlo es muy fácil!</h2>
                    <p class="card-text">1 Explora nuestra página y descubre todos los productos disponibles.
                    <br> 2 Agrega al carrito los que más te gusten.
                    <br> 3 Cuando estés listo, presiona el botón “Realizar pedido” para apartarlos.
                    <br> ✅ Tu pedido quedará registrado y los productos estarán reservados para ti al momento de recogerlos.</p>
                </div>
                <div class="card-footer"><a class="btn btn-outline-dark mt-auto text-white" href="vistas/carrito.php">Ver carrito de compras</a></div>
            </div>
        </div>
        <div class="col-md-4 mb-5">
            <div class="card h-100">
                <div class="card-body text-white">
                    <h2 class="card-title">📧Retiro del pedido</h2>
                    <p class="card-text">Una vez realizado tu pedido, recibirás un correo en la dirección registrada en nuestro sistema. Este correo incluirá un código único que deberás mostrar en el local para retirar tus productos.
                    <br> 💳 Solo tienes que pagar al momento de la entrega, ¡y listo! El pedido será completamente tuyo. 
                    <br> Estamos ubicados en San Pedro de los Altos en el centro comercial San Pedro, sector Ventorrillo Cipreces, calle proceso, Vía Principal de San Pedro, 1201, Miranda. </p>
                </div>
                <div class="card-footer"><a class="btn btn-outline-dark mt-auto text-white" href="https://maps.app.goo.gl/VARqDZka8Wqyz5Hu7" target="_blank" rel="noopener noreferrer">Ubicacion</a></div>
            </div>
        </div>
    </div>
</div>
<!--Productos cartas-->
<!--Productos cartas-->
<section class="productos_cartas" id="productos_cartas">
  <div class="container px-4 px-lg-5 ">
    <p class="fs-5 fw-semibold d-inline-block me-1" style="color: #D2C1B6">Hola <?php echo htmlspecialchars($nombre); ?></p>
    <p class="fs-5 d-inline-block" style="color: #fff">, te puede interesar…</p>
      <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center ">
          <?php 
          // Consulta para obtener 8 productos aleatorios
          $query_aleatorios = "SELECT * FROM productos ORDER BY RAND() LIMIT 8";
          $resultado_aleatorios = mysqli_query($conexion, $query_aleatorios);
          
          if ($resultado_aleatorios && mysqli_num_rows($resultado_aleatorios) > 0): ?>
              <?php while($producto = mysqli_fetch_assoc($resultado_aleatorios)): ?>
                  <div class="col mb-5">
                      <div class="card h-100 text-white">
                          <!-- Product image-->
                          <?php
                          $ruta_db = $producto['imagen'] ?? 'diseno/img/default.png';
                          ?>
                          <img class="card-img-top" 
                               src="<?php echo htmlspecialchars($ruta_db); ?>" 
                               alt="<?php echo htmlspecialchars($producto['nombre']); ?>" />
                          <!-- Product details-->
                          <div class="card-body p-4">
                              <div class="text-center">
                                  <!-- Product name-->
                                  <h5 class="fw-bolder"><?php echo htmlspecialchars($producto['nombre']); ?></h5>
                                  <!-- Product price-->
                                  Bs.<?php echo number_format($producto['precio'], 2); ?>
                              </div>
                          </div>
                          <!-- Product actions-->
                          <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                              <div class="text-center">
                                  <a class="btn btn-outline-dark mt-auto" 
                                     href="vistas/producto_log.php?id=<?php echo $producto['id_producto']; ?>">
                                     Ver producto
                                  </a>
                              </div>
                          </div>
                      </div>
                  </div>
              <?php endwhile; ?>
          <?php else: ?>
              <div class="col-12 text-center text-white">
                  <p>No hay productos disponibles.</p>
              </div>
          <?php endif; ?>
      </div>
  </div>
</section>
<!--footer-->
<footer class="py-5" style="background-color: #151110;">
  <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p></div>
</footer>
  <script defer src="./boostrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>