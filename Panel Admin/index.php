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
require_once "view/parte_superior.php";
?>

<!--INICIO DEL CONTENIDO PRINCIPAL-->
<div class="container">
    <!-- Mensaje de bienvenida al usuario que logeo-->
    <h3 class="text-center text-secondary">Lista De Empleados</h3>

    <?php
    require_once "../logins/logouts/conexion.php"; 

    $conexion = Conexion::Conectar(); // Llama a la función estática para obtener la conexión
    $sql_conexion = $conexion->query("SELECT id_trabajador, id_rol, nombre, apellido, cedula, nombre_de_usuario, numero_de_telefono, correo_electronico FROM admin_trabajadores");
    ?>

    <table class="table table-bordered table-hover w-100" id="example">
        <thead>
            <tr>
                <th scope="col">Rol</th>
                <th scope="col">Nombre</th>
                <th scope="col">Apellido</th>
                <th scope="col">Cedula De Identidad</th>
                <th scope="col">Nombre de usuario</th>
                <th scope="col">Numero de Telefono</th>
                <th scope="col">correo electronico</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="tabla_empleados" class="users_table_body">
            <?php while ($sql = $sql_conexion->fetch(PDO::FETCH_ASSOC)) { ?>
                <tr>
                    <td><?php echo $sql["id_rol"]?></td>
                    <td><?php echo $sql["nombre"]; ?></td>
                    <td><?php echo $sql["apellido"]; ?></td>
                    <td><?php echo $sql["cedula"]; ?></td>
                    <td><?php echo $sql["nombre_de_usuario"]; ?></td>
                    <td><?php echo $sql["numero_de_telefono"]; ?></td>
                    <td><?php echo $sql["correo_electronico"]; ?></td>
                    <td>
                        <div style="display: inline-flex;">
                            <!----|----------------- BOTON MODIFICAR USUARIO ------------------->
                        <a
                                href="#"
                                class="btn btn-warning mr-2 btn-modificar-usuario"
                                data-toggle="modal"
                                data-target="#modalModificarUsuario"
                                data-id-trabajador="<?php echo $sql["id_trabajador"]; ?>"
                            >
                                <i class="fa-solid fa-user-pen"></i>
                            </a>
                            <!----|----------------- BOTON MODIFICAR USUARIO ------------------->


                            <!----|----------------- BOTON ELIMINAR USUARIO ------------------->
                            <a href="#" class="btn btn-danger btn mr-2 btn-eliminar-usuario" data-id-trabajador="<?php echo $sql["id_trabajador"]; ?>">
                                <i class="fa-solid fa-user-xmark"></i>
                            </a>
                            <!----|----------------- BOTON ELIMINAR USUARIO ------------------->

                        </div>
                    </td>
                </tr>

                <!-- Modal modificar trabajadores -->
                <div class="modal fade" id="modalModificarUsuario" tabindex="-1" aria-labelledby="modalModificarUsuarioLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title fs-5" id="modalModificarUsuarioLabel">Modificar Empleado</h3>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="formModificarUsuario" method="POST">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="rol_modificar">Selecciona el rol:</label>
                                                <select id="rol_modificar" name="rol" class="form-control">
                                                    <option value="1">Administrador</option>
                                                    <option value="2">Usuario</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombre_modificar">Nombre Completo:</label>
                                                <input type="text" id="nombre_modificar" name="nombre" class="form-control" placeholder="Nombre completo">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="apellido_modificar">Apellidos:</label>
                                                <input type="text" id="apellido_modificar" name="apellido" class="form-control" placeholder="Apellido">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="cedula_modificar">Cédula:</label>
                                                <input type="text" id="cedula_modificar" name="cedula" class="form-control" placeholder="Cédula">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="telefono_modificar">Número de teléfono:</label>
                                                <input type="text" id="telefono_modificar" name="telefono" class="form-control" placeholder="Número de teléfono">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="nombre_de_usuario_modificar">Nombre de Usuario:</label>
                                                <input type="text" id="nombre_de_usuario_modificar" name="nombre_de_usuario" class="form-control" placeholder="Nombre de usuario">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="correo_electronico_modificar">Correo electrónico:</label>
                                                <input type="email" id="correo_electronico_modificar" name="correo_electronico" class="form-control" placeholder="Correo electrónico">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <a href="index.php" class="btn btn-secondary btn-block">Atrás</a>
                                        </div>
                                        <div class="col-md-6">
                                            <button type="submit" value="Enviar" name="btn-modificar-usuario" class="btn btn-primary btn-block">Modificar Usuario</button>
                                        </div>
                                    </div>
                                    <input type="hidden" id="id_trabajador_modificar" name="id_trabajador">
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!--Modal modificar Trabajadores-->
            <?php } ?>
        </tbody>
    </table>

    <!--el link de jquery en la laptop tiene que ir siosi para que la ventanita de logout funcione pero
        en la computadora principal no tiene que ir el jquery, ya que no le permite que aparezca la ventanita de logout   link jquery:  <script src="../logins/jquery/jquery-3.3.1.min.js"></script> -->

    <script src="../logins/jquery/jquery-3.3.1.min.js"></script>
    <script src="../logins/bootstrap/js/bootstrap.min.js"></script>
    <script src="../logins/popper/popper.min.js"></script>
    <script src="../logins/Plugins/sweetalert2/sweetalert2.all.min.js"></script>
    <script src="controladores/codigo_modificar_usuario.js"></script>
    <script src="controladores/eliminar_usuario.js"></script>
    <!-- FIN DEL CONTENIDO PRINCIPAL-->
    <?php require_once "view/parte_inferior.php"?>
    <script>
        // Esta línea evita que la página protegida se guarde en el historial del navegador
        window.history.pushState(null, null, location.href);
        window.onpopstate = function() {
            window.history.go(1);
        };
    </script>
    <!--para que el modal de modificar trabajadores funcione sin internet-->
    <script src="offline_service/boostrap/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="offline_service/fontawesome-free/css/all.min.css">

    <!--la carpeta offline_service tiene como proposito que los modales y los logitos
    de modificar o eliminar o desactivar usuarios, productos etc, funcione con o sin internet-->
</div>