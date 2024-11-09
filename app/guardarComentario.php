<?php
    // Incluir funciones y archivos necesarios
    include("functionsJWT.php");

    // Obtener el usuario desde la cookie
    $usuarioComent = getUsuarioCookie();
    if (!$usuarioComent) {
        die("Error: No se ha podido obtener el usuario de la cookie.");
    }

    // Conexión a la base de datos
    $hostname = "db";
    $username = "admin";
    $password = "test";
    $db = "database";
    $conn = mysqli_connect($hostname, $username, $password, $db);

    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    // Validar y filtrar los datos del formulario
    $usuarioCreador = isset($_POST['usuarioCreador']) ? mysqli_real_escape_string($conn, $_POST['usuarioCreador']) : '';
    $tituloEvento = isset($_POST['tituloEvento']) ? mysqli_real_escape_string($conn, $_POST['tituloEvento']) : '';
    $comentario = isset($_POST['comentario']) ? trim($_POST['comentario']) : '';

    // Verificar que todos los campos requeridos tengan datos
    if (empty($usuarioCreador) || empty($tituloEvento) || empty($comentario)) {
        die("Error: Todos los campos son obligatorios.");
    }

    // Filtrar y proteger el contenido del comentario para evitar XSS
    $comentarioSeguro = htmlspecialchars($comentario, ENT_QUOTES);

    // Insertar el comentario en la base de datos
    $query = "INSERT INTO comentarios (usuarioCreador, tituloEv, usuarioComent, comentario, fechaHora) 
              VALUES ('$usuarioCreador', '$tituloEvento', '$usuarioComent', '$comentarioSeguro', NOW())";

    if (mysqli_query($conn, $query)) {
        // Redirigir al usuario a la página de comentarios del evento después de guardar el comentario
        header("Location: comentarios.php?usuario=" . urlencode($usuarioCreador) . "&titulo=" . urlencode($tituloEvento));
        exit();
    } else {
        echo "Error al guardar el comentario: " . mysqli_error($conn);
    }

    // Cerrar la conexión a la base de datos
    mysqli_close($conn);
?>
