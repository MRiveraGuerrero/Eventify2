<?php
session_start();
include("functionsJWT.php");

if (!isset($_SESSION['token']) || !isset($_POST['token']) || $_SESSION['token'] !== $_POST['token']) {
    http_response_code(403);
    echo json_encode(['error' => 'Token inválido']);
    exit;
}

if (!comprobarCookieUsuario()) {
    http_response_code(401);
    echo json_encode(['error' => 'No autorizado']);
    exit;
}

$usuario = getUsuarioCookie();

if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/profile_photos/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileExtension = strtolower(pathinfo($_FILES['profile_photo']['name'], PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
    
    if (!in_array($fileExtension, $allowedTypes)) {
        http_response_code(400);
        echo json_encode(['error' => 'Tipo de archivo no permitido']);
        exit;
    }

    $fileName = $usuario . '_' . time() . '.' . $fileExtension;
    $targetPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetPath)) {
        $hostname = "db";
        $username = "admin";
        $password = "test";
        $db = "database";

        $conn = mysqli_connect($hostname, $username, $password, $db);
        
        if ($conn->connect_error) {
            http_response_code(500);
            echo json_encode(['error' => 'Error de conexión a la base de datos']);
            exit;
        }

        $query = "UPDATE usuarios SET foto_perfil = ? WHERE usuario = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $targetPath, $usuario);
        
        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'photo_url' => $targetPath
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar la base de datos']);
        }

        $stmt->close();
        $conn->close();
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Error al subir el archivo']);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'No se recibió ninguna imagen']);
}
?>