<?php
      $secure = false; // solo https
      $httponly = true; // no se puede acceder  a la cookie con javascript
      $samesite = 'Strict';
  
      if(PHP_VERSION_ID < 70300) {
        session_set_cookie_params($maxlifetime, '/; SameSite='.$samesite, '', $secure, $httponly);
      } else {
          session_set_cookie_params([
              'lifetime' => $maxlifetime,
              'path' => '/',
              'domain' => '',
              'secure' => $secure,
              'httponly' => $httponly,
              'SameSite' => $samesite
          ]);
      }
      session_start(); 
      include("functionsJWT.php");

        
        if (!isset($_SESSION['token'])){
          $_SESSION['token'] = bin2hex(random_bytes(32));
        }

        $hostname = "db";
        $username = "admin";
        $password = "test";
        $db = "database";

        $conn = mysqli_connect($hostname, $username, $password, $db);
        if ($conn->connect_error) {
          die("Database connection failed: " . $conn->connect_error);
        }

        if (comprobarCookieUsuario()) {
          $usuario = getUsuarioCookie();
        } else {
          $usuario = "invitado";
        }

        include("navbar.php");
        echo '<!DOCTYPE html>
        <html>
          <head>
              <title>Eventify</title>
              <link rel="stylesheet" href="styles.css">
              <link rel="stylesheet" href="perfil.css">
              <link rel="preconnect" href="https://fonts.googleapis.com">
        
              <!-- Fuente de letra roboto de Google  https://fonts.google.com/specimen/Roboto -->
              <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
              <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
              <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
          </head>
          <body>
            
            <div class="page">
              <div class="cabecera">
                <img class="imagenSV" src="imagenes/logoSV.png"></img>
                <h1 class="tituloInicio">Perfil</h1>
                <img class="imagenWIP" src="imagenes/logoWIP.png"></img>
              </div>';
        $otro_usuario = $usuario;
        if (isset($_GET["usuario"])) {
          $otro_usuario = $_GET["usuario"];
          $query = "SELECT * FROM usuarios WHERE usuario = ?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("s", $otro_usuario);
          $stmt->execute();
          $result = $stmt->get_result();
          while ($row = $result->fetch_assoc()) {
            echo '<div class="formbox">
                    <div class="form-title">
                        Perfil de '.htmlspecialchars($row['usuario'], ENT_QUOTES). '
                    </div>
                    <!-- Alinear inputs https://stackoverflow.com/questions/4309950/how-to-align-input-forms-in-html -->
                    <form class="form">
                        <div class="linea-form">
                            <p>Nombre y Apellidos: '.htmlspecialchars($row['nombre'], ENT_QUOTES).'</p>
                        </div>
                        <div class="linea-form">
                            <p>Teléfono: '.htmlspecialchars($row['telef'], ENT_QUOTES).'</p>
                        </div>
                        <div class="linea-form">
                            <p>DNI: '.htmlspecialchars($row['dni'], ENT_QUOTES).' </p>
                        </div>
                        <div class="linea-form">
                            <p>Email: '.htmlspecialchars($row['email'], ENT_QUOTES).'</p>
                        </div>
                        <div class="linea-form">
                            <p>Nacimiento: '.htmlspecialchars($row['nacimiento'], ENT_QUOTES).'</p>
                        </div>
                    </form>
                </div>';
          }
        } else {
          $query = "SELECT * FROM usuarios WHERE usuario = ?";
          $stmt = $conn->prepare($query);
          $stmt->bind_param("s", $usuario);
          $stmt->execute();
          $result = $stmt->get_result();
          while ($row = $result->fetch_assoc()) {
            echo '<div class="formbox">
                    <div class="form-title">
                        Mi Perfil
                    </div>
                    <div class="profile-photo-container">
                        <img id="profileImage" 
                            src="' . (htmlspecialchars($row['foto_perfil'] ?? 'imagenes/default-profile.png', ENT_QUOTES)) . '" 
                            alt="Foto de perfil">  
                          <input type="file" id="photoInput" accept="image/*" style="display: none;">
                          <button type="button" id="changePhotoBtn" class="boton-foto">Cambiar foto</button>
                    </div>
                    <!-- Alinear inputs https://stackoverflow.com/questions/4309950/how-to-align-input-forms-in-html -->
                    <form class="form" action="/submit.php" id="form-registro" method="POST">
                        <div class="linea-form">
                            <p>Nombre de usuario: '.htmlspecialchars($row['usuario'], ENT_QUOTES).'</p>
                        </div>
                        <div class="linea-form">
                            <p>Nombre y Apellidos: '.htmlspecialchars($row['nombre'], ENT_QUOTES).'</p>
                            <input type="text" name="nombre" value="'.htmlspecialchars($row['nombre'], ENT_QUOTES).'">
                        </div>
                        <div class="linea-form">
                            <p>Teléfono: '.htmlspecialchars($row['telef'], ENT_QUOTES).'</p>
                            <input type="text" name="telefono" value="'.htmlspecialchars($row['telef'], ENT_QUOTES).'">
                        </div>
                        <div class="linea-form">
                            <p>DNI: '.htmlspecialchars($row['dni'], ENT_QUOTES).' </p>
                            <input type="text" name="dni" value="'.htmlspecialchars($row['dni'], ENT_QUOTES).'">
                        </div>
                        <div class="linea-form">
                            <p>Email: '.htmlspecialchars($row['email'], ENT_QUOTES).'</p>
                            <input type="email" name="email" value="'.htmlspecialchars($row['email'], ENT_QUOTES).'">
                        </div>
                        <div class="linea-form">
                            <p>Nacimiento: '.htmlspecialchars($row['nacimiento'], ENT_QUOTES).'</p>
                            <input type="date" name="nacimiento" value="'.htmlspecialchars($row['nacimiento'], ENT_QUOTES).'">
                        </div>
                        <div class="linea-form">
                            <p>Contraseña </p>
                            <input type="password" name="passwd" value="">
                        </div>
                        <div class="linea-form">
                            <input type="hidden" value="edit" name="tiporegistro">
                            <p>
                            <button type="submit" class="boton" id="botonPerfil">Editar</button>
                            </p>                  
                        </div>
                        <input type="hidden" name="token" value="'.$_SESSION['token'].'">
                    </form>
                </div>';
          }
        }

        $query = "SELECT * FROM eventos WHERE usuario = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $otro_usuario);
        $stmt->execute();
        $result = $stmt->get_result();

          while ($row = $result->fetch_assoc()) {
            echo "
            <div class='evento'>
                <div class='barraUsuario'>
                  <span class='material-symbols-outlined'><svg xmlns='http://www.w3.org/2000/svg' height='24' viewBox='0 -960 960 960' width='24'><path d='M234-276q51-39 114-61.5T480-360q69 0 132 22.5T726-276q35-41 54.5-93T800-480q0-133-93.5-226.5T480-800q-133 0-226.5 93.5T160-480q0 59 19.5 111t54.5 93Zm246-164q-59 0-99.5-40.5T340-580q0-59 40.5-99.5T480-720q59 0 99.5 40.5T620-580q0 59-40.5 99.5T480-440Zm0 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q53 0 100-15.5t86-44.5q-39-29-86-44.5T480-280q-53 0-100 15.5T294-220q39 29 86 44.5T480-160Zm0-360q26 0 43-17t17-43q0-26-17-43t-43-17q-26 0-43 17t-17 43q0 26 17 43t43 17Zm0-60Zm0 360Z'/></svg></span>
                  <p class='nombreUsuarioEvento no-overflow'>".htmlspecialchars($row['usuario'], ENT_QUOTES)."</p>
                </div>
                <h2 class='tituloEvento no-overflow'>" .htmlspecialchars($row['titulo'],ENT_QUOTES)."</h2>
                <p class='descripcionEvento'>".htmlspecialchars($row['enunciado'], ENT_QUOTES)."</p>
                <span class='botonDescarga'> <img src='./imagenes/download.svg' width='25' height='25' alt='descargar'></span>
                <!--chapuza-->
                <input type='hidden' class='opcion1' value='".htmlspecialchars($row['opcion1'], ENT_QUOTES)."'>
                <input type='hidden' class='resultado1' value='".htmlspecialchars($row['resultado1'], ENT_QUOTES)."'>
                <input type='hidden' class='opcion2' value='".htmlspecialchars($row['opcion2'], ENT_QUOTES)."'>
                <input type='hidden' class='resultado2' value='".htmlspecialchars($row['resultado2'], ENT_QUOTES)."'>
            </div>
            ";
          }

        $stmt->close();
        $conn->close();
      ?>
      <script src="perfil.js"></script>
    </div>
  </body>
</html>
