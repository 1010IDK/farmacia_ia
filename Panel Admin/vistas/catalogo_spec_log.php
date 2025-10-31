<?php
include("../config/conexion.php");
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: 0");

// Verifica si el usuario está logueado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login_register_user.php");
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
    <link rel="stylesheet" href="../diseno/css/nav_log.css">
    <link rel="stylesheet" href="../diseno/css/catalogo.css">
    <link rel="icon" href="../diseno/icons/catalogo.svg" type="image/svg+xml">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo de productos</title>
</head>
<body style="background-color: #1B3C53;">  
<!--navbar-->
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
<!--footer-->
<footer class="py-5" style="background-color: #151110;">
  <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p></div>
</footer>
<script src="../boostrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>