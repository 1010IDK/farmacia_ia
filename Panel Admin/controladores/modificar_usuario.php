<?php
// Asegura que se reporten todos los errores para depuración
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
    // Recoge los datos del formulario de modificación
    $id_trabajador = $_POST['id_trabajador'] ?? '';
    $rol = $_POST['rol'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $cedula = $_POST['cedula'] ?? '';
    $numero_de_telefono = $_POST['telefono'] ?? '';
    $nombre_de_usuario = $_POST['nombre_de_usuario'] ?? '';
    $correo_electronico = $_POST['correo_electronico'] ?? '';

    // Validación básica: asegura que no haya campos vacíos
    if (
        empty($id_trabajador) || empty($rol) || empty($nombre) || empty($apellido) || empty($cedula) ||
        empty($numero_de_telefono) || empty($nombre_de_usuario) || empty($correo_electronico)
    ) {
        echo "error: Todos los campos son obligatorios.";
        exit;
    }
    
    // --- VERIFICACIONES DE DUPLICADOS (Adaptadas para la modificación) ---

    // Verifica si el nombre de usuario ya existe en otro usuario
    $consulta = "SELECT * FROM admin_trabajadores WHERE nombre_de_usuario = :nombre_de_usuario AND id_trabajador != :id_trabajador";
    $resultado = $conexion->prepare($consulta);
    $resultado->bindParam(':nombre_de_usuario', $nombre_de_usuario);
    $resultado->bindParam(':id_trabajador', $id_trabajador);
    $resultado->execute();
    if ($resultado->rowCount() > 0) {
        echo "error: El nombre de usuario ya está en uso.";
        exit;
    }
    
    // Verifica si la cédula ya existe en otro usuario
    $consulta = "SELECT * FROM admin_trabajadores WHERE cedula = :cedula AND id_trabajador != :id_trabajador";
    $resultado = $conexion->prepare($consulta);
    $resultado->bindParam(':cedula', $cedula);
    $resultado->bindParam(':id_trabajador', $id_trabajador);
    $resultado->execute();
    if ($resultado->rowCount() > 0) {
        echo "error: La cédula ya está registrada.";
        exit;
    }
    
    // Verifica si el correo ya existe en otro usuario
    $consulta = "SELECT * FROM admin_trabajadores WHERE correo_electronico = :correo_electronico AND id_trabajador != :id_trabajador";
    $resultado = $conexion->prepare($consulta);
    $resultado->bindParam(':correo_electronico', $correo_electronico);
    $resultado->bindParam(':id_trabajador', $id_trabajador);
    $resultado->execute();
    if ($resultado->rowCount() > 0) {
        echo "error: El correo electrónico ya está registrado.";
        exit;
    }
    
    // Verifica si el teléfono ya existe en otro usuario
    $consulta = "SELECT * FROM admin_trabajadores WHERE numero_de_telefono = :numero_de_telefono AND id_trabajador != :id_trabajador";
    $resultado = $conexion->prepare($consulta);
    $resultado->bindParam(':numero_de_telefono', $numero_de_telefono);
    $resultado->bindParam(':id_trabajador', $id_trabajador);
    $resultado->execute();
    if ($resultado->rowCount() > 0) {
        echo "error: El número de teléfono ya está registrado.";
        exit;
    }

    // Actualiza el usuario
    $consulta = "UPDATE admin_trabajadores SET 
        id_rol = :id_rol, 
        nombre = :nombre, 
        apellido = :apellido, 
        cedula = :cedula, 
        numero_de_telefono = :numero_de_telefono, 
        nombre_de_usuario = :nombre_de_usuario, 
        correo_electronico = :correo_electronico
    WHERE id_trabajador = :id_trabajador";
    
    $resultado = $conexion->prepare($consulta);

    $params = [
        'id_trabajador' => $id_trabajador,
        'id_rol' => $rol,
        'nombre' => $nombre,
        'apellido' => $apellido,
        'cedula' => $cedula,
        'numero_de_telefono' => $numero_de_telefono,
        'nombre_de_usuario' => $nombre_de_usuario,
        'correo_electronico' => $correo_electronico
    ];

    if ($resultado->execute($params)) {
        echo "success";
    } else {
        $errorInfo = $resultado->errorInfo();
        echo "error: " . $errorInfo[2];
    }
}
?>