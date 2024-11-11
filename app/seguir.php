<?php
  session_start();
  include("functionsJWT.php");

  // Verifica si el usuario está autenticado
  if (isset($_SESSION['usuario'])) {
    $usuarioSeguidor = $_SESSION['usuario'];
    $usuarioSeguido = $_POST['usuarioSeguido'];

    // Verifica si el usuario ya sigue a otro
    $conn = mysqli_connect('localhost', 'usuario', 'contraseña', 'base_de_datos');
    
    $checkFollow = "SELECT * FROM follows WHERE usuarioSeguidor = ? AND usuarioSeguido = ?";
    $stmt = $conn->prepare($checkFollow);
    $stmt->bind_param("ss", $usuarioSeguidor, $usuarioSeguido);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
      // Si ya sigue, dejar de seguir
      $deleteFollow = "DELETE FROM follows WHERE usuarioSeguidor = ? AND usuarioSeguido = ?";
      $stmt = $conn->prepare($deleteFollow);
      $stmt->bind_param("ss", $usuarioSeguidor, $usuarioSeguido);
      $stmt->execute();
      echo json_encode(['status' => 'dejado']);
    } else {
      // Si no sigue, empezar a seguir
      $insertFollow = "INSERT INTO follows (usuarioSeguidor, usuarioSeguido) VALUES (?, ?)";
      $stmt = $conn->prepare($insertFollow);
      $stmt->bind_param("ss", $usuarioSeguidor, $usuarioSeguido);
      $stmt->execute();
      echo json_encode(['status' => 'seguido']);
    }

    $stmt->close();
    $conn->close();
  } else {
    echo json_encode(['status' => 'error', 'message' => 'Usuario no autenticado']);
  }
?>
