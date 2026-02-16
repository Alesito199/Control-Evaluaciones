<?php 
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Iniciar la sesión si aún no ha sido iniciada
}
function estadoAutenticado() : bool {
   

    // Check if the 'login' key is set and true in the session
    if (isset($_SESSION['login']) && $_SESSION['login']) {
        return true; // User is authenticated
    } else {
        return false; // User is not authenticated
    }
}

function cerrarSesion() {
        session_start(); // Asegúrate de que la sesión está iniciada
        session_unset(); // Liberar todas las variables de sesión
        session_destroy(); // Destruir la sesión
    
        // Puedes incluir una redirección aquí o dejarla para que se maneje en otra parte
        // Por ejemplo, redirigir al usuario a la página de inicio
        header("Location: ../../index.php");
        exit();
    }