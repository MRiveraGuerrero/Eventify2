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
  if (!isset($_SESSION['token'])){
    $_SESSION['token'] = bin2hex(random_bytes(32));
  }
  include("functionsJWT.php"); 
  
  
  

  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "database";

  $conn = mysqli_connect($hostname,$username,$password,$db);
  if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
  }

  
  if(comprobarCookieUsuario()){
    $usuario = getUsuarioCookie();
  }else{
    $usuario = "invitado";
  }

  include("navbar.php");
  $query = mysqli_query($conn, "SELECT * FROM eventos WHERE usuario='".$usuario."'")
  or die (mysqli_error($conn));
  echo '<!DOCTYPE html>
  <html>
      <head>
          <title>Eventify</title>
          <link rel="stylesheet" href="editarPost.css">
          <link rel="preconnect" href="https://fonts.googleapis.com">
  
          <!-- Fuente de letra roboto de Google  https://fonts.google.com/specimen/Roboto -->
          <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
          <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
          <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
      </head>
        
        <div class="page">
        <div class="cabecera">
            <img class="imagenSV" src="imagenes/logoSV.png"></img>
            <h1 class="tituloInicio">Editar Eventos</h1>
            <img class="imagenWIP" src="imagenes/logoWIP.png"></img>
          </div>
          <script src="eliminarEvento.js"></script>
          ';
  while ($row = mysqli_fetch_array($query)) {
    echo "
    <div class='evento'>
        <div class='barraUsuario'>
          <form action='/editar.php' method='POST'>
            <input id='valtitulo' name='titulo' type='hidden' value='".htmlspecialchars($row['titulo'], ENT_QUOTES)."'>
            <button class='botonEditar'> Editar </button>
          </form>
          <button class='botonEliminar'> Eliminar evento </button>
        </div>
        <h2 class='tituloEvento no-overflow'>".htmlspecialchars($row['titulo'], ENT_QUOTES) ."</h2>
        <input id='valtoken' type='hidden' name='token' value='".$_SESSION['token']."'>
        <p class='descripcionEvento no-overflow'>".htmlspecialchars($row['enunciado'], ENT_QUOTES) ."</p>
    </div>
    ";
  }
  

  
?>

