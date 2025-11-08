<?php
// Inicia la sesión
session_start();

// 1. Lógica de validación de sesión
if (!isset($_SESSION['s_usuario']) || empty($_SESSION['s_usuario'])) {
    header("Location: /Farmacia/logins/login_admin.php");
    exit();
}

// 2. Cabeceras HTTP para prevenir el caché del navegador
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// 3. Incluir conexión a DB
include_once "../logins/conexion.php";
$objeto = new Conexion();
$conexion = $objeto->conectar();

// 4. Obtener parámetros de fecha (hoy por defecto)
$fecha_seleccionada = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
$fecha_anterior = date('Y-m-d', strtotime($fecha_seleccionada . ' -1 day'));
$fecha_siguiente = date('Y-m-d', strtotime($fecha_seleccionada . ' +1 day'));

// 5. Obtener tipo de cambio USD (valor por defecto)
$tipo_cambio = isset($_GET['tipo_cambio']) ? floatval($_GET['tipo_cambio']) : 50.00;

// 6. CONSULTAS PRINCIPALES PARA EL DÍA SELECCIONADO

// Ventas pagadas del día
$sql_ventas_pagadas = "
    SELECT COUNT(*) as total_ventas, COALESCE(SUM(monto_unico), 0) as total_ingresos
    FROM pagos 
    WHERE DATE(fecha_pago) = :fecha 
    AND estado_pago = 'pagado'
";
$stmt_ventas = $conexion->prepare($sql_ventas_pagadas);
$stmt_ventas->bindParam(':fecha', $fecha_seleccionada);
$stmt_ventas->execute();
$ventas_pagadas = $stmt_ventas->fetch(PDO::FETCH_ASSOC);

// Pedidos apartados sin pagar
$sql_pedidos_apartados = "
    SELECT COUNT(*) as total_apartados
    FROM ventas 
    WHERE DATE(fecha_apartado) = :fecha 
    AND estado = 'apartado'
";
$stmt_apartados = $conexion->prepare($sql_pedidos_apartados);
$stmt_apartados->bindParam(':fecha', $fecha_seleccionada);
$stmt_apartados->execute();
$pedidos_apartados = $stmt_apartados->fetch(PDO::FETCH_ASSOC);

// Pagos confirmados
$sql_pagos_confirmados = "
    SELECT COUNT(*) as total_pagos
    FROM pagos 
    WHERE DATE(fecha_pago) = :fecha 
    AND estado_pago = 'pagado'
";
$stmt_pagos = $conexion->prepare($sql_pagos_confirmados);
$stmt_pagos->bindParam(':fecha', $fecha_seleccionada);
$stmt_pagos->execute();
$pagos_confirmados = $stmt_pagos->fetch(PDO::FETCH_ASSOC);

// Productos con stock bajo
$sql_stock_bajo = "
    SELECT nombre, cantidad, precio
    FROM productos 
    WHERE cantidad <= 5
    ORDER BY cantidad ASC
    LIMIT 10
";
$stmt_stock = $conexion->prepare($sql_stock_bajo);
$stmt_stock->execute();
$stock_bajo = $stmt_stock->fetchAll(PDO::FETCH_ASSOC);

// 7. Incluye la parte superior de tu página
require_once "view/resumen_dia.php";
?>

<!--INICIO DEL CONTENIDO PRINCIPAL-->
<div class="container-fluid">

    <!-- Encabezado y Filtros -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Resumen del Día</h1>
        <div class="d-flex">
            <!-- Selector de Fecha -->
            <form method="GET" class="mr-3">
                <div class="input-group">
                    <input type="date" name="fecha" class="form-control" 
                           value="<?php echo $fecha_seleccionada; ?>" 
                           max="<?php echo date('Y-m-d'); ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-calendar-day"></i> Ver Resumen
                        </button>
                    </div>
                </div>
                <input type="hidden" name="tipo_cambio" value="<?php echo $tipo_cambio; ?>">
            </form>
            
            <!-- Navegación entre días -->
            <div class="btn-group mr-3">
                <a href="?fecha=<?php echo $fecha_anterior; ?>&tipo_cambio=<?php echo $tipo_cambio; ?>" 
                   class="btn btn-outline-secondary">
                    <i class="fas fa-chevron-left"></i> Ayer
                </a>
                <a href="?fecha=<?php echo date('Y-m-d'); ?>&tipo_cambio=<?php echo $tipo_cambio; ?>" 
                   class="btn btn-outline-primary">Hoy</a>
                <?php if ($fecha_siguiente <= date('Y-m-d')): ?>
                <a href="?fecha=<?php echo $fecha_siguiente; ?>&tipo_cambio=<?php echo $tipo_cambio; ?>" 
                   class="btn btn-outline-secondary">
                    Mañana <i class="fas fa-chevron-right"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Configuración Tipo de Cambio -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Tipo de Cambio (USD)
                            </div>
                            <form method="GET" class="form-inline">
                                <input type="hidden" name="fecha" value="<?php echo $fecha_seleccionada; ?>">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">1 USD =</span>
                                    </div>
                                    <input type="number" step="0.01" name="tipo_cambio" 
                                           class="form-control" value="<?php echo $tipo_cambio; ?>"
                                           style="width: 120px;">
                                    <div class="input-group-append">
                                        <span class="input-group-text">BS</span>
                                    </div>
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-sync-alt"></i> Actualizar
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Métricas Principales -->
    <div class="row">
        
        <!-- Ventas Pagadas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Ventas Pagadas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $ventas_pagadas['total_ventas'] ?? 0; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pedidos Apartados -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Pedidos Apartados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $pedidos_apartados['total_apartados'] ?? 0; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagos Confirmados -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Pagos Confirmados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo $pagos_confirmados['total_pagos'] ?? 0; ?>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-credit-card fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Ingresos -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Ingresos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Bs. <?php echo number_format($ventas_pagadas['total_ingresos'] ?? 0, 2); ?>
                            </div>
                            <div class="text-xs text-muted">
                                $ <?php echo number_format(($ventas_pagadas['total_ingresos'] ?? 0) / $tipo_cambio, 2); ?> USD
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stock Bajo -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-danger">Stock Bajo (≤ 5 unidades)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Stock</th>
                                    <th>Precio BS</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($stock_bajo)): ?>
                                    <?php foreach ($stock_bajo as $producto): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $producto['cantidad'] == 0 ? 'danger' : 'warning'; ?>">
                                                <?php echo $producto['cantidad']; ?>
                                            </span>
                                        </td>
                                        <td>Bs. <?php echo number_format($producto['precio'], 2); ?></td>
                                        <td>
                                            <?php if ($producto['cantidad'] == 0): ?>
                                                <span class="badge badge-danger">Agotado</span>
                                            <?php elseif ($producto['cantidad'] <= 2): ?>
                                                <span class="badge badge-warning">Muy Bajo</span>
                                            <?php else: ?>
                                                <span class="badge badge-info">Bajo</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="4" class="text-center text-success">
                                            <i class="fas fa-check-circle"></i> Todo el stock está en niveles normales
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="../logins/jquery/jquery-3.3.1.min.js"></script>
<script src="../logins/bootstrap/js/bootstrap.min.js"></script>
<script src="../logins/popper/popper.min.js"></script>
<script src="../logins/Plugins/sweetalert2/sweetalert2.all.min.js"></script>

<!-- FIN DEL CONTENIDO PRINCIPAL-->
<?php require_once "view/parte_inferior.php"; ?>

<script>
    window.history.pushState(null, null, location.href);
    window.onpopstate = function() {
        window.history.go(1);
    };
</script>
<script src="offline_service/boostrap/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="offline_service/fontawesome-free/css/all.min.css">
</div>