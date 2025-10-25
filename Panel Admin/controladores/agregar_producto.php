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
    // Recoge los datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';
    $categoria = (int) ($_POST['categoria'] ?? 0); // Cast to int for safety

    // Validación básica: asegura que no haya campos vacíos
    if (empty($nombre) || empty($descripcion) || empty($precio) || empty($cantidad) || $categoria <= 0) {
        echo "error: Todos los campos son obligatorios, incluyendo una categoría válida.";
        exit;
    }

    // Manejo de la imagen (si se subió)
    $imagen_path = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === 0) {
        $upload_dir = '../img/'; // Ajusta si es necesario (relativo a esta carpeta)
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $file_tmp = $_FILES['imagen']['tmp_name'];
        $file_name = basename($_FILES['imagen']['name']);
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        // Validar tipo y tamaño (máx 5MB)
        if (in_array($file_ext, $allowed_ext) && $_FILES['imagen']['size'] <= 5000000) {
            $new_file_name = uniqid('prod_') . '.' . $file_ext;
            $imagen_path = 'img/' . $new_file_name;
            if (!move_uploaded_file($file_tmp, $upload_dir . $new_file_name)) {
                echo "error: Error al subir la imagen.";
                exit;
            }
        } else {
            echo "error: Archivo no válido o demasiado grande.";
            exit;
        }
    }

    // Inserta el producto en la base de datos (usando id_categoria)
    $consulta = "INSERT INTO productos (nombre, descripcion, precio, cantidad, id_categoria, imagen) 
                 VALUES (:nombre, :descripcion, :precio, :cantidad, :categoria, :imagen)";
    $resultado = $conexion->prepare($consulta);

    $params = [
        ':nombre' => $nombre,
        ':descripcion' => $descripcion,
        ':precio' => (float)$precio,
        ':cantidad' => (int)$cantidad,
        ':categoria' => $categoria,
        ':imagen' => $imagen_path
    ];

    try {
        if ($resultado->execute($params)) {
            echo "success";
        } else {
            $errorInfo = $resultado->errorInfo();
            echo "error: " . $errorInfo[2];
        }
    } catch (PDOException $e) {
        echo "error: " . $e->getMessage();
    }
}
?>