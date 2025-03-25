<?php
session_start();

// Destruir todas las variables de sesión
$_SESSION = array();

// Eliminar la cookie de sesión
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// Destruir la sesión
session_destroy();

// Redirigir con JavaScript para evitar caché
echo '
<script>
    // Forzar recarga limpia
    window.location.replace("index.html");
    history.replaceState(null, null, window.location.href);
</script>
';
exit();
?>