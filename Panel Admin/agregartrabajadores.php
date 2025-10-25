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
require_once "view/añadir_trabajadores.php";
?>

<!--INICIO DEL CONTENIDO PRINCIPAL-->

<div class="container">

<!-- Mensaje de bienvenida al usuario que logeo-->
 <h3 class="text-center text-secondary">Registro de Nuevo Usuario</h3>


<div class="container">
    <form id="formRegistro" method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="rol">Selecciona el rol:</label>
                    <select id="rol" name="rol" class="form-control">
                        <option value="1">Administrador</option>
                        <option value="2">Usuario</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nombre">Nombre Completo:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Nombre completo">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="apellido">Apellidos:</label>
                    <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Apellido">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cedula">Cédula:</label>
                    <input type="text" id="cedula" name="cedula" class="form-control" placeholder="Cédula">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="telefono">Número de teléfono:</label>
                    <input type="text" id="telefono" name="telefono" class="form-control" placeholder="Número de teléfono">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="nombre_de_usuario">Nombre de Usuario:</label>
                    <input type="text" id="nombre_de_usuario" name="nombre_de_usuario" class="form-control" placeholder="Nombre de usuario">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="correo_electronico">Correo electrónico:</label>
                    <input type="email" id="correo_electronico" name="correo_electronico" class="form-control" placeholder="Correo electrónico">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" class="form-control" placeholder="Contraseña">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="confirmar_contraseña">Confirmar Contraseña:</label>
                    <input type="password" id="confirmar_contraseña" name="confirmar_contraseña" class="form-control" placeholder="Confirmar Contraseña">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <a href="index.php" class="btn btn-secondary btn-block">Atrás</a>
            </div>
            <div class="col-md-6">
                <button type="submit" value="Enviar" name="btnregistrar" class="btn btn-primary btn-block">Crear Cuenta</button>
            </div>
        </div>
    </form>
</div>
<script src="../logins/jquery/jquery-3.3.1.min.js"></script>
<script src="../logins/bootstrap/js/bootstrap.min.js"></script>
<script src="../logins/popper/popper.min.js"></script>
<script src="../logins/Plugins/sweetalert2/sweetalert2.all.min.js"></script>
<script src="controladores/codigo_usuario.js"></script>
<!-- FIN DEL CONTENIDO PRINCIPAL-->
<?php require_once "view/parte_inferior.php"?>
<script>
  // Esta línea evita que la página protegida se guarde en el historial del navegador
  window.history.pushState(null, null, location.href);
  window.onpopstate = function() {
    window.history.go(1);
  };
</script>


