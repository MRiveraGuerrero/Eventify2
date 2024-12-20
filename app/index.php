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
        <div class="ordenamiento">
          <form method="GET" class="form-ordenamiento">
              <label for="orden">Ordenar por:</label>
              <select name="orden" id="orden" onchange="this.form.submit()">
                  <option value="reciente" <?php echo (isset($_GET['orden']) && $_GET['orden'] == 'reciente') ? 'selected' : ''; ?>>Más recientes</option>
                  <option value="likes" <?php echo (isset($_GET['orden']) && $_GET['orden'] == 'likes') ? 'selected' : ''; ?>>Más likes</option>
                  <option value="alfabetico" <?php echo (isset($_GET['orden']) && $_GET['orden'] == 'alfabetico') ? 'selected' : ''; ?>>Alfabético</option>
                  <option value="comentarios" <?php echo (isset($_GET['orden']) && $_GET['orden'] == 'comentarios') ? 'selected' : ''; ?>>Más comentados</option>
              </select>
          </form>
        </div>
        <?php
          $hostname = "db";
          $username = "admin";
          $password = "test";
          $db = "database";

          $conn = mysqli_connect($hostname, $username, $password, $db);
          if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
          }

          // Aquí va el nuevo código de ordenamiento
          $orden = isset($_GET['orden']) ? $_GET['orden'] : 'reciente';

          $query = "SELECT e.*, 
          (SELECT COUNT(*) FROM comentarios c WHERE c.usuarioCreador = e.usuario AND c.tituloEv = e.titulo) as num_comentarios 
          FROM eventos e WHERE e.usuario NOT IN (SELECT usuarioBloqueado FROM block WHERE usuarioBloqueador = '$usuario')";
          
          switch($orden) {
              case 'likes':
                  $query .= " ORDER BY likes DESC";
                  break;
              case 'alfabetico':
                  $query .= " ORDER BY titulo ASC";
                  break;
              case 'comentarios':
                  $query .= " ORDER BY num_comentarios DESC";
                  break;
              case 'reciente':
              default:
                  $query .= " ORDER BY fecha DESC";
                  break;
          }
          $query = mysqli_query($conn, $query) or die(mysqli_error($conn));


          while ($row = mysqli_fetch_array($query)) {
            $usuarioLikeado = false;

            $checkLikeQuery = mysqli_query($conn, "SELECT * FROM likes WHERE usuarioLike = '".htmlspecialchars($usuario, ENT_QUOTES)."' AND tituloEv = '".htmlspecialchars($row['titulo'], ENT_QUOTES)."' AND usuarioCreador = '".htmlspecialchars($row['usuario'], ENT_QUOTES)."'");

            if (!$checkLikeQuery) {
                echo "Error en la consulta: " . mysqli_error($conn);
            }

            if (mysqli_num_rows($checkLikeQuery) > 0) {
                $usuarioLikeado = true;
            }

            echo "
            <div class='evento'>
                <div class='barraUsuario'>
                  <span class='material-symbols-outlined'><svg xmlns='http://www.w3.org/2000/svg' height='24' viewBox='0 -960 960 960' width='24'><path d='M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z'/></svg></span>
                  <a class='nombreUsuarioEvento no-overflow' href=/perfil.php?usuario=".htmlspecialchars($row['usuario'], ENT_QUOTES).">".htmlspecialchars($row['usuario'], ENT_QUOTES)."</a>
                </div>
                <h2 class='tituloEvento no-overflow'>" .htmlspecialchars($row['titulo'], ENT_QUOTES)."</h2>
                <p class='descripcionEvento'>".htmlspecialchars($row['enunciado'], ENT_QUOTES)."</p>

                <form method='POST' action='likeEvento.php'>
                  <input type='hidden' name='usuarioLike' value='".htmlspecialchars($usuario, ENT_QUOTES)."'>
                  <input type='hidden' name='usuario' value='".htmlspecialchars($row['usuario'], ENT_QUOTES)."'>
                  <input type='hidden' name='titulo' value='".htmlspecialchars($row['titulo'], ENT_QUOTES)."'>
                  
                  <button type='submit' class='botonLike ".($usuarioLikeado ? "liked" : "")."'>
                      <span class='material-symbols-outlined'>favorite</span>
                      <span>" . $row['likes'] . " Likes</span>
                  </button>
                </form>

                <a href='comentarios.php?usuario=".urlencode($row['usuario'])."&titulo=".urlencode($row['titulo'])."' class='botonComentarios'>
                    <span class='material-symbols-outlined'>comment</span>
                    <span>Comentarios</span>
                </a>
                <input type='hidden' class='opcion1' value='".htmlspecialchars($row['opcion1'], ENT_QUOTES)."'>
                <input type='hidden' class='resultado1' value='".htmlspecialchars($row['resultado1'], ENT_QUOTES)."'>
                <input type='hidden' class='opcion2' value='".htmlspecialchars($row['opcion2'], ENT_QUOTES)."'>
                <input type='hidden' class='resultado2' value='".htmlspecialchars($row['resultado2'], ENT_QUOTES)."'>
                <span class='botonDescarga'> <img src='./imagenes/download.svg' width='25' height='25' alt='descargar'></span>
            </div>";
          }
          mysqli_close($conn);
        ?>
      </div>
      <script src="index.js"></script>
    </body>
</html>
