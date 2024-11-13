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
        include("navbar.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Eventify</title>
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="login.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">

        <!-- Fuente de letra roboto de Google  https://fonts.google.com/specimen/Roboto -->
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    </head>
    <body>
    <script src="form.js"></script>

      <div class="page">
        <?php

          if (!isset($_SESSION['token'])){
            $_SESSION['token'] = bin2hex(random_bytes(32));
          }

          $hostname = "db";
          $username = "admin";
          $password = "test";
          $db = "database";

          $conn = mysqli_connect($hostname,$username,$password,$db);
          if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
          }



        $query = mysqli_query($conn, "SELECT * FROM usuarios")
          or die (mysqli_error($conn));
        ?>

        <div class="formbox">
            <div class="form-title">
                Iniciar sesión
            </div>
            <p class="desc">
                A continuación se muestran ejemplos para cada campo
            </p>
            <!-- Alinear inputs https://stackoverflow.com/questions/4309950/how-to-align-input-forms-in-html -->
            <form class="form" id="form-registro" action="/submit.php" method="POST">
                <div class="linea-form" id="linea-nombre">
                    <p>Nombre y Apellidos: Jon Tom</p>
                    <input type="text" name="nombre">
                </div>
                <div class="linea-form" id="linea-telefono">
                    <p>Teléfono: 123456789</p>
                    <input type="text" name="telefono">
                </div>
                <div class="linea-form" id="linea-email">
                    <p>Email: jontom@gmail.com</p>
                    <input type="email" name="email">
                </div>
                <div class="linea-form" id="linea-nacimiento">
                    <p>Fecha de nacimiento</p>
                    <input type="date" name="nacimiento">
                </div>
                <div class="linea-form">
                    <p id="usuario-texto">Nombre de usuario:</p>
                    <input type="text" name="usuario">
                </div>
                <div class="linea-form">
                    <p id="passwd-texto">Contraseña:</p>
                    <input type="password" name="passwd">
                </div>
                <div class="linea-form">
                  <input type="hidden" value="signin" name="tiporegistro">
                  <p>
                    <button type="submit" class="boton" id="botonRegistro">Realizar</button>
                  </p>                  
                  <button class="boton" id="botonIniciar">Cambiar a Crear cuenta</button>
                </div>
                <input type="hidden" name="token" value="<?php echo $_SESSION['token'] ?? '' ?>">
              </form>

        </div>
      </div>

      <div id="termsModal" class="modal">
        <div class="modal-content">
          <h2>Términos y Condiciones & Política de Privacidad</h2>
          <div class="modal-body">
            <h3>Términos y Condiciones</h3>
            <p>Al crear una cuenta en Eventify, usted acepta los siguientes términos:</p>
            <ul>
              <li>Ser responsable de mantener la confidencialidad de su cuenta</li>
              <li>No compartir contenido ilegal o inapropiado</li>
              <li>No crear múltiples cuentas con el fin de abusar del sistema</li>
              <li>Respetar a otros usuarios y sus contenidos</li>
            </ul>

            <h3>Política de Privacidad</h3>
            <p>Recopilamos y procesamos los siguientes datos:</p>
            <ul>
              <li>Información de perfil (nombre, email, fecha de nacimiento)</li>
              <li>Datos de uso del servicio</li>
              <li>Información necesaria para la seguridad de la cuenta</li>
            </ul>
            <p>Sus datos están protegidos y solo se utilizan para:</p>
            <ul>
              <li>Proporcionar y mejorar nuestros servicios</li>
              <li>Mantener la seguridad de su cuenta</li>
              <li>Comunicarnos con usted sobre actualizaciones importantes</li>
            </ul>
          </div>
          <div class="modal-footer">
            <label class="consent-checkbox">
              <input type="checkbox" id="termsConsent">
              <span>He leído y acepto los términos y condiciones y la política de privacidad</span>
            </label>
            <div class="modal-buttons">
              <button id="acceptTerms" class="boton" disabled>Aceptar</button>
              <button id="declineTerms" class="boton">Rechazar</button>
            </div>
          </div>
        </div>
      </div>
    </body>
</html>