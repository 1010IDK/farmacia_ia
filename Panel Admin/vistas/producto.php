<?php
include("../config/conexion.php");
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!--Boostrap-->
    <link href="../boostrap/css/bootstrap.min.css" rel="stylesheet">
    <!--Iconos-->
    <link rel="stylesheet" href="../diseno/icons/icons-1.13.1/font/bootstrap-icons.min.css">
    <!--Css-->
    <link rel="stylesheet" href="../diseno/css/producto.css">
    <link rel="icon" href="../diseno/icons/catalogo.svg" type="image/svg+xml">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>nombre del producto</title>
</head>
<body style="background-color: #1B3C53;">
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
                            Cart
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
                        <a class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false" id="dropdown">
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
                  <a class="nav-link" href="http://localhost/proyecto/vistas/login_register_user.php" id="iniciar_sesion"><i class="bi bi-person-fill"></i>    Iniciar sesion</a> 
                </li>
              </ul>
          </div>
        </div>    
  </nav>
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

                          // La ruta resultante será: '../img/prod_68f153025adee.png'
                          ?>
                <img class="card-img-top mb-5 mb-md-0" src="<?php echo $ruta_final; ?>" alt="Imagen de <?php echo $nombre_producto; ?>" />
            </div>
            <div class="col-md-6">
                <div class="small mb-1">ID: <?php echo $id_producto; ?></div>
                <h1 class="display-5 fw-bolder"><?php echo $nombre_producto; ?></h1>
                <div class="fs-5 mb-5">
                    <span>Bs.<?php echo $precio; ?></span>
                </div>
                <p class="lead"><?php echo $descripcion; ?></p>
                <form action="add_to_cart.php" method="post">
                    <input type="hidden" name="product_id" value="<?php echo $id_producto; ?>">
                    <input class="form-control text-center me-3" id="inputQuantity" name="quantity" type="number" value="1" min="1" style="max-width: 3rem" />
                    <button class="btn btn-outline-dark flex-shrink-0" type="submit" aria-label="Añadir <?php echo $nombre_producto; ?> al carrito">
                        <i class="bi-cart-fill me-1"></i>
                        Añadir al carrito
                    </button>
                </form>
            </div>
        </div>
    </div>
        <!-- Related items section-->
        <section class="py-5 productos_cartas" id="productos_cartas">
            <div class="container px-4 px-lg-5 mt-5">
                <h2 class="fw-bolder mb-4">Tambien te podrian interesar</h2>
                <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                    <div class="col mb-5">
                        <div class="card h-100">
                            <!-- Product image-->
                  <img class="card-img-top" src="../diseno/img/cartas-index/carta (1).jpg" alt="..." />
                  <!-- Product details-->
                  <div class="card-body p-4">
                      <div class="text-center">
                          <!-- Product name-->
                          <h5 class="fw-bolder">nombre producto</h5>
                          <!-- Product price-->
                          $40.00 - $80.00
                      </div>
                  </div>
                  <!-- Product actions-->
                  <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                      <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Ver producto</a></div>
                  </div>
              </div>
          </div>
          <div class="col mb-5">
              <div class="card h-100 text-white">
                  <!-- Sale badge-->
                  <!-- Product image-->
                  <img class="card-img-top" src="../diseno/img/cartas-index/carta (2).jpg" alt="..." />
                  <!-- Product details-->
                  <div class="card-body p-4">
                      <div class="text-center">
                          <!-- Product name-->
                          <h5 class="fw-bolder">nombre producto</h5>
                          <!-- Product price-->
                          $18.00
                      </div>
                  </div>
                  <!-- Product actions-->
                  <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                      <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Ver producto</a></div>
                  </div>
              </div>
          </div>
          <div class="col mb-5">
              <div class="card h-100 text-white">
                  <!-- Sale badge-->
                  <!-- Product image-->
                  <img class="card-img-top" src="../diseno/img/cartas-index/carta (3).jpg" alt="..." />
                  <!-- Product details-->
                  <div class="card-body p-4">
                      <div class="text-center">
                          <!-- Product name-->
                          <h5 class="fw-bolder">producto nombre</h5>
                          <!-- Product price-->
                          $25.00
                      </div>
                  </div>
                  <!-- Product actions-->
                  <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                      <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Ver producto</a></div>
                  </div>
              </div>
          </div>
          <div class="col mb-5">
              <div class="card h-100 text-white">
                  <!-- Product image-->
                  <img class="card-img-top" src="../diseno/img/cartas-index/carta (4).jpg" alt="..." />
                  <!-- Product details-->
                  <div class="card-body p-4">
                      <div class="text-center">
                          <!-- Product name-->
                          <h5 class="fw-bolder">nombre producto</h5>
                          <!-- Product price-->
                          $40.00
                      </div>
                  </div>
                  <!-- Product actions-->
                  <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                      <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="#">Ver producto</a></div>
                  </div>
              </div>
          </div>
        </section>
        <!-- Footer-->
        <footer class="py-5 bg-dark">
            <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p></div>
        </footer>
    <script defer src="../boostrap/js/bootstrap.bundle.min.js"></script>
  </body>