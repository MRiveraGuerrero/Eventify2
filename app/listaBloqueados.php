<?php
    include("functionsJWT.php");
    include("navbar.php");
    $usuario = getUsuarioCookie();  // Obtener el usuario actual

    // Conectar a la base de datos
    $hostname = "db";
    $username = "admin";
    $password = "test";
    $db = "database";

    $conn = mysqli_connect($hostname, $username, $password, $db);
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Consulta para obtener los usuarios bloqueados por el usuario actual
    $query = "SELECT usuarioBloqueado FROM block WHERE usuarioBloqueador = '$usuario'";

    $result = mysqli_query($conn, $query) or die(mysqli_error($conn));

    // Mostrar los resultados en una tabla HTML
    echo "<!DOCTYPE html>
    <html lang='es'>
    <head>
        <title>Usuarios Bloqueados</title>
        <link rel='stylesheet' href='styles.css'>
        <link rel='preconnect' href='https://fonts.googleapis.com'>
        <link href='https://fonts.googleapis.com/css2?family=Roboto&display=swap' rel='stylesheet'>
    </head>
    <body>
        <div class='navbar'>
            <ul>
                <li><a href='/' class='linkInicio'>Inicio</a></li>
            </ul>
        </div>

        <div class='page'>
            <div class='cabecera'>
                <img class='imagenSV' src='imagenes/logoSV.png' alt='Logo SV'>
                <h1 class='tituloInicio'>Usuarios Bloqueados</h1>
                <img class='imagenWIP' src='imagenes/logoWIP.png' alt='Logo WIP'>
            </div>";

    if (mysqli_num_rows($result) > 0) {
        // Si hay usuarios bloqueados, los mostramos en una tabla
        echo "<div class='evento'>
                <h2>Lista de Usuarios Bloqueados</h2>
                <table border='1' cellpadding='10' class='tablaUsuarios'>
                    <thead>
                        <tr>
                            <th>Usuario Bloqueado</th>
                        </tr>
                    </thead>
                    <tbody>";

        // Recorrer los resultados y mostrar los usuarios bloqueados
        while ($row = mysqli_fetch_assoc($result)) {
            $usuarioBloqueado = htmlspecialchars($row['usuarioBloqueado'], ENT_QUOTES);
            echo "<tr>
                    <td><a href='/perfil.php?usuario=$usuarioBloqueado'>$usuarioBloqueado</a></td>
                </tr>";
        }

        echo "</tbody></table>
            </div>";
    } else {
        echo "<p>No tienes usuarios bloqueados.</p>";
    }

    echo "</div>
    </body>
    </html>";

    // Cerrar la conexión a la base de datos
    mysqli_close($conn);
?>
