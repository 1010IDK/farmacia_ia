<?php
// Inicia la sesión
session_start();

// Validación de sesión
if (!isset($_SESSION['s_usuario']) || empty($_SESSION['s_usuario'])) {
    header("Location: /Farmacia/logins/login_admin.php");
    exit();
}

// Cabeceras HTTP para prevenir caché
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Incluir conexión a DB
require_once "../logins/conexion.php"; // Ajusta la ruta si es necesario
$objeto = new Conexion();
$conexion = $objeto->conectar();
if (!$conexion) {
    die("Error: No se pudo establecer la conexión a la base de datos. Revisa conexion.php.");
}

// Query para obtener productos con nombre de categoría
try {
    error_log("Intentando preparar la consulta SQL.", 0);
    $sql_conexion = $conexion->prepare("SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.cantidad, p.imagen, c.categoria AS categoria_nombre 
                                       FROM productos p 
                                       LEFT JOIN categorias c ON p.id_categoria = c.id_categoria 
                                       ORDER BY p.id_producto DESC");
    error_log("Consulta preparada con éxito.", 0);
    $sql_conexion->execute();
    error_log("Consulta ejecutada con éxito.", 0);
} catch (PDOException $e) {
    error_log("Error en la consulta (línea 18): " . $e->getMessage(), 0);
    die("Error en la consulta (línea 18): " . $e->getMessage());
}

// Incluye la parte superior de tu página
require_once "view/verproductos.php";
?>

<!--INICIO DEL CONTENIDO PRINCIPAL-->

<div class="container">
    <!-- Mensaje de bienvenida al usuario que logeo-->
    <h3 class="text-center text-secondary">Lista De Productos</h3>

    <table class="table table-bordered table-hover w-100" id="example">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nombre</th>
                <th scope="col">Descripción</th>
                <th scope="col">Precio</th>
                <th scope="col">Cantidad</th>
                <th scope="col">Categoría</th>
                <th scope="col">Imagen</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody id="tabla_productos" class="productos_table_body">
            <?php while ($sql = $sql_conexion->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($sql["id_producto"] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($sql["nombre"] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($sql["descripcion"] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($sql["precio"] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($sql["cantidad"] ?? ''); ?></td>
                    <td><?php echo htmlspecialchars($sql["categoria_nombre"] ?? 'Sin categoría'); ?></td>
                    <td>
                        <?php
                        $image_path = 'C:\\xampp\\htdocs\\Farmacia\\Panel Admin\\img\\' . basename($sql["imagen"]);
                        if (!empty($sql["imagen"]) && file_exists($image_path)) {
                            echo "<a href=\"/Farmacia/Panel Admin/img/" . htmlspecialchars(basename($sql['imagen'])) . "\" target=\"_blank\">Ver imagen</a>";
                        } else {
                            echo "Sin imagen";
                        }
                        ?>
                    </td>
                    <td>
                        <div style="display: inline-flex;">
                            <!-- BOTON MODIFICAR/VIEW PRODUCTO (azul como en empleados) -->
                            <a href="#"
                               class="btn btn-primary  mr-2 btn-modificar-producto"
                               data-toggle="modal"
                               data-target="#modalModificarProducto"
                               data-id-producto="<?php echo htmlspecialchars($sql["id_producto"]); ?>"
                               data-nombre="<?php echo htmlspecialchars($sql["nombre"] ?? ''); ?>"
                               data-descripcion="<?php echo htmlspecialchars($sql["descripcion"] ?? ''); ?>"
                               data-precio="<?php echo htmlspecialchars($sql["precio"] ?? ''); ?>"
                               data-cantidad="<?php echo htmlspecialchars($sql["cantidad"] ?? ''); ?>"
                               data-categoria="<?php echo htmlspecialchars($sql["id_categoria"] ?? ''); ?>"
                               data-imagen="<?php echo htmlspecialchars($sql["imagen"] ?? ''); ?>">
                                <i class="fas fa-pen"></i>
                            </a>
                            <!-- BOTON ELIMINAR PRODUCTO (rojo como en empleados) -->
                            <a href="#"
                               class="btn btn-danger mr-2 btn-eliminar-producto"
                               data-id-producto="<?php echo htmlspecialchars($sql["id_producto"]); ?>">
                                <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- Modal modificar productos -->
    <div class="modal fade" id="modalModificarProducto" tabindex="-1" aria-labelledby="modalModificarProductoLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title fs-5" id="modalModificarProductoLabel">Modificar Producto</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formModificarProducto" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="id_producto_modificar" name="id_producto">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre_modificar">Nombre:</label>
                                    <input type="text" id="nombre_modificar" name="nombre" class="form-control" placeholder="Nombre Del Producto">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="descripcion_modificar">Descripción:</label>
                                    <input type="text" id="descripcion_modificar" name="descripcion" class="form-control" placeholder="Descripción Del Producto">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="precio_modificar">Precio:</label>
                                    <input type="text" id="precio_modificar" name="precio" class="form-control" placeholder="Precio Del Producto">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cantidad_modificar">Cantidad:</label>
                                    <input type="number" id="cantidad_modificar" name="cantidad" class="form-control" placeholder="Cantidad Del Producto">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="categoria_modificar">Categoría:</label>
                                    <select id="categoria_modificar" name="categoria" class="form-control" required>
                                        <option value="">Seleccione una categoría</option>
                                        <?php
                                        $categorias_query = $conexion->query("SELECT id_categoria, categoria FROM categorias");
                                        while ($cat = $categorias_query->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value='" . htmlspecialchars($cat['id_categoria']) . "'>" . htmlspecialchars($cat['categoria']) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Imagen del producto:</label>
                                    <button type="button" id="selectImageModificar" class="btn btn-secondary">Seleccionar Imagen</button>
                                    <span id="fileNameModificar">Sin archivo seleccionado</span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="visualizar_productos.php" class="btn btn-secondary btn-block">Atrás</a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" value="Enviar" name="btn-modificar-producto" class="btn btn-primary btn-block">Modificar Producto</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal modificar productos -->
</div>

<script src="../logins/jquery/jquery-3.3.1.min.js"></script>
<script src="../logins/bootstrap/js/bootstrap.min.js"></script>
<script src="../logins/popper/popper.min.js"></script>
<script src="../logins/Plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="controladores/codigo_modificar_producto.js"></script>
<script src="controladores/eliminar_producto.js"></script>

<!-- FIN DEL CONTENIDO PRINCIPAL -->
<?php require_once "view/parte_inferior.php"; ?>

<script>
    // Esta línea evita que la página protegida se guarde en el historial del navegador
    window.history.pushState(null, null, location.href);
    window.onpopstate = function() {
        window.history.go(1);
    };
</script>
<script src="offline_service/boostrap/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="offline_service/fontawesome-free/css/all.min.css">