document.addEventListener("DOMContentLoaded", function () {
    // Obtener el mensaje de la URL si existe
    const urlParams = new URLSearchParams(window.location.search);
    const mensaje = urlParams.get('mensaje');
    const error = urlParams.get('error');

    // Mostrar mensajes basados en el parámetro de la URL
    if (mensaje) {
        let tipo = 'success';
        let texto = '';
        let redireccion = null;

        if (mensaje === 'conIncor') {
            tipo = 'error';
            texto = 'Contraseña Incorrecta';
        } else if (mensaje === 'usuIncor') {
            tipo = 'error';
            texto = 'Usuario Incorrecto';
        } else if (mensaje === 'eliminado') {
            texto = 'Eliminada correctamente.';
        } else if (mensaje === 'actualizado') {
            texto = 'Los Datos se han modificado correctamente.';
        } else if (mensaje === 'habilitado') {
            texto = 'Se ha habilitado el acceso correctamente.';
        } else if (mensaje === 'deshabilitado') {
            texto = 'El usuario está inactivo. Por favor, contacte al administrador.';
        } else if (mensaje === 'error' && error) {
            tipo = 'error';
            texto = `Error: ${error}`;
        } else if (mensaje === 'loginFuncionario') {
            tipo = 'success';
            texto = 'Inicio de sesión exitoso. Redirigiendo...';
            redireccion = 'admin/index.php';
        } else if (mensaje === 'loginDocente') {
            tipo = 'success';
            texto = 'Inicio de sesión exitoso. Redirigiendo...';
            redireccion = 'docente/index.php';
        } else if (mensaje === 'loginSecretario') {
            tipo = 'success';
            texto = 'Inicio de sesión exitoso. Redirigiendo...';
            redireccion = 'admin/index.php';
        } else if (mensaje === 'loginAlumno') {
            tipo = 'success';
            texto = 'Inicio de sesión exitoso. Redirigiendo...';
            redireccion = 'alumnos/index.php';
        }

        if (texto) {
            Swal.fire({
                icon: tipo,
                title: texto,
                showConfirmButton: false,
                timer: 1600, // Mostrar el mensaje por 2 segundos
                timerProgressBar: true,
                
            }).then(() => {
                if (redireccion) {
                    window.location.href = redireccion; // Redirigir automáticamente
                } else {
                    // Remover los parámetros de la URL después de mostrar el mensaje
                    const newUrl = window.location.origin + window.location.pathname;
                    window.history.replaceState({ path: newUrl }, '', newUrl);
                }
            });
        }
    }

    // Lógica para manejar los eventos de eliminación, edición, habilitación y deshabilitación...
});
