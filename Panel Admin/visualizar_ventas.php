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

// 3. Incluye la parte superior de tu página
require_once "view/verventas.php";
?>

<!--INICIO DEL CONTENIDO PRINCIPAL-->
<div class="container">

<!-- BUSCADOR -->
    <div class="mb-3">
        <input type="search" 
               id="buscador_ventas" 
               class="form-control" 
               placeholder="Buscar ventas por ID, banco, referencia, fecha..."
               style="max-width: 400px;">
    </div>

    <h3 class="text-center text-secondary">Ventas Realizadas</h3>

    <?php
    require_once "../logins/logouts/conexion.php"; 
    $conexion = Conexion::Conectar();
    
    // ========== PAGINACIÓN ==========
    $ventas_por_pagina = 10;
    $pagina_actual = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
    if ($pagina_actual < 1) $pagina_actual = 1;

    // Calcular el offset
    $offset = ($pagina_actual - 1) * $ventas_por_pagina;

    // Obtener el total de ventas
    $sql_total = "SELECT COUNT(*) as total FROM pagos";
    $stmt_total = $conexion->prepare($sql_total);
    $stmt_total->execute();
    $total_ventas = $stmt_total->fetch(PDO::FETCH_ASSOC)['total'];

    // Calcular total de páginas
    $total_paginas = ceil($total_ventas / $ventas_por_pagina);

    // Query para obtener ventas con paginación
    $sql_conexion = $conexion->prepare("SELECT id_pago, id_venta, id_usuario, banco, metodo_pago, monto_unico, referencia, fecha_pago 
                                       FROM pagos 
                                       ORDER BY fecha_pago DESC 
                                       LIMIT :limit OFFSET :offset");
    
    $sql_conexion->bindValue(':limit', $ventas_por_pagina, PDO::PARAM_INT);
    $sql_conexion->bindValue(':offset', $offset, PDO::PARAM_INT);
    $sql_conexion->execute();
    ?>

    <!-- ========== INFORMACIÓN DE PAGINACIÓN ========== -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="text-muted">
            Mostrando <?php echo ($offset + 1); ?> - <?php echo min($offset + $ventas_por_pagina, $total_ventas); ?> de <?php echo $total_ventas; ?> ventas
        </div>
    </div>

    <table class="table table-bordered table-hover w-100 tabla-empleados" id="tabla_ventas">
        <thead class="thead-dark">
            <tr>
                <th scope="col">id pago</th>
                <th scope="col">id venta</th>
                <th scope="col">id_usuario</th>
                <th scope="col">Banco</th>
                <th scope="col">Metodo de pago</th>
                <th scope="col">Monto Total</th>
                <th scope="col">N° de Referencia</th>
                <th scope="col">Fecha de pago</th>
            </tr>
        </thead>
        <tbody id="tabla_ventas" class="users_table_body">
            <?php while ($sql = $sql_conexion->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $sql["id_pago"]?></td>
                    <td><?php echo $sql["id_venta"]; ?></td>
                    <td><?php echo $sql["id_usuario"]; ?></td>
                    <td><?php echo $sql["banco"]; ?></td>
                    <td><?php echo $sql["metodo_pago"]; ?></td>
                    <td><?php echo $sql["monto_unico"]; ?></td>
                    <td><?php echo $sql["referencia"]; ?></td>
                    <td><?php echo $sql["fecha_pago"]; ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <!-- ========== PAGINACIÓN ========== -->
    <?php if ($total_paginas > 1): ?>
    <nav aria-label="Paginación de ventas">
        <ul class="pagination justify-content-center">
            <!-- Botón Anterior -->
            <li class="page-item <?php echo $pagina_actual <= 1 ? 'disabled' : ''; ?>">
                <a class="page-link" href="?pagina=<?php echo $pagina_actual - 1; ?>" aria-label="Anterior">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>

            <!-- Números de página -->
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <?php 
                // Mostrar solo páginas cercanas a la actual para no tener una lista muy larga
                $mostrar_pagina = false;
                if ($total_paginas <= 10) {
                    $mostrar_pagina = true;
                } else {
                    // Mostrar primeras 3, últimas 3 y páginas alrededor de la actual
                    if ($i <= 3 || $i > $total_paginas - 3 || abs($i - $pagina_actual) <= 2) {
                        $mostrar_pagina = true;
                    }
                }
                ?>
                
                <?php if ($mostrar_pagina): ?>
                    <?php if ($i == $pagina_actual): ?>
                        <li class="page-item active">
                            <span class="page-link"><?php echo $i; ?></span>
                        </li>
                    <?php else: ?>
                        <li class="page-item">
                            <a class="page-link" href="?pagina=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endif; ?>
                <?php elseif ($i == 4 && $pagina_actual > 5): ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                <?php elseif ($i == $total_paginas - 3 && $pagina_actual < $total_paginas - 4): ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                <?php endif; ?>
            <?php endfor; ?>

            <!-- Botón Siguiente -->
            <li class="page-item <?php echo $pagina_actual >= $total_paginas ? 'disabled' : ''; ?>">
                <a class="page-link" href="?pagina=<?php echo $pagina_actual + 1; ?>" aria-label="Siguiente">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
    <?php endif; ?>
    <!-- ========== FIN PAGINACIÓN ========== -->

<script src="../logins/jquery/jquery-3.3.1.min.js"></script>
<script src="../logins/bootstrap/js/bootstrap.min.js"></script>
<script src="../logins/popper/popper.min.js"></script>
<script src="../logins/Plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="controladores/buscador.js"></script>

<script>
  $(document).ready(function () {
    aplicarBuscador("tabla_ventas", "buscador_ventas");
  });
</script>
<!-- FIN DEL CONTENIDO PRINCIPAL-->
<?php require_once "view/parte_inferior.php"?>
 <script>
        window.history.pushState(null, null, location.href);
        window.onpopstate = function() {
            window.history.go(1);
        };
    </script>
    <script src="offline_service/boostrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="offline_service/fontawesome-free/css/all.min.css">
</div>