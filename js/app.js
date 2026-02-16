document.addEventListener("DOMContentLoaded", function () {
    // Obtener el mensaje de la URL si existe
    const urlParams = new URLSearchParams(window.location.search);
    const mensaje = urlParams.get('mensaje');
    const error = urlParams.get('error');

    // Mostrar mensajes basados en el parámetro de la URL
    if (mensaje) {
        let tipo = 'success';
        let texto = '';

        if (mensaje === 'existe') {
            tipo = 'error';
            texto = 'Los datos ya se encuentran registrado.';
        } else if (mensaje === 'guardado') {
            texto = 'Se ha guardado correctamente.';
        } else if (mensaje === 'eliminado') {
            texto = 'Eliminada correctamente.';
        } else if (mensaje === 'actualizado') {
            texto = 'Los Datos se han modificado correctamente.';
        } else if (mensaje === 'habilitado') {
            texto = 'Se ha habilitado el acceso correctamente.';
        } else if (mensaje === 'deshabilitado') {
            texto = 'Se ha desactivo el acceso correctamente.';
        }else if (mensaje === 'repetido') {
            tipo = 'error';
            texto = 'Error: Docente ya asigando al curso';
        } else if (mensaje === 'error' && error) {
            tipo = 'error';
            texto = `Error: ${error}`;
        }

        if (texto) {
            Swal.fire({
                icon: tipo,
                title: texto,
                showConfirmButton: true,
                confirmButtonColor: '#1c64f2',
            }).then(() => {
                // Remover los parámetros de la URL después de mostrar el mensaje
                const newUrl = window.location.origin + window.location.pathname;
                window.history.replaceState({ path: newUrl }, '', newUrl);
            });
        }
    }

    // Añadir evento de escucha a los botones de eliminar
    document.querySelectorAll('.eliminar').forEach(function (element) {
        element.addEventListener('click', function (event) {
            event.preventDefault(); // Prevenir la acción por defecto del enlace

            const url = this.href;

            Swal.fire({
                title: '¿Estás seguro de eliminar estos datos?',
                text: "¡No podrás revertir esta acción!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#057a55',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminarla!',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url; // Redirigir a la URL de eliminar
                }
            });
        });
    });
 

    // Añadir evento de escucha a los botones de editar
    document.querySelectorAll('.editar').forEach(function (element) {
        element.addEventListener('click', function (event) {
            event.preventDefault(); // Prevenir la acción por defecto del enlace

            const url = this.href;

            Swal.fire({
                title: '¿Estás seguro de que desea editar estos datos?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#057a55',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, editarla!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url; // Redirigir a la URL de editar
                }
            });
        });
    });
    // Añadir evento de escucha a los botones de editar
    document.querySelectorAll('.habilitar').forEach(function (element) {
        element.addEventListener('click', function (event) {
            event.preventDefault(); // Prevenir la acción por defecto del enlace

            const url = this.href;

            Swal.fire({
                title: '¿Estás seguro de que desea habilitar?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#057a55',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, Habilitar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url; // Redirigir a la URL de editar
                }
            });
        });
    });


    document.querySelectorAll('.deshabilitar').forEach(function (element) {
        element.addEventListener('click', function (event) {
            event.preventDefault(); // Prevenir la acción por defecto del enlace

            const url = this.href;

            Swal.fire({
                title: '¿Estás seguro de que desea dehabilitar a este secretario?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#057a55',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, deshabilitar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url; // Redirigir a la URL de editar
                }
            });
        });
    });


      // Añadir evento de escucha a los botones de cancelar
      document.querySelectorAll('.cancelar').forEach(function (element) {
        element.addEventListener('click', function (event) {
            event.preventDefault(); // Prevenir la acción por defecto del enlace

            const url = this.href;

            Swal.fire({
                title: '¿Estás seguro de que desea cancelar?',
                text: "¡No podrás revertir esta acción!",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#057a55',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí!',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url; // Redirigir a la URL de eliminar
                }
            });
        });
    });
});


// script.js
document.addEventListener('keydown', function(event) {
    // Ctrl + M para abrir el manual de usuario
    if (event.ctrlKey && event.key === 'm') {
        event.preventDefault();
        abrirManualUsuario();
    }
});

function abrirManualUsuario() {
    // Ruta al PDF del manual de usuario
    const urlManual = 'pdf/MANUAL-ADMINISTRADOR-SECRETARIO.pdf';
    // Abre el PDF en una nueva ventana o pestaña
    window.open(urlManual, '_blank');
}
