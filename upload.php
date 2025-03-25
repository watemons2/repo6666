<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    die("Acceso denegado.");
}

include "base.php"; // Conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['archivo'])) {
    $id_usuario = $_SESSION['id_usuario'];
    $nombre_original = $_FILES['archivo']['name'];
    $archivo_tmp = $_FILES['archivo']['tmp_name'];
    $contenido = file_get_contents($archivo_tmp); // Convertir a binario

    // Generar un identificador único para evitar duplicados
    $id_archivo = uniqid() . "_" . $nombre_original;

    // 📌 Ruta donde se guardarán los archivos en el servidor
    $directorio_destino = "C:/xampp/htdocs/archivo/";

    // Crear carpeta si no existe
    if (!is_dir($directorio_destino)) {
        mkdir($directorio_destino, 0777, true);
    }

    // 📌 Ruta final
    $ruta_final = $directorio_destino . $id_archivo;

    // Mover archivo al servidor
    if (move_uploaded_file($archivo_tmp, $ruta_final)) {
        // Guardar en la base de datos
        $sql = "INSERT INTO archivos (id_archivo, id_usuario, contenido) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $id_archivo, $id_usuario, $contenido);

        if ($stmt->execute()) {
            $mensaje = "Archivo subido correctamente y almacenado en el servidor.";
            $tipo_alerta = "success";
        } else {
            $mensaje = "Hubo un problema al guardar en la base de datos.";
            $tipo_alerta = "error";
        }

        $stmt->close();
    } else {
        $mensaje = "No se pudo subir el archivo al servidor.";
        $tipo_alerta = "error";
    }

    $conn->close();
} else {
    $mensaje = "No se ha enviado ningún archivo.";
    $tipo_alerta = "error";
}

// Redirigir con SweetAlert2
echo "<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Subir Archivo</title>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
<script>
    Swal.fire({
        title: 'Resultado',
        text: '$mensaje',
        icon: '$tipo_alerta',
        confirmButtonText: 'Aceptar'
    }).then(() => {
        window.location.href = 'subir.php';
    });
</script>
</body>
</html>";
?>
