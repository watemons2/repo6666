<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario']) || !isset($_SESSION['auth_token'])) {
    header("Location: index.html");
    exit();
}

// Verificar tiempo de inactividad (5 minutos)
$inactividad = 300; // 5 minutos en segundos
$tiempo_actual = time();

if (($tiempo_actual - $_SESSION['ultimo_acceso']) > $inactividad) {
    // Destruir la sesión si ha expirado
    session_unset();
    session_destroy();
    header("Location: index.html");
    exit();
}

// Actualizar tiempo de último acceso
$_SESSION['ultimo_acceso'] = $tiempo_actual;

// Cabeceras anti-caché
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background: #000;
            overflow: hidden;
        }
        .trail {
            position: absolute;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            pointer-events: none;
            animation: fadeOut 1.5s ease-out forwards, rainbow 1s linear infinite;
        }
        @keyframes fadeOut {
            0% { transform: scale(1); opacity: 1; }
            100% { transform: scale(3); opacity: 0; }
        }
        @keyframes rainbow {
            0% { background: #ff0000; }
            16% { background: #ff7f00; }
            33% { background: #ffff00; }
            50% { background: #00ff00; }
            66% { background: #0000ff; }
            83% { background: #4b0082; }
            100% { background: #9400d3; }
        }
        .welcome-text {
            position: absolute;
            color: #fff;
            font-size: 3rem;
            text-align: center;
        }
    </style>
    <script>
        document.addEventListener('mousemove', (e) => {
            const trail = document.createElement('div');
            trail.className = 'trail';
            document.body.appendChild(trail);
            trail.style.left = e.pageX + 'px';
            trail.style.top = e.pageY + 'px';
            setTimeout(() => trail.remove(), 1500);
        });
    </script>
</head>
<body>
    <div class="welcome-text">Bienvenido, <?php echo htmlspecialchars($_SESSION['usuario']); ?></div>
    <a href="logout.php" style="position: absolute; top: 20px; right: 20px; color: white; text-decoration: none;">Cerrar sesión</a>
</body>
</html>