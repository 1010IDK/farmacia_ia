<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once "../../logins/conexion.php";
$objeto = new Conexion();
$conexion = $objeto->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge los datos del formulario
    $rol = $_POST['rol'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $cedula = $_POST['cedula'] ?? '';
    $numero_de_telefono = $_POST['telefono'] ?? '';
    $nombre_de_usuario = $_POST['nombre_de_usuario'] ?? '';
    $correo_electronico = $_POST['correo_electronico'] ?? '';
    $contrasena = $_POST['contrasena'] ?? '';
    $confirmar_contraseña = $_POST['confirmar_contraseña'] ?? '';

    // Validación básica
    if (
        empty($rol) || empty($nombre) || empty($apellido) || empty($cedula) ||
        empty($numero_de_telefono) || empty($nombre_de_usuario) ||
        empty($correo_electronico) || empty($contrasena) || empty($confirmar_contraseña)
    ) {
        echo "error: Todos los campos son obligatorios.";
        exit;
    }

    if ($contrasena !== $confirmar_contraseña) {
        echo "error: Las contraseñas no coinciden.";
        exit;
    }

    // Verifica si el usuario ya existe
    $consulta = "SELECT * FROM admin_trabajadores WHERE nombre_de_usuario = :usuario";
    $resultado = $conexion->prepare($consulta);
    $resultado->bindParam(':usuario', $nombre_de_usuario);
    $resultado->execute();

    if ($resultado->rowCount() > 0) {
        echo "user_found";
        exit;
    }
    
    // --- NUEVO CÓDIGO AÑADIDO ---
    // Verifica si la cédula ya existe
    $consulta = "SELECT * FROM admin_trabajadores WHERE cedula = :cedula";
    $resultado = $conexion->prepare($consulta);
    $resultado->bindParam(':cedula', $cedula);
    $resultado->execute();

    if ($resultado->rowCount() > 0) {
        echo "cedula_found";
        exit;
    }

    // Verifica si el correo ya existe
    $consulta = "SELECT * FROM admin_trabajadores WHERE correo_electronico = :correo";
    $resultado = $conexion->prepare($consulta);
    $resultado->bindParam(':correo', $correo_electronico);
    $resultado->execute();

    if ($resultado->rowCount() > 0) {
        echo "correo_found";
        exit;
    }

    // Verifica si el teléfono ya existe
    $consulta = "SELECT * FROM admin_trabajadores WHERE numero_de_telefono = :telefono";
    $resultado = $conexion->prepare($consulta);
    $resultado->bindParam(':telefono', $numero_de_telefono);
    $resultado->execute();

    if ($resultado->rowCount() > 0) {
        echo "telefono_found";
        exit;
    }
    // --- FIN DEL CÓDIGO AÑADIDO ---

    // Hashea la contraseña
    $password_hashed = password_hash($contrasena, PASSWORD_DEFAULT);

    // Inserta el nuevo usuario
    $consulta = "INSERT INTO admin_trabajadores (id_rol, nombre, apellido, cedula, numero_de_telefono, nombre_de_usuario, correo_electronico, contraseña)
        VALUES (:id_rol, :nombre, :apellido, :cedula, :numero_de_telefono, :nombre_de_usuario, :correo_electronico, :contrasena)";
    $resultado = $conexion->prepare($consulta);

    $params = [
        'id_rol' => $rol,
        'nombre' => $nombre,
        'apellido' => $apellido,
        'cedula' => $cedula,
        'numero_de_telefono' => $numero_de_telefono,
        'nombre_de_usuario' => $nombre_de_usuario,
        'correo_electronico' => $correo_electronico,
        'contrasena' => $password_hashed
    ];

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if ($resultado->execute($params)) {
    echo "success";
} else {
    $errorInfo = $resultado->errorInfo();
    echo "error: " . $errorInfo[2];
}
}
?>