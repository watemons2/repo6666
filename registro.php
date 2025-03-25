<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "base.php";

function encriptar_contraseña($contraseña) {
    return password_hash($contraseña, PASSWORD_BCRYPT);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = bin2hex(random_bytes(8));
    $email = trim($_POST['email']);
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($nombre_usuario) || empty($password)) {
        die("Complete todos los campos.");
    }

    $contraseña_encriptada = encriptar_contraseña($password);

    // Verificar usuario/email existente
    $check_sql = "SELECT * FROM usuarios WHERE nombre_usuario = ? OR email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $nombre_usuario, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        die("El usuario o correo ya está registrado.");
    } else {
        $sql = "INSERT INTO usuarios (id_usuario, email, nombre_usuario, password, ultimo_login, estado_usuario) 
                VALUES (?, ?, ?, ?, NOW(), 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $id_usuario, $email, $nombre_usuario, $contraseña_encriptada);

        if ($stmt->execute()) {
            header("Location: index.html");
            exit();
        } else {
            die("Error al registrar: " . $stmt->error);
        }
    }

    $stmt->close();
    $conn->close();
}
?>