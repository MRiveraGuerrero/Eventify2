<?php
// Conexión a la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = mysqli_connect($hostname, $username, $password, $db);
if (!$conn) {
    die("Conexión a la base de datos fallida: " . mysqli_connect_error());
}

// Verifica si la solicitud es un POST y los datos están en formato JSON
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
    // Lee el contenido JSON de la solicitud
    $jsonData = file_get_contents("php://input");
    
    // Convierte el JSON a un array asociativo de PHP
    $data = json_decode($jsonData, true);
    
    // Verifica que los datos requeridos están presentes
    if (isset($data['usuarioSeguidor']) && isset($data['usuarioSeguido'])) {
        $usuarioSeguidor = $data['usuarioSeguidor'];
        $usuarioSeguido = $data['usuarioSeguido'];

        // Asegúrate de que los datos sean seguros para evitar inyecciones SQL
        $usuarioSeguidor = mysqli_real_escape_string($conn, $usuarioSeguidor);
        $usuarioSeguido = mysqli_real_escape_string($conn, $usuarioSeguido);

        // Verifica si el usuario ya sigue al otro
        $checkFollow = "SELECT * FROM follows WHERE usuarioSeguidor = '$usuarioSeguidor' AND usuarioSeguido = '$usuarioSeguido'";
        $follow_result = mysqli_query($conn, $checkFollow);

        if (mysqli_num_rows($follow_result) > 0) {
            // Si ya sigue, dejar de seguir (eliminar de la tabla 'follows')
            $deleteFollow = "DELETE FROM follows WHERE usuarioSeguidor = '$usuarioSeguidor' AND usuarioSeguido = '$usuarioSeguido'";
            if (mysqli_query($conn, $deleteFollow)) {
                echo json_encode(['status' => 'succes']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al dejar de seguir']);
            }
        } else {
            // Si no sigue, empezar a seguir (insertar en la tabla 'follows')
            $insertFollow = "INSERT INTO follows (usuarioSeguidor, usuarioSeguido) VALUES ('$usuarioSeguidor', '$usuarioSeguido')";
            if (mysqli_query($conn, $insertFollow)) {
                echo json_encode(['status' => 'succes']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al seguir']);
            }
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método o formato de solicitud incorrecto']);
}

// Cierra la conexión a la base de datos
mysqli_close($conn);
?>
