<?php
// Iniciar sesión al principio del script
session_start();

// Incluir la conexión a la base de datos
include("../config/conexion.php");

// Verificar si se ha enviado el formulario
if (isset($_POST['btningresar'])) {
    
    // Sanitizar datos de entrada
    $usuario = mysqli_real_escape_string($conexion, $_POST['email']);
    $contrasena = $_POST['password']; // NO sanitizar para password_verify
    
    // Validar que el email tenga formato válido
    if (!filter_var($usuario, FILTER_VALIDATE_EMAIL)) {
        echo "<script>
            alert('Por favor, ingrese un email válido');
            location.assign('../vistas/login_register_user.php');
        </script>";
        mysqli_close($conexion);
        exit();
    }
    
    // CONSULTA
    $sql = "SELECT id_usuario, correo, contraseña_hash FROM usuarios WHERE correo = '$usuario'";
    $resultado = mysqli_query($conexion, $sql);
    
    if ($resultado && mysqli_num_rows($resultado) == 1) {
        $fila = mysqli_fetch_assoc($resultado);
        
        // VERIFICACIÓN CORREGIDA: usar password_verify
        if (password_verify($contrasena, $fila['contraseña_hash'])) {
            
            // Establecer variables de sesión
            $_SESSION['loggedin'] = true;
            $_SESSION['id_usuario'] = $fila['id_usuario'];
            $_SESSION['correo'] = $fila['correo'];
            $_SESSION['inicio_sesion'] = time();
            
            mysqli_close($conexion);
            
            // REDIRECCIÓN EXITOSA
            echo "<script>window.location.href = '../index_logeado.php';</script>";
            exit();
            
        } else {
            mysqli_close($conexion);
            echo "<script>
                alert('Contraseña incorrecta. Por favor, verifique sus datos.');
                location.assign('../vistas/login_register_user.php');
            </script>";
            exit();
        }
        
    } else {
        mysqli_close($conexion);
        echo "<script>
            alert('Usuario no encontrado. Verifique su email o regístrese.');
            location.assign('../vistas/login_register_user.php');
        </script>";
        exit();
    }
    
} else {
    // Si no es POST, redirigir a la vista
    mysqli_close($conexion);
    header('Location: ../vistas/login_register_user.php');
    exit();
}
?>