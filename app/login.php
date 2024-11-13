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
              <li>Es su responsabilidad mantener la confidencialidad de su cuenta, incluyendo su contraseña y demás credenciales.</li>
              <li>Eventify no se hace responsable por cualquier pérdida o daño derivado del uso indebido de su cuenta.</li>
              <li>No debe compartir contenido que sea ilegal, ofensivo, difamatorio, violento o que infrinja derechos de terceros.</li>
              <li>Está prohibido crear múltiples cuentas con el propósito de manipular el sistema o evadir restricciones.</li>
              <li>Debe respetar los derechos, contenido y privacidad de otros usuarios.</li>
              <li>Eventify se reserva el derecho de suspender o eliminar su cuenta en caso de incumplimiento de estas condiciones.</li>
              <li>Los servicios de Eventify se proporcionan "tal cual", sin garantías explícitas o implícitas sobre su disponibilidad, funcionalidad o resultados.</li>
              <li>Nos reservamos el derecho de modificar estos términos en cualquier momento; cualquier cambio se comunicará oportunamente a los usuarios registrados.</li>
            </ul>

            <h3>Política de Privacidad</h3>
            <p>En cumplimiento de la RGPD y la LSSI, Eventify recopila y procesa los siguientes datos personales:</p>
            <ul>
              <li><strong>Nombre</strong>: utilizado para identificar al usuario.</li>
              <li><strong>Teléfono</strong>: usado para comunicaciones relacionadas con el servicio y medidas de seguridad.</li>
              <li><strong>Correo electrónico</strong>: utilizado para contacto, notificaciones y recuperación de la cuenta.</li>
              <li><strong>Fecha de nacimiento</strong>: usada para verificar la edad y ofrecer una experiencia personalizada.</li>
              <li><strong>Nombre de usuario</strong>: identificador único para acceder a la plataforma.</li>
              <li><strong>Contraseña</strong>: almacenada de forma segura con técnicas de cifrado, junto con una sal única para cada usuario.</li>
              <li><strong>Dirección IP</strong>: recopilada al iniciar sesión para mejorar la seguridad y detectar accesos sospechosos.</li>
              <li><strong>Historial de accesos</strong>: registra intentos de inicio de sesión, incluyendo fecha y hora.</li>
            </ul>
            <p>Sus datos personales serán utilizados exclusivamente para:</p>
            <ul>
              <li>Proporcionar y mejorar los servicios ofrecidos por Eventify.</li>
              <li>Garantizar la seguridad y el correcto funcionamiento de la plataforma.</li>
              <li>Enviar notificaciones importantes, como actualizaciones del servicio o cambios en los términos.</li>
              <li>Cumplir con nuestras obligaciones legales o requerimientos regulatorios.</li>
            </ul>
            <p>Sus derechos incluyen el acceso, rectificación, eliminación, portabilidad y limitación del tratamiento de sus datos, los cuales puede ejercer contactándonos en el correo <strong>mikelrg2003@gmail.com</strong>.</p>
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