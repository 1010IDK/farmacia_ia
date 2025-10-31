<?php
include("../config/conexion.php");

// Obtener la categoría desde la URL
$categoria_id = isset($_GET['categoria']) ? intval($_GET['categoria']) : 1;

// Consulta CORREGIDA - sin la columna 'activo'
$sql = "SELECT * FROM productos WHERE id_categoria = $categoria_id";
$resultado = mysqli_query($conexion, $sql);
//nombre categoria 
$sql_categoria = "SELECT categoria FROM categorias WHERE id_categoria = $categoria_id";
$resultado_categoria = mysqli_query($conexion, $sql_categoria);

if ($resultado_categoria && mysqli_num_rows($resultado_categoria) > 0) {
    $fila_categoria = mysqli_fetch_assoc($resultado_categoria);
    $nombre_categoria = $fila_categoria['categoria'];
} else {
    $nombre_categoria = "Categoría No Encontrada";
}
// Verificar si hay error
if (!$resultado) {
    die("Error en la consulta: " . mysqli_error($conexion));
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
    <link rel="stylesheet" href="../diseno/css/catalogo.css">
    <link rel="icon" href="../diseno/icons/catalogo.svg" type="image/svg+xml">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo de productos</title>
</head>
<body style="background-color: #1B3C53;">
  
    <!--barra de navegacion-->
  <nav class="navbar navbar-expand-lg bg-body-tertiary" id="navegador">
        <div class="container-fluid">
          <a class="navbar-brand" href="../index_t.php"><img src="../diseno/img/navbar2.png" alt="farmacia" id="farmacia"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse p-2 text-white" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ">
              <!-- <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
              </li> -->
                    <li class="nav-item" id="carrito">
                        <!-- Botón del carrito -->
                        <button class="btn btn-outline-dark" type="button" onclick="showLoginAlert()">
                            <i class="bi-cart-fill me-1 text-white"></i>
                            Carrito
                            <span class="badge text-black ms-1 text-white">0</span>
                        </button>

                        <!-- Toast container -->
                        <div class="toast-container position-fixed top-0 end-0 p-3">
                            <div id="loginToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                                <div class="toast-header bg-light border-start border-3 border-danger">
                                    <i class="bi bi-exclamation-circle-fill text-danger me-2"></i>
                                    <strong class="me-auto text-dark">Acceso requerido</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                </div>
                                <div class="toast-body bg-light text-dark">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-info-circle text-danger me-2"></i>
                                        <span>Debes iniciar sesión para acceder al carrito de compras.</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            function showLoginAlert() {
                                const toast = new bootstrap.Toast(document.getElementById('loginToast'));
                                toast.show();
                            }
                        </script>
                    </li>
                </ul>
            <div class="d-flex flex-row-reverse" id="search">
                    <div class="p-2 ">
                        <form class="d-flex" role="search" method="GET" action="catalogo.php">
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
                          <li><a class="dropdown-item" href="catalogo.php">Todos los productos</a></li>
                          <li><hr class="dropdown-divider"></li>
                          <li><a class="dropdown-item" href="catalogo_spec.php?categoria=1">Comida y bebida</a></li>
                          <li><a class="dropdown-item" href="catalogo_spec.php?categoria=2">Medicina</a></li>
                          <li><a class="dropdown-item" href="catalogo_spec.php?categoria=3">Cuidado personal</a></li>
                        </ul>
                      </li>
                </div>
              </div>
              <ul class="navbar-nav mb-2 mb-lg-0 ">
                <li class="nav-item">
                  <a class="nav-link " href="login_register_user.php" id="iniciar_sesion"><i class="bi bi-person-fill"></i>    Iniciar sesion</a> 
                </li>
              </ul>
          </div>
        </div>    
  </nav>
  <header class="bg-dark py-5" id="header-catalogo">
            <div class="container px-4 px-lg-5 my-5">
                <div class="text-center text-white" >
                    <h1 class="display-4 fw-bolder">Farmamigo IV, tu farmacia de confianza</h1>
                    <p class="lead fw-normal text-white-50 mb-0">Explora nuestro extenso catalogo de productos de <?php echo $nombre_categoria; ?></p>
                </div>
            </div>
        </header>
  <section class="productos_cartas">
  <div class="container px-4 px-lg-5 ">
      <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center ">
          <?php if ($resultado && mysqli_num_rows($resultado) > 0): ?>
              <?php while($producto = mysqli_fetch_assoc($resultado)): ?>
                  <div class="col mb-5">
                      <div class="card h-100 text-white" id="carta_producto">
                          <!-- Product image-->
                           <?php
                          // 1. Obtener la ruta de la base de datos
                            $ruta_db = $producto['imagen'] ?? 'diseno/img/default.png';

                          // 2. Preceder con '../' para salir de la carpeta 'vistas/'
                            $ruta_final = '../' . $ruta_db;

                          // La ruta resultante será: '../img/prod_68f153025adee.png'
                          ?>
                          <img class="card-img-top"
                          src="<?php echo htmlspecialchars($ruta_final); ?>"
                          alt="Imagen producto" />
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
                                <a class="btn btn-outline-dark mt-auto" href="producto_log.php?id=<?php echo $producto['id_producto']; ?>">Ver producto</a>
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
                            <a href="catalogo.php" class="text-light text-decoration-none">
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
</html>