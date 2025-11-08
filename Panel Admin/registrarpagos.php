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
require_once "view/modificar_pagos.php";
?>

<!--INICIO DEL CONTENIDO PRINCIPAL-->

<div class="container mt-5">
    <h3 class="text-center text-secondary mb-4">Registrar Pago</h3>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="form-group">
                <label for="codigo_pedido">Buscar Pedido por Código:</label>
                <input 
                    type="text" 
                    id="codigo_pedido" 
                    class="form-control" 
                    placeholder="Ej: FL4o0hWvuB" 
                    autocomplete="off"
                >
                <small class="text-muted">Ingresa el código del pedido apartado</small>
            </div>
        </div>
    </div>

    <!-- Aquí se mostrará el resultado (opcional) -->
    <div id="resultado_pedido" class="mt-4"></div>
</div>


<script src="../logins/jquery/jquery-3.3.1.min.js"></script>
<script src="../logins/bootstrap/js/bootstrap.min.js"></script>
<script src="../logins/popper/popper.min.js"></script>
<script src="../logins/Plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="controladores/buscar_pedido.js"></script>



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
<style>
    /* Para Chrome, Safari, Edge, Opera */
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button,
input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

/* Para Firefox */
input {
    -moz-appearance: textfield;
}

/* Para todos los navegadores modernos */
input {
    appearance: textfield;
}
</style>