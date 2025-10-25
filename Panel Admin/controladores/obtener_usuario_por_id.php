<?php
// Asegura que se reporten todos los errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inicia la sesión si aún no se ha iniciado
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Incluye el archivo de conexión a la base de datos
include_once "../../logins/conexion.php";
$objeto = new Conexion();
$conexion = $objeto->conectar();

// Verifica que la solicitud sea de tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recoge el ID del usuario enviado
    $id_trabajador = $_POST['id_trabajador'] ?? '';

    // Si el ID está vacío, devuelve un error en formato JSON
    if (empty($id_trabajador)) {
        echo json_encode(['error' => 'ID de trabajador no proporcionado.']);
        exit;
    }

    // Consulta para obtener los datos del usuario por su ID
    $consulta = "SELECT * FROM admin_trabajadores WHERE id_trabajador = :id_trabajador";
    $resultado = $conexion->prepare($consulta);
    $resultado->bindParam(':id_trabajador', $id_trabajador, PDO::PARAM_INT);
    $resultado->execute();

    // Obtiene el resultado como un array asociativo
    $datos_usuario = $resultado->fetch(PDO::FETCH_ASSOC);

    // Verifica si se encontró el usuario y lo devuelve en formato JSON
    if ($datos_usuario) {
        echo json_encode($datos_usuario);
    } else {
        echo json_encode(['error' => 'Usuario no encontrado.']);
    }
} else {
    // Devuelve un error si el método no es POST
    echo json_encode(['error' => 'Método de solicitud no permitido.']);
}
?>