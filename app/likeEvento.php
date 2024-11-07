<?php
// Conexión a la base de datos
$hostname = "db";
$username = "admin";
$password = "test";
$db = "database";

$conn = mysqli_connect($hostname, $username, $password, $db);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Verifica que se haya recibido el 'usuarioLike', 'usuarioCreador' y 'tituloEv'
if (isset($_POST['usuarioLike']) && isset($_POST['usuario']) && isset($_POST['titulo'])) {
    $usuarioLike = $_POST['usuarioLike'];
    $usuarioCreador = $_POST['usuario'];
    $tituloEv = $_POST['titulo'];

    // Asegúrate de que los datos son seguros para evitar inyecciones SQL
    $usuarioLike = mysqli_real_escape_string($conn, $usuarioLike);
    $usuarioCreador = mysqli_real_escape_string($conn, $usuarioCreador);
    $tituloEv = mysqli_real_escape_string($conn, $tituloEv);

    // Verifica si el usuario ya ha dado like a este evento
    $query_like = "SELECT * FROM likes WHERE usuarioCreador = '$usuarioCreador' AND tituloEv = '$tituloEv' AND usuarioLike = '$usuarioLike'";
    $like_result = mysqli_query($conn, $query_like);

    if (mysqli_num_rows($like_result) > 0) {
        // Si ya ha dado like, se quita el like (eliminamos de la tabla 'likes' y restamos 1 al contador de likes)
        $query_delete_like = "DELETE FROM likes WHERE usuarioCreador = '$usuarioCreador' AND tituloEv = '$tituloEv' AND usuarioLike = '$usuarioLike'";
        if (mysqli_query($conn, $query_delete_like)) {
            // Disminuye el contador de likes en la tabla eventos
            $query_update_likes = "UPDATE eventos SET likes = likes - 1 WHERE usuario = '$usuarioCreador' AND titulo = '$tituloEv'";
            mysqli_query($conn, $query_update_likes);
        } else {
            echo "Error al quitar el like: " . mysqli_error($conn);
        }
    } else {
        // Si no ha dado like, se agrega el like (insertamos en la tabla 'likes' y sumamos 1 al contador de likes)
        $query_insert_like = "INSERT INTO likes (usuarioCreador, tituloEv, usuarioLike) VALUES ('$usuarioCreador', '$tituloEv', '$usuarioLike')";
        if (mysqli_query($conn, $query_insert_like)) {
            // Aumenta el contador de likes en la tabla eventos
            $query_update_likes = "UPDATE eventos SET likes = likes + 1 WHERE usuario = '$usuarioCreador' AND titulo = '$tituloEv'";
            mysqli_query($conn, $query_update_likes);
        } else {
            echo "Error al agregar el like: " . mysqli_error($conn);
        }
    }

    // Redirige a la página principal o la página del evento, según sea necesario
    header("Location: /"); // Cambia la URL según sea necesario
    exit();
} else {
    echo "No se ha recibido el usuario o el título del evento.";
}

mysqli_close($conn);
?>
