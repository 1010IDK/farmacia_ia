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
    $id_producto = $_POST['id_producto'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';
    $categoria = $_POST['categoria'] ?? '';

    // Validate required fields
    if (empty($id_producto) || empty($nombre) || empty($descripcion) || empty($precio) || empty($cantidad) || empty($categoria)) {
        echo "error: Todos los campos son obligatorios.";
        exit;
    }

    // Prepare existing image path
    $consulta_exist = "SELECT imagen FROM productos WHERE id_producto = :id_producto";
    $res_exist = $conexion->prepare($consulta_exist);
    $res_exist->bindParam(':id_producto', $id_producto);
    $res_exist->execute();
    $existing_imagen = $res_exist->fetchColumn() ?: '';

    $imagen_path = $existing_imagen;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = 'C:\\xampp\\htdocs\\Farmacia\\Panel Admin\\img\\';
        if (!is_dir($upload_dir)) {
            if (!mkdir($upload_dir, 0755, true)) {
                echo "error: No se pudo crear el directorio de imágenes.";
                exit;
            }
        }

        $file_tmp = $_FILES['imagen']['tmp_name'];
        $file_name = basename($_FILES['imagen']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($file_ext, $allowed_ext)) {
            echo "error: Extensión de archivo no permitida.";
            exit;
        }

        if ($_FILES['imagen']['size'] > 5000000) {
            echo "error: El archivo es demasiado grande (máximo 5MB).";
            exit;
        }

        $new_file_name = uniqid('prod_') . '.' . $file_ext;
        $imagen_path = 'img/' . $new_file_name; // Relative path for DB
        $full_path = $upload_dir . $new_file_name;

        if (move_uploaded_file($file_tmp, $full_path)) {
            if ($existing_imagen && file_exists('C:\\xampp\\htdocs\\Farmacia\\Panel Admin\\img\\' . basename($existing_imagen))) {
                unlink('C:\\xampp\\htdocs\\Farmacia\\Panel Admin\\img\\' . basename($existing_imagen));
            }
        } else {
            echo "error: Error al mover el archivo subido.";
            exit;
        }
    } elseif (isset($_FILES['imagen']) && $_FILES['imagen']['error'] !== UPLOAD_ERR_NO_FILE) {
        echo "error: Error en la subida del archivo: " . $_FILES['imagen']['error'];
        exit;
    }

    // Update product
    $consulta = "UPDATE productos SET 
        nombre = :nombre, 
        descripcion = :descripcion, 
        precio = :precio, 
        cantidad = :cantidad, 
        id_categoria = :categoria, 
        imagen = :imagen
    WHERE id_producto = :id_producto";
    
    $resultado = $conexion->prepare($consulta);

    $params = [
        ':id_producto' => $id_producto,
        ':nombre' => $nombre,
        ':descripcion' => $descripcion,
        ':precio' => (float)$precio,
        ':cantidad' => (int)$cantidad,
        ':categoria' => (int)$categoria, // Changed to id_categoria to match DB schema
        ':imagen' => $imagen_path
    ];

    try {
        if ($resultado->execute($params)) {
            echo "success";
        } else {
            $errorInfo = $resultado->errorInfo();
            echo "error: Fallo en la actualización: " . ($errorInfo[2] ?: 'Desconocido');
        }
    } catch (PDOException $e) {
        error_log("PDO Error: " . $e->getMessage());
        echo "error: Error de base de datos: " . $e->getMessage();
    }
}
?>