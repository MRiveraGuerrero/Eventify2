<?php
  ini_set('display_errors', 0);
  ini_set('display_startup_errors', 0);
  //ini_set('display_errors', 0);
  $hostname = "db";
  $username = "admin";
  $password = "test";
  $db = "database";

  $conn = mysqli_connect($hostname,$username,$password,$db); 
  if(comprobarCookieUsuario() && $_COOKIE["user"] !== "invitado"){
    $perfil = '<a href="/perfil.php"><img src="./imagenes/perfil.svg" width="35" height="35" alt="perfil"></a>';
    //cambiar el href de abajo para que redirija a logout.php
    $logOut = '<a href="logout.php"><img src="./imagenes/logout.svg" width="35" height="35" alt="cerrar sesion"></a>';
  }else{
    $perfil = '<a class="linkLogin" href=/login.php>Iniciar sesión</a>';
    $logOut = "";
  }
  $navbar = '
  <link rel="stylesheet" href="styles.css">

  <!-- Fuente de letra roboto de Google  https://fonts.google.com/specimen/Roboto -->
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">

  <!-- Iconos de Google  https://developers.google.com/fonts/docs/material_icons?hl=es-419 -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
  <div class="navbar">
  <ul>
    <div class="links">
      <a class="linkInicio" href="/">Inicio</a>
      <a class="linkInicio" href="/crearEvento.php">Crear Evento</a>
      <a class="linkInicio" href="/editarPost.php">Editar Evento</a>
      <a class="linkInicio" href="/chat.php">Chat</a>
      <a class="linkInicio" href="/listaBloqueados.php">Usuarios bloqueados</a>
    </div>
    <li>
      '.$perfil.'
      '.$logOut.'
    </li>
  </ul>
  </div>';

  echo $navbar;
?>
