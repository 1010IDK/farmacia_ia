<?php
session_start();
include("../config/conexion.php");

if (isset($_POST['btn-registrar'])) {

    $nombre_completo = mysqli_real_escape_string($conexion, $_POST['registrar-nombre']);
    $correo          = mysqli_real_escape_string($conexion, $_POST['registrar-correo']);
    $ubicacion       = mysqli_real_escape_string($conexion, $_POST['ubicacion']);
    $telefono        = mysqli_real_escape_string($conexion, $_POST['registrar-telefono']);
    $contrasena      = mysqli_real_escape_string($conexion, $_POST['registrar-contrasena']);

    // Validaciones
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
            alert('Por favor, ingrese un email válido');
            location.assign('../vistas/login_register_user.php');
        </script>";
        exit();
    }

    if (empty($nombre_completo) || empty($correo) || empty($ubicacion) || empty($telefono) || empty($contrasena)) {
        echo "<script>
            alert('Todos los campos son obligatorios');
            location.assign('../vistas/login_register_user.php');
        </script>";
        exit();
    }

    if (strlen($contrasena) < 6) {
        echo "<script>
            alert('La contraseña debe tener al menos 6 caracteres');
            location.assign('../vistas/login_register_user.php');
        </script>";
        exit();
    }

    if (!is_numeric($telefono)) {
        echo "<script>
            alert('El teléfono debe contener solo números');
            location.assign('../vistas/login_register_user.php');
        </script>";
        exit();
    }

    // Verificar si el correo ya existe
    $sql_verificar = "SELECT id_usuario FROM usuarios WHERE correo = '$correo'";
    $resultado_verificar = mysqli_query($conexion, $sql_verificar);

    if (!$resultado_verificar) {
        echo "<script>
            alert('Error en la verificación: " . mysqli_error($conexion) . "');
            location.assign('../vistas/login_register_user.php');
        </script>";
        exit();
    }

    if (mysqli_num_rows($resultado_verificar) > 0) {
        echo "<script>
            alert('El correo electrónico ya está registrado');
            location.assign('../vistas/login_register_user.php');
        </script>";
        exit();
    }

    // Hash de contraseña
    $contrasena_hash = password_hash($contrasena, PASSWORD_DEFAULT);

    mysqli_autocommit($conexion, false);

    try {

        $sql_usuario = "
            INSERT INTO usuarios ( nombre, correo, contraseña_hash)
            VALUES ('$nombre_completo', '$correo', '$contrasena_hash')
        ";
        if (!mysqli_query($conexion, $sql_usuario)) {
            throw new Exception("Error al crear usuario: " . mysqli_error($conexion));
        }

        $id_usuario = mysqli_insert_id($conexion);

        $sql_direccion = "
            INSERT INTO direcciones_usuarios (id_usuario, direccion)
            VALUES ($id_usuario, '$ubicacion')
        ";
        if (!mysqli_query($conexion, $sql_direccion)) {
            throw new Exception("Error al guardar ubicación: " . mysqli_error($conexion));
        }

        $sql_telefono = "
            INSERT INTO telefonos_usuarios (id_usuario, numero_tlf)
            VALUES ($id_usuario, '$telefono')
        ";
        if (!mysqli_query($conexion, $sql_telefono)) {
            throw new Exception("Error al guardar teléfono: " . mysqli_error($conexion));
        }

        mysqli_commit($conexion);
        mysqli_autocommit($conexion, true);

        // Registro exitoso
        echo "<script>
            alert('¡Registro exitoso! Ahora puedes iniciar sesión');
            location.assign('../vistas/login_register_user.php');
        </script>";
        exit();

    } catch (Exception $e) {
        mysqli_rollback($conexion);
        mysqli_autocommit($conexion, true);

        echo "<script>
            alert('Error en el registro: " . $e->getMessage() . "');
            location.assign('../vistas/login_register_user.php');
        </script>";
        exit();
    }

} else {
    header("Location: ../vistas/login_register_user.php");
    exit();
}