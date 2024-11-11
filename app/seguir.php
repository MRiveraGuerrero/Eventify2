<?php
session_start();
include("config.php"); // Conexión a la base de datos

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás logueado']);
    exit;
}

$usuarioSeguidor = $_SESSION['usuario']; // El usuario logueado
$usuarioSeguido = $_POST['usuarioSeguido']; // El usuario al que se quiere seguir o dejar de seguir

// Verificar si el usuario ya está siguiendo al otro
$query = "SELECT * FROM follows WHERE usuarioSeguidor = ? AND usuarioSeguido = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $usuarioSeguidor, $usuarioSeguido);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Si ya sigue al usuario, eliminar el seguimiento
    $queryDelete = "DELETE FROM follows WHERE usuarioSeguidor = ? AND usuarioSeguido = ?";
    $stmtDelete = $conn->prepare($queryDelete);
    $stmtDelete->bind_param("ss", $usuarioSeguidor, $usuarioSeguido);
    $stmtDelete->execute();
    $stmtDelete->close();
    echo json_encode(['status' => 'dejado']);
} else {
    // Si no lo sigue, agregar el seguimiento
    $queryInsert = "INSERT INTO follows (usuarioSeguidor, usuarioSeguido) VALUES (?, ?)";
    $stmtInsert = $conn->prepare($queryInsert);
    $stmtInsert->bind_param("ss", $usuarioSeguidor, $usuarioSeguido);
    $stmtInsert->execute();
    $stmtInsert->close();
    echo json_encode(['status' => 'seguido']);
}

$stmt->close();
$conn->close();
?>
