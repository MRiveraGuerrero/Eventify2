<?php
    include("functionsJWT.php"); 
    include("navbar.php");
    $usuario = getUsuarioCookie();
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Eventify</title>
        <link rel="stylesheet" href="styles.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    </head>
    <body>

      <div class="page">
        <div class="cabecera">
          <img class="imagenSV" src="imagenes/logoSV.png" alt="Logo SV">
          <h1 class="tituloInicio">Inicio</h1>
          <img class="imagenWIP" src="imagenes/logoWIP.png" alt="Logo WIP">
        </div>
        
        <?php
          // Conexión a la base de datos
          $hostname = "db";
          $username = "admin";
          $password = "test";
          $db = "database";

          $conn = mysqli_connect($hostname, $username, $password, $db);
          if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
          }

          $query = mysqli_query($conn, "SELECT * FROM eventos") or die(mysqli_error($conn));

          while ($row = mysqli_fetch_array($query)) {
            // Verificar si el usuario ha dado like al evento
            $usuarioLikeado = false; // Asume que no ha dado like
            $checkLikeQuery = mysqli_query($conn, "SELECT * FROM likes WHERE usuario = '".htmlspecialchars($usuario, ENT_QUOTES)."' AND evento_id = ".(int)$row['id']);
            if (mysqli_num_rows($checkLikeQuery) > 0) {
                $usuarioLikeado = true; // Si hay resultado, el usuario ya dio like
            }

            // Mostrar cada evento
            echo "
            <div class='evento'>
                <div class='barraUsuario'>
                  <p class='nombreUsuarioEvento no-overflow'>".htmlspecialchars($row['usuario'], ENT_QUOTES)."</p>
                </div>
                <h2 class='tituloEvento no-overflow'>" .htmlspecialchars($row['titulo'], ENT_QUOTES)."</h2>
                <p class='descripcionEvento'>".htmlspecialchars($row['enunciado'], ENT_QUOTES)."</p>

                <!-- Formulario de Likes -->
                <form method='POST' action='likeEvento.php'>
                  <input type='hidden' name='usuarioLike' value='".htmlspecialchars($usuario, ENT_QUOTES)."'>
                  <input type='hidden' name='usuario' value='".htmlspecialchars($row['usuario'], ENT_QUOTES)."'>
                  <input type='hidden' name='titulo' value='".htmlspecialchars($row['titulo'], ENT_QUOTES)."'>
                  <input type='hidden' name='evento_id' value='".htmlspecialchars($row['id'], ENT_QUOTES)."'>
                  
                  <button type='submit' class='botonLike ".($usuarioLikeado ? "liked" : "")."'>
                      <span class='material-symbols-outlined'>favorite</span>  <!-- Corazón -->
                      <span>" . $row['likes'] . " Likes</span>
                  </button>
                </form>
            </div>";
          }
          mysqli_close($conn);
        ?>

      </div>
    </body>
</html>
