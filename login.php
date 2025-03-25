<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "base.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($usuario) || empty($password)) {
        die("Por favor, complete todos los campos.");
    }

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ?";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Error en la consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $fila = $result->fetch_assoc();
        $password_db = $fila['password'];

        if (password_verify($password, $password_db)) {
            $_SESSION['id_usuario'] = $fila['id_usuario']; 
            $_SESSION['usuario'] = $fila['nombre_usuario'];
            $_SESSION['auth_token'] = bin2hex(random_bytes(32)); 
            $_SESSION['ultimo_acceso'] = time(); 

            header("Location: subir.php");
            exit();
        } else {
            die("Usuario o contraseña incorrectos.");
        }
    } else {
        die("Usuario no encontrado.");
    }

    $stmt->close();
    $conn->close();
}
?>
