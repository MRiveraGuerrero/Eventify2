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
    
    if (!isset($_SESSION['token'])) {
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
        header("Location: /login.php");
        exit();
    }

    // Handle message sending
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $token = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);

        if (!$token || $token !== $_SESSION['token']) {
            header($_SERVER['SERVER_PROTOCOL'] . ' 405 Method Not Allowed');
            exit;
        }

        $destinatario = filter_input(INPUT_POST, 'destinatario', FILTER_SANITIZE_STRING);
        $mensaje = filter_input(INPUT_POST, 'mensaje', FILTER_SANITIZE_STRING);

        if ($destinatario && $mensaje) {
            $query = "INSERT INTO mensajes (usuarioA, usuarioB, mensaje) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $usuario, $destinatario, $mensaje);
            $stmt->execute();
            $stmt->close();
        }
    }

    include("navbar.php");
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Chat - Eventify</title>
        <link rel="stylesheet" href="styles.css">
        <style>
            .chat-container {
                margin: 20px auto;
                max-width: 800px;
                padding: 20px;
                background: white;
                border-radius: 10px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .message {
                margin: 10px 0;
                padding: 10px;
                border-radius: 5px;
            }
            .sent {
                background: #e3f2fd;
                margin-left: 20%;
            }
            .received {
                background: #f5f5f5;
                margin-right: 20%;
            }
            .chat-form {
                margin-top: 20px;
                display: flex;
                gap: 10px;
            }
            .chat-form input[type="text"] {
                flex: 1;
                padding: 10px;
            }
            .chat-form button {
                padding: 10px 20px;
                background: var(--color2);
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
            }
            .user-select {
                margin-bottom: 20px;
            }
        </style>
    </head>
    <body>
        <div class="page">
            <div class="chat-container">
                <h2>Chat</h2>
                
                <!-- User selection -->
                <div class="user-select">
                    <form method="GET">
                        <select name="chat_with" onchange="this.form.submit()">
                            <option value="">Seleccionar usuario</option>
                            <?php
                                $query = "SELECT usuario FROM usuarios WHERE usuario != ?";
                                $stmt = $conn->prepare($query);
                                $stmt->bind_param("s", $usuario);
                                $stmt->execute();
                                $result = $stmt->get_result();
                                
                                while ($row = $result->fetch_assoc()) {
                                    $selected = (isset($_GET['chat_with']) && $_GET['chat_with'] === $row['usuario']) ? 'selected' : '';
                                    echo "<option value='" . htmlspecialchars($row['usuario'], ENT_QUOTES) . "' $selected>" . 
                                         htmlspecialchars($row['usuario'], ENT_QUOTES) . "</option>";
                                }
                                $stmt->close();
                            ?>
                        </select>
                    </form>
                </div>

                <!-- Messages -->
                <?php
                if (isset($_GET['chat_with'])) {
                    $chat_with = $_GET['chat_with'];
                    $query = "SELECT * FROM mensajes WHERE 
                             (usuarioA = ? AND usuarioB = ?) OR 
                             (usuarioA = ? AND usuarioB = ?) 
                             ORDER BY fecha ASC";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ssss", $usuario, $chat_with, $chat_with, $usuario);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    while ($row = $result->fetch_assoc()) {
                        $class = ($row['usuarioA'] === $usuario) ? 'sent' : 'received';
                        echo "<div class='message " . $class . "'>";
                        echo "<strong>" . htmlspecialchars($row['usuarioA'], ENT_QUOTES) . ":</strong> ";
                        echo htmlspecialchars($row['mensaje'], ENT_QUOTES);
                        echo "<br><small>" . $row['fecha'] . "</small>";
                        echo "</div>";
                    }
                    $stmt->close();

                    // Message input form
                    echo "<form class='chat-form' method='POST'>";
                    echo "<input type='hidden' name='token' value='" . $_SESSION['token'] . "'>";
                    echo "<input type='hidden' name='destinatario' value='" . htmlspecialchars($chat_with, ENT_QUOTES) . "'>";
                    echo "<input type='text' name='mensaje' placeholder='Escribe tu mensaje...' required>";
                    echo "<button type='submit'>Enviar</button>";
                    echo "</form>";
                }
                ?>
            </div>
        </div>
    </body>
</html>