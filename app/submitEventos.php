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
            // https://www.freecodecamp.org/news/creating-html-forms/
            if ($_SERVER["REQUEST_METHOD"] === "POST") {

                // Evitar CSRF
                $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);

                if (!$token || $token !== $_SESSION['token']) {
                    // return 405 http status code
                    header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
                    echo "ERROR TOKEN CSRF";
                    exit;
                }
                
                $viejoTitulo = $_POST["viejoTitulo"];
                $titulo = $_POST["titulo"];
                $enunciado = $_POST["enunciado"];
                $opcion1 = $_POST["opcion1"];
                $resultado1 = $_POST["resultado1"];
                $opcion2 = $_POST["opcion2"];
                $resultado2 = $_POST["resultado2"];
                $es_edit = $_POST["flagedit"];

                $hostname = "db";
                $username = "admin";
                $password = "test";
                $db = "database";

                $conn = mysqli_connect($hostname,$username,$password,$db); 
                if(comprobarCookieUsuario()){
                    $usuario = getUsuarioCookie();

                }else{
                    $usuario = "invitado";
                }
                
                if ($conn->connect_error) {
                die("Database connection failed: " . $conn->connect_error);
                }

                if($es_edit != "edit"){
                    $consulta = "INSERT INTO eventos (usuario, titulo, enunciado, opcion1, resultado1, opcion2, resultado2, likes) VALUES(?, ?, ?, ?, ?, ?, ?, 0)";
                    $tipos = "sssssss";
                    $parametros = array($usuario, $titulo , $enunciado, $opcion1, $resultado1, $opcion2, $resultado2);
                    $mensaje = "Evento creado";

                }else{
                    $consulta = "UPDATE eventos SET titulo = ?, enunciado = ?, opcion1 = ?, resultado1 = ?, opcion2 = ?, resultado2 = ? WHERE titulo = ? AND usuario = ?";
                    $tipos = "ssssssss";
                    $parametros = array($titulo , $enunciado, $opcion1, $resultado1, $opcion2, $resultado2, $viejoTitulo, $usuario);
                    $mensaje = "Evento editado";
                }


                if($stmt = mysqli_prepare($conn, $consulta)){
                        $stmt->bind_param($tipos, ...$parametros);
                        $stmt->execute();
                        $stmt->close();
                }else{
                    $mensaje = "error";
                }

             }else{
                header("Location: /"); // Redirigimos a inicio si no es post
                exit();
             }
             include("navbar.php");
            ?>
<!DOCTYPE html>
<html>
    <head>
        <title>
            Eventify
        </title>
        <link rel="stylesheet" href="submit.css">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        
        <div class="page mensaje">
            <?php
                echo $mensaje; 
            ?> 
        </div>
    </body>
</html>