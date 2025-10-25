<?php
// indicamos a PHP que no muestre errores ni advertencias. 
error_reporting(0);
session_start();
include_once "conexion.php";
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : '';
$password = (isset($_POST['password'])) ? $_POST['password'] : '';

// Verificamos si el usuario existe
$consulta = "SELECT nombre, apellido, contraseña FROM admin_trabajadores WHERE nombre_de_usuario = '$usuario'";
$resultado = $conexion->prepare($consulta);
$resultado->execute();
$data = $resultado->fetchAll(PDO::FETCH_ASSOC);

if (empty($data)) {
    echo 'user_not_found';
} else {
    $password_bd = $data[0]['contraseña'];
    
    if (password_verify($password, $password_bd)) {
    // La contraseña es correcta, guardamos los datos del usuario en la sesión
        $_SESSION["s_usuario"] = $usuario;
        $_SESSION["s_nombre"] = $data[0]['nombre'];
        $_SESSION["s_apellido"] = $data[0]['apellido'];
        echo 'success';
    } else {
        echo 'incorrect_password';
    }
     //$password= contraseña escrita por el usuario, $password_bd= La contraseña encriptada que se almaceno en la base de datos

 // Usamos password_verify() para comparar Esta función hace el proceso inverso internamente. En lugar de descifrar el hash (lo cual es imposible), toma la contraseña en texto plano, la encripta con el mismo algoritmo y compara si el nuevo hash coincide con el que ya tienes guardado en la base de datos. 
}
$conexion = null;
?>
