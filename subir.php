<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    die("Acceso denegado.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Archivo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6200ea, #ff00ff);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .upload-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .custom-file-label {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
            cursor: pointer;
        }
        input[type="file"] {
            display: none;
        }
    </style>
</head>
<body>

<div class="upload-container">
    <h2 class="text-center text-white">Subir Archivo</h2>
    
    <!-- Formulario de subida -->
    <form action="upload.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3 text-center">
            <label class="custom-file-label" for="archivo">Seleccionar Archivo</label>
            <input type="file" id="archivo" name="archivo" required>
        </div>
        <button type="submit" class="btn btn-light w-100">Subir Archivo</button>
    </form>

    <div class="text-center mt-3">
        <a href="bienvenida.php" class="text-white fw-bold">Volver al inicio</a>
    </div>
</div>

<script>
    document.getElementById("archivo").addEventListener("change", function() {
        const label = document.querySelector(".custom-file-label");
        label.textContent = this.files.length > 0 ? this.files[0].name : "Seleccionar Archivo";
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
