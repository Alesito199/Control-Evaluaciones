<?php
include '../vistas/header/header-docente.php';

$ci_docente = $ci; // Assuming $ci is defined somewhere

// Procesar el formulario de subir materiales
if (isset($_POST['subir_material'])) {
    $asignatura_id = isset($_POST['asignatura']) ? $_POST['asignatura'] : '';
    $upload_dir = 'uploads/materiales/'; // Directorio donde se guardarán los archivos

    // Crear el directorio si no existe
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    // Verificar que se ha subido un archivo
    if (isset($_FILES['archivo']) && $_FILES['archivo']['error'] == UPLOAD_ERR_OK) {
        $archivo_tmp = $_FILES['archivo']['tmp_name'];
        $archivo_nombre = basename($_FILES['archivo']['name']);
        $archivo_destino = $upload_dir . $archivo_nombre;

        // Mover el archivo a su destino final
        if (move_uploaded_file($archivo_tmp, $archivo_destino)) {
            // Insertar información del archivo en la base de datos
            $query_insert = $conn->prepare("INSERT INTO materiales (asignatura_id, docente_id, nombre_archivo, ruta_archivo) VALUES (:asignatura_id, :docente_id, :nombre_archivo, :ruta_archivo)");
            $query_insert->execute([
                'asignatura_id' => $asignatura_id,
                'docente_id' => $ci_docente,
                'nombre_archivo' => $archivo_nombre,
                'ruta_archivo' => $archivo_destino
            ]);

            // Redirigir con un mensaje de éxito
            header("Location: subir-material.php?mensaje=exito");
            exit();
        } else {
            // Error al mover el archivo
            header("Location: subir-material.php?mensaje=error");
            exit();
        }
    } else {
        // Error en la subida del archivo
        header("Location: subir-material.php?mensaje=error_archivo");
        exit();
    }
}

?>

<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Subir Materiales de Clase</h1>

            <?php
            if (isset($_GET['mensaje'])) {
                if ($_GET['mensaje'] == 'exito') {
                    echo '<p class="text-green-600">El material se ha subido correctamente.</p>';
                } elseif ($_GET['mensaje'] == 'error') {
                    echo '<p class="text-red-600">Hubo un error al subir el material. Inténtelo de nuevo.</p>';
                } elseif ($_GET['mensaje'] == 'error_archivo') {
                    echo '<p class="text-red-600">No se seleccionó ningún archivo o hubo un error en la subida del archivo.</p>';
                }
            }
            ?>

            <form action="subir-material.php" method="post" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="asignatura" class="block mb-2 text-sm font-medium text-gray-700">Selecciona una Asignatura:</label>
                    <select id="asignatura" name="asignatura" class="block w-full p-2.5 mb-4 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Seleccione una asignatura</option>
                        <?php
                        // Obtener las asignaturas del docente
                        $query_asignaturas = $conn->prepare("SELECT * FROM detalle_curso WHERE docentes_ci_docente = :ci_docente");
                        $query_asignaturas->execute(['ci_docente' => $ci_docente]);
                        $result_asignaturas = $query_asignaturas->fetchAll();
                        foreach ($result_asignaturas as $row) {
                            echo '<option value="'.$row['asignaturas_id_asignatura'].'">'.$row['asignaturas_id_asignatura'].'</option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="archivo" class="block mb-2 text-sm font-medium text-gray-700">Selecciona un Archivo:</label>
                    <input type="file" name="archivo" id="archivo" class="block w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 cursor-pointer focus:outline-none">
                </div>

                <button type="submit" name="subir_material" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Subir Material</button>
            </form>
        </div>
    </div>
</div>

<?php
include '../vistas/footer/footer-docente.php';
?>
