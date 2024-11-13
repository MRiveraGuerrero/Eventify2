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
    if (isset($data['usuarioBloqueador']) && isset($data['usuarioBloqueado'])) {
        $usuarioBloqueador = $data['usuarioBloqueador'];
        $usuarioBloqueado = $data['usuarioBloqueado'];

        // Asegúrate de que los datos sean seguros para evitar inyecciones SQL
        $usuarioBloqueador = mysqli_real_escape_string($conn, $usuarioBloqueador);
        $usuarioBloqueado = mysqli_real_escape_string($conn, $usuarioBloqueado);

        // Verifica si el usuario ya sigue al otro
        $checkBlock = "SELECT * FROM block WHERE usuarioBloqueador = '$usuarioBloqueador' AND usuarioBloqueado = '$usuarioBloqueado'";
        $block_result = mysqli_query($conn, $checkBlock);

        if (mysqli_num_rows($block_result) > 0) {
            // Si ya sigue, dejar de seguir (eliminar de la tabla 'follows')
            $unlock = "DELETE FROM block WHERE usuarioBloqueador = '$usuarioBloqueador' AND usuarioBloqueado = '$usuarioBloqueado'";
            if (mysqli_query($conn, $unlock)) {
                echo json_encode(['status' => 'succes']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error al dejar de seguir']);
            }
        } else {
            // Si no sigue, empezar a seguir (insertar en la tabla 'follows')
            $block = "INSERT INTO block (usuarioBloqueador, usuarioBloqueado) VALUES ('$usuarioBloqueador', '$usuarioBloqueado')";
            if (mysqli_query($conn, $block)) {
                echo json_encode(['status' => 'succes']);
                header("Location: index.php?accion=bloqueado");
                exit();
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
