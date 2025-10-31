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

// Consulta la información completa del usuario
$id_usuario = $_SESSION['id_usuario'];
$sql_usuario = "SELECT u.*, tu.numero_tlf, du.direccion 
                FROM usuarios u 
                LEFT JOIN telefonos_usuarios tu ON u.id_usuario = tu.id_usuario 
                LEFT JOIN direcciones_usuarios du ON u.id_usuario = du.id_usuario 
                WHERE u.id_usuario = $id_usuario";
$resultado_usuario = mysqli_query($conexion, $sql_usuario);

if ($resultado_usuario && $usuario = mysqli_fetch_assoc($resultado_usuario)) {
    $nombre = $usuario['nombre'];
    $correo = $usuario['correo'];
    $telefono = $usuario['numero_tlf'] ?? 'No registrado';
    $direccion = $usuario['direccion'] ?? 'No registrada';
} else {
    $nombre = "Usuario";
    $correo = "No disponible";
    $telefono = "No registrado";
    $direccion = "No registrada";
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
    <link rel="icon" href="../diseno/icons/info-circle.svg" type="image/svg+xml">
    <style>
        .profile-container {
            background: #1B3C53;
            min-height: 100vh;
            padding: 2rem 0;
        }
        
        .profile-card {
            background: #F9F3EF;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: 1px solid #D2C1B6;
            overflow: hidden;
        }
        
        .profile-header {
            background: #456882;
            color: #F9F3EF;
            padding: 3rem 2rem;
            text-align: center;
            position: relative;
        }
        
        .profile-avatar {
            width: 120px;
            height: 120px;
            background: rgba(249, 243, 239, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            border: 4px solid #F9F3EF;
            font-size: 3rem;
            color: #F9F3EF;
        }
        
        .profile-body {
            padding: 3rem;
        }
        
        .info-section {
            margin-bottom: 2.5rem;
        }
        
        .info-title {
            color: #1B3C53;
            font-weight: 600;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #D2C1B6;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .info-item {
            display: flex;
            align-items: center;
            padding: 1rem 0;
            border-bottom: 1px solid #F9F3EF;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .info-icon {
            width: 40px;
            height: 40px;
            background: #456882;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: #F9F3EF;
        }
        
        .info-content {
            flex: 1;
        }
        
        .info-label {
            font-weight: 500;
            color: #456882;
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
        }
        
        .info-value {
            font-weight: 600;
            color: #1B3C53;
            font-size: 1.1rem;
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .btn-back {
            background: transparent;
            border: 2px solid #456882;
            color: #456882;
            padding: 0.75rem 2rem;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-back:hover {
            background: #456882;
            color: #F9F3EF;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(69, 104, 130, 0.3);
        }
        
        .text-success {
            color: #456882 !important;
        }
        
        .opacity-75 {
            opacity: 0.75;
        }
    </style>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Información Personal - Farmamigo IV</title>
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
                <li class="nav-item" id="carrito">
                    <a href="carrito.php" class="btn btn-outline-dark">
                        <i class="bi-cart-fill me-1 text-white"></i>
                        Cart
                        <span class="badge text-black ms-1 text-white">
                            <?php
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
                        <input class="form-control me-2" type="search" name="busqueda" placeholder="Buscar" aria-label="Search"/>
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

<!-- Contenido Principal -->
<div class="profile-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="profile-card">
                    <!-- Header del Perfil -->
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <i class="bi bi-person-circle"></i>
                        </div>
                        <h2 class="mb-2"><?php echo htmlspecialchars($nombre); ?></h2>
                        <p class="mb-0 opacity-75">Miembro de Farmamigo IV</p>
                    </div>
                    
                    <!-- Cuerpo del Perfil -->
                    <div class="profile-body">
                        <!-- Información Básica -->
                        <div class="info-section">
                            <h4 class="info-title">
                                <i class="bi bi-info-circle"></i>
                                Información Básica
                            </h4>
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Nombre Completo</div>
                                    <div class="info-value"><?php echo htmlspecialchars($nombre); ?></div>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Correo Electrónico</div>
                                    <div class="info-value"><?php echo htmlspecialchars($correo); ?></div>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Estado de la Cuenta</div>
                                    <div class="info-value text-success">
                                        <i class="bi bi-check-circle-fill"></i> Activa
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Información de Contacto -->
                        <div class="info-section">
                            <h4 class="info-title">
                                <i class="bi bi-telephone"></i>
                                Información de Contacto
                            </h4>
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-phone"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Teléfono</div>
                                    <div class="info-value"><?php echo htmlspecialchars($telefono); ?></div>
                                </div>
                            </div>
                            
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="info-content">
                                    <div class="info-label">Dirección</div>
                                    <div class="info-value"><?php echo htmlspecialchars($direccion); ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botón de Volver -->
                        <div class="action-buttons">
                            <a href="../index_logeado.php" class="btn btn-back">
                                <i class="bi bi-arrow-left me-2"></i>Volver al Inicio
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!--footer-->
<footer class="py-5" style="background-color: #151110;">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; Farmamigo IV 2024</p>
    </div>
</footer>

<script src="../boostrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>