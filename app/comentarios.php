<?php
    // Incluir archivos de funciones y navegación
    include("functionsJWT.php"); 
    include("navbar.php");

    // Conexión a la base de datos
    $hostname = "db";
    $username = "admin";
    $password = "test";
    $db = "database";
    $conn = mysqli_connect($hostname, $username, $password, $db);

    if ($conn->connect_error) {
        die("Error de conexión a la base de datos: " . $conn->connect_error);
    }

    // Obtener y filtrar parámetros de la URL
    $usuarioCreador = isset($_GET['usuario']) ? mysqli_real_escape_string($conn, $_GET['usuario']) : '';
    $tituloEvento = isset($_GET['titulo']) ? mysqli_real_escape_string($conn, $_GET['titulo']) : '';

    // Verificar que los parámetros no estén vacíos
    if (empty($usuarioCreador) || empty($tituloEvento)) {
        die("Error: Usuario o título de evento no especificado.");
    }

    // Consulta de comentarios
    $query = "SELECT usuarioComent, comentario, fechaHora FROM comentarios 
              WHERE usuarioCreador = '$usuarioCreador' AND tituloEv = '$tituloEvento'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error en la consulta de comentarios: " . mysqli_error($conn));
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Comentarios - <?php echo htmlspecialchars($tituloEvento); ?></title>
    <link rel="stylesheet" href="comentarios.css">
</head>
<body>

<div class="page">
    <h1>Comentarios para: <?php echo htmlspecialchars($tituloEvento); ?></h1>

    <?php
        // Mostrar los comentarios, si existen
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='comentario'>";
                echo "<p><strong>" . htmlspecialchars($row['usuarioComent'], ENT_QUOTES) . "</strong></p>";
                echo "<p>" . nl2br(htmlspecialchars($row['comentario'], ENT_QUOTES)) . "</p>";
                echo "<p><small>Fecha: " . htmlspecialchars($row['fechaHora'], ENT_QUOTES) . "</small></p>";
                echo "</div><hr>";
            }
        } else {
            echo "<p>No hay comentarios para este evento.</p>";
        }
    ?>

    <!-- Formulario para agregar un nuevo comentario -->
    <h2>Agregar un comentario</h2>
    <form method="POST" action="guardarComentario.php">
        <input type="hidden" name="usuarioCreador" value="<?php echo htmlspecialchars($usuarioCreador, ENT_QUOTES); ?>">
        <input type="hidden" name="tituloEvento" value="<?php echo htmlspecialchars($tituloEvento, ENT_QUOTES); ?>">
        
        <label for="comentario">Comentario:</label><br>
        <textarea name="comentario" id="comentario" rows="4" cols="50" required></textarea><br><br>
        
        <button type="submit">Publicar</button>
    </form>
</div>

<?php mysqli_close($conn); ?>

</body>
</html>
