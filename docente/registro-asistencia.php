<?php
include "../vistas/header/header-docente.php";

// Suponiendo que tienes el ci_docente almacenado en una variable de sesión
$ci_docente = $ci;

// Procesar el formulario de registrar asistencia
if (isset($_POST['registrar_asistencia'])) {
    $asignatura_id = isset($_POST['asignatura_id']) ? $_POST['asignatura_id'] : '';
    $ci_docente = isset($_POST['ci_docente']) ? $_POST['ci_docente'] : '';
    $fecha = date('Y-m-d');
    $alumnos_presentes = isset($_POST['alumnos']) ? $_POST['alumnos'] : [];

    $query_alumnos = $conn->prepare("SELECT ci_alumno FROM alumnos WHERE asignacion_curso_id_asignacion_curso = :asignatura_id");
    $query_alumnos->execute(['asignatura_id' => $asignatura_id]);
    $result_alumnos = $query_alumnos->fetchAll();

    $success = true;
    foreach ($result_alumnos as $row) {
        $alumno_id = $row['ci_alumno'];
        $presente = in_array($alumno_id, $alumnos_presentes) ? 1 : 0;

        $query_insert = $conn->prepare("INSERT INTO registro_asistencia(alumnos_ci_alumno, detalle_curso_docentes_ci_docente, detalle_curso_asignacion_curso_id_asignacion_curso, detalle_curso_asignaturas_id_asignatura, fecha_asistencia, presente_asistencia)
                                        VALUES (:alumno_id, :docente_ci_docente, :asignacion_id, :asignatura_id, :fecha, :presente)");
        $query_insert->execute([
            'alumno_id' => $alumno_id,
            'docente_ci_docente' => $ci_docente,
            'asignacion_id' => $asignatura_id,
            'asignatura_id' => $asignatura_id,
            'fecha' => $fecha,
            'presente' => $presente
        ]);

        if (!$query_insert) {
            $success = false;
            break;
        }
    }

    // Redirigir según el resultado
    if ($success) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=asistencia");
        exit();
    } else {
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=error");
        exit();
    }
}

// Obtener las asignaturas del docente
$query_asignaturas = $conn->prepare("SELECT detalle_curso.*, docentes.nombre_docente, docentes.apellido_docente, asignacion_curso.anho, asignacion_curso.turno, asignaturas.nombre_asignatura 
                          FROM detalle_curso 
                          INNER JOIN docentes ON detalle_curso.docentes_ci_docente = docentes.ci_docente 
                          INNER JOIN asignacion_curso ON detalle_curso.asignacion_curso_id_asignacion_curso = asignacion_curso.id_asignacion_curso 
                          INNER JOIN asignaturas ON detalle_curso.asignaturas_id_asignatura = asignaturas.id_asignatura WHERE docentes_ci_docente = :ci_docente");
$query_asignaturas->execute(['ci_docente' => $ci_docente]);
$result_asignaturas = $query_asignaturas->fetchAll();
?>

<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-4">Registro de Asistencia</h1>

            <form method="post">
                <label for="asignatura" class="block mb-2 text-sm font-medium text-gray-700">Selecciona una Asignatura:</label>
                <select id="asignatura" name="asignatura" class="block w-full p-2.5 mb-4 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                    <option value="">Seleccione una asignatura</option>
                    <?php foreach ($result_asignaturas as $row) { ?>
                        <option value="<?php echo $row['asignaturas_id_asignatura']; ?>" <?php echo (isset($_POST['asignatura']) && $_POST['asignatura'] == $row['asignaturas_id_asignatura']) ? 'selected' : ''; ?>>
                            <?php echo $row['nombre_asignatura']." - ".$row['anho']." - ".$row['turno']; ?>
                        </option>
                    <?php } ?>
                </select>
            </form>

            <?php if (isset($_POST['asignatura']) && !empty($_POST['asignatura'])): 
                $asignatura_id = $_POST['asignatura'];

                $query_alumnos = $conn->prepare("SELECT * FROM alumnos WHERE asignacion_curso_id_asignacion_curso = :asignatura_id");
                $query_alumnos->execute(['asignatura_id' => $asignatura_id]);
                $result_alumnos = $query_alumnos->fetchAll();
            ?>

                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post" id="asistenciaForm">
                    <input type="hidden" name="asignatura_id" value="<?php echo $asignatura_id; ?>">
                    <input type="hidden" name="ci_docente" value="<?php echo $ci_docente; ?>">
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg mt-4">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="p-4">Presente</th>
                                    <th scope="col" class="px-6 py-3">Nombre del Alumno</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($result_alumnos as $row): ?>
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                        <th class="w-4 p-4">
                                            <div class="flex items-center">
                                                <input id="checkbox-<?php echo $row['ci_alumno']; ?>" name="alumnos[]" value="<?php echo $row['ci_alumno']; ?>" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                                <label for="checkbox-<?php echo $row['ci_alumno']; ?>" class="sr-only">checkbox</label>
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 font-medium text-gray-900 dark:text-white" onclick="toggleCheckbox('<?php echo $row['ci_alumno']; ?>')">
                                            <?php echo $row['nombre_alumno']; ?>
                                        </th>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between mt-4">
                        <button type="submit" name="registrar_asistencia" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Registrar Asistencia</button>
                        <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 cancelar">Cancelar Registro de Asistencia</a>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function toggleCheckbox(ci_alumno) {
        const checkbox = document.getElementById(`checkbox-${ci_alumno}`);
        checkbox.checked = !checkbox.checked;
    }

    function resetForm() {
        document.getElementById('asistenciaForm').reset();
        const checkboxes = document.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => checkbox.checked = false);
    }
</script>

<?php
include "../vistas/footer/footer-docente.php";
?>
