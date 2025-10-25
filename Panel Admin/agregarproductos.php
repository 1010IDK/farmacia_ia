<?php
// Inicia la sesión
session_start();

// 1. Lógica de validación de sesión
// Si no hay una sesión de usuario, redirige inmediatamente al login.
if (!isset($_SESSION['s_usuario']) || empty($_SESSION['s_usuario'])) {
    header("Location: /Farmacia/logins/login_admin.php");
    exit();
}

// 2. Cabeceras HTTP para prevenir el caché del navegador
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Incluir conexión a DB para el select dinámico
include_once "../logins/conexion.php"; // Ruta ajustada basada en tu estructura
$objeto = new Conexion();
$conexion = $objeto->conectar();

// Query para obtener categorías
$consulta_categorias = "SELECT * FROM categorias ORDER BY id_categoria ASC";
$resultado_categorias = $conexion->prepare($consulta_categorias);
$resultado_categorias->execute();

// 3. Incluye la parte superior de tu página
require_once "view/añadir_productos.php";
?>

<!--INICIO DEL CONTENIDO PRINCIPAL-->

<div class="container">

<!-- Mensaje de bienvenida al usuario que logeo-->
<h3 class="text-center text-secondary">Registro de Nuevo Producto</h3>

<div class="container">
    <form id="formRegistroProducto" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nombre">Nombre del Producto:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre del producto">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="descripcion">Descripción:</label>
                    <input type="text" id="descripcion" name="descripcion" class="form-control" placeholder="Descripción del producto">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="precio">Precio:</label>
                    <input type="text" id="precio" name="precio" class="form-control" placeholder="Precio del producto">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cantidad">Cantidad:</label>
                    <input type="number" id="cantidad" name="cantidad" class="form-control" placeholder="Cantidad del producto">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="categoria">Selecciona la categoria:</label>
                    <select id="categoria" name="categoria" class="form-control">
                        <?php while ($row = $resultado_categorias->fetch(PDO::FETCH_ASSOC)) { ?>
                            <option value="<?php echo $row['id_categoria']; ?>"><?php echo $row['categoria']; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Imagen del producto:</label>
                    <button type="button" id="selectImage" class="btn btn-secondary">Seleccionar Imagen</button>
                    <span id="fileName">Sin archivo seleccionado</span>
                </div>
            </div>
        </div>
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Registrar Producto</button>
        </div>
    </form>
</div>

<script src="../logins/jquery/jquery-3.3.1.min.js"></script>
<script src="../logins/bootstrap/js/bootstrap.min.js"></script>
<script src="../logins/popper/popper.min.js"></script>
<script src="../logins/Plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="controladores/codigo_registro_producto.js"></script>
<!-- FIN DEL CONTENIDO PRINCIPAL-->
<?php require_once "view/parte_inferior.php"?>