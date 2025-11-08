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
require_once "view/verpedidos.php";
?>

<!--INICIO DEL CONTENIDO PRINCIPAL-->
<div class="container">

<!-- BUSCADOR -->
    <div class="mb-3">
        <input type="search" 
               id="buscador_pedidos" 
               class="form-control" 
               placeholder="Buscar pedidos por código, cliente, fecha..."
               style="max-width: 400px;">
    </div>

<!-- Tu tabla -->
<table class="table table-bordered table-hover" id="tabla_pedidos">
    <!-- ... -->
</table>
    <h3 class="text-center text-secondary">Pedidos Disponibles</h3>

    <?php
    require_once "../logins/logouts/conexion.php"; 
    $conexion = Conexion::Conectar();
    $sql_conexion = $conexion->query("SELECT id_venta, id_usuario, codigo_unico, estado, fecha_apartado FROM ventas");
    ?>
         <table class="table table-bordered table-hover w-100 " id="tabla_pedidos">
    <thead class="thead-dark">
        <tr>
            <th scope="col">id_venta</th>
            <th scope="col">id_usuario</th>
            <th scope="col">Código del Pedido</th>
            <th scope="col">Estado del Pedido</th>
            <th scope="col">Fecha de apartado</th>
            <th scope="col">Acción</th>
        </tr>
    </thead>
    <tbody id="tabla_pedidos" class="users_table_body">
        <?php 
        $sql_conexion = $conexion->query("
            SELECT id_venta, id_usuario, codigo_unico, estado, fecha_apartado 
            FROM ventas 
            WHERE estado != 'pagado'
            ORDER BY fecha_apartado DESC
        ");
        while ($sql = $sql_conexion->fetch(PDO::FETCH_ASSOC)) { ?>
            <tr>
                <td><?php echo $sql["id_venta"]; ?></td>
                <td><?php echo $sql["id_usuario"]; ?></td>
                <td><?php echo $sql["codigo_unico"]; ?></td>
                <td>
                    <span class="badge badge-<?php echo $sql['estado'] == 'pendiente' ? 'warning' : 'info'; ?>">
                        <?php echo ucfirst($sql["estado"]); ?>
                    </span>
                </td>
                <td><?php echo date('d/m/Y H:i', strtotime($sql["fecha_apartado"])); ?></td>
                <td>
                    <?php if ($sql["estado"] != 'pagado'): ?>
                        <button class="btn btn-danger btn-sm cancelar-pedido" 
                                data-id="<?php echo $sql['id_venta']; ?>">
                            <i class="fas fa-times"></i> Cancelar pedido
                        </button>
                    <?php else: ?>
                        <span class="text-success"><i class="fas fa-check"></i> Pagado</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>



<script src="../logins/jquery/jquery-3.3.1.min.js"></script>
<script src="../logins/bootstrap/js/bootstrap.min.js"></script>
<script src="../logins/popper/popper.min.js"></script>
<script src="../logins/Plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="controladores/buscador.js"></script>

<script>
  $(document).ready(function () {
    aplicarBuscador("tabla_pedidos", "buscador_pedidos");
  });
</script>

<script>
$(document).ready(function() {
    console.log("jQuery cargado correctamente");
    
    // Prueba: ¿se detecta el botón?
    if ($('.cancelar-pedido').length > 0) {
        console.log("Botón cancelar-pedido encontrado:", $('.cancelar-pedido').length);
    } else {
        console.log("ERROR: No se encontró el botón .cancelar-pedido");
    }

    // Evento con delegación (FUNCIONA SIEMPRE)
    $(document).on('click', '.cancelar-pedido', function(e) {
        e.preventDefault();
        console.log("CLICK DETECTADO en botón cancelar-pedido");

        const id_venta = $(this).data('id');
        const fila = $(this).closest('tr');

        if (!id_venta) {
            Swal.fire('Error', 'ID de venta no encontrado', 'error');
            return;
        }

        Swal.fire({
            title: '¿Cancelar pedido?',
            text: 'Esta acción no se puede deshacer',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, cancelar',
            cancelButtonText: 'No'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'controladores/eliminar_pedido.php',
                    type: 'POST',
                    data: { id_venta: id_venta },
                    beforeSend: function() {
                        Swal.fire({
                            title: 'Eliminando...',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });
                    },
                    success: function(response) {
                        console.log("Respuesta del servidor:", response);
                        const res = response.trim();

                        if (res === 'success') {
                            Swal.fire('¡Cancelado!', 'Pedido eliminado', 'success')
                                .then(() => fila.fadeOut(400, function() { $(this).remove(); }));
                        } else {
                            const msg = res.replace('error:', '').trim();
                            Swal.fire('Error', msg, 'error');
                        }
                    },
                    error: function(xhr) {
                        console.error("Error AJAX:", xhr);
                        Swal.fire('Error', 'No se pudo conectar al servidor', 'error');
                    }
                });
            }
        });
    });
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
