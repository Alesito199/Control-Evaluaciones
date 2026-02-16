<?php
include "../vistas/header/header-docente.php";
include("../include/database/database.php");
?>

<?php
// PREGUNTAR
$docente_ci = $ci ?? 'default_usuario'; // Asegúrate de validar esto adecuadamente
// Variable para almacenar el mensaje de estado actualizado
$mensaje = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recolectar datos del formulario
    $puntaje_examen = $_POST['puntaje_examen'];
    $parcial_examenes = $_POST['parcial_examenes'];
    $tipo_examen = $_POST['tipo_examen'];
    $calendario = $_POST['calendario'];
    $estado = $_POST['estado'];
    // Preparar y vincular parámetros
    $sql = "INSERT INTO examenes (tipo_examen, puntaje_examen, parcial_examenes,estado_examen, detalle_curso_docentes_ci_docente, detalle_curso_asignacion_curso_id_asignacion_curso, detalle_curso_asignaturas_id_asignatura, calendarios_id_calendario) VALUES (:tipo_examen, :puntaje_examen, :parcial_examenes,:estado, :detalle_curso_docentes_ci_docente, :detalle_curso_asignacion_curso_id_asignacion_curso, :detalle_curso_asignaturas_id_asignatura, :calendarios_id_calendario)";

    // Preparar la sentencia
    $stmt = $conn->prepare($sql);

    // Bindear parámetros
    $stmt->bindParam(':tipo_examen', $tipo_examen);
    $stmt->bindParam(':puntaje_examen', $puntaje_examen);
    $stmt->bindParam(':parcial_examenes', $parcial_examenes);
    $stmt->bindParam(':estado', $estado);
    $stmt->bindParam(':detalle_curso_docentes_ci_docente', $docente_ci);
    $stmt->bindParam(':calendarios_id_calendario', $calendario);
    // Enviar los parámetros de asignatura y curso
    list($asignatura_id, $curso_id) = explode('|', $_POST['asignatura']);
    $stmt->bindParam(':detalle_curso_asignacion_curso_id_asignacion_curso', $curso_id);
    $stmt->bindParam(':detalle_curso_asignaturas_id_asignatura', $asignatura_id);

    // Ejecutar la sentencia
    if ($stmt->execute()) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=creado");
        exit();
    } else {
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=error&error=" . urlencode($e->getMessage()));
        exit();
    }
}
// Obtener los exámenes existentes para este docente
$sql_exams = "SELECT * FROM examenes WHERE detalle_curso_docentes_ci_docente = :docente_ci";
$stmt_exams = $conn->prepare($sql_exams);
$stmt_exams->bindParam(':docente_ci', $docente_ci);
$stmt_exams->execute();
$exams = $stmt_exams->fetchAll(PDO::FETCH_ASSOC);

// Mostrar el mensaje de error si está presente en la URL
$errorMessage = isset($_GET['error']) ? urldecode($_GET['error']) : '';

// Verificar si se solicitó habilitar o deshabilitar un usuario
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id_usuario = $_GET['id'];
    if ($_GET['action'] == 'habilitar') {
        // Actualizar el estado del usuario a activo
        $actualizar_usuario = $conn->prepare("UPDATE examenes SET estado_examen = 1 WHERE id_examen = :id_usuario");
    } elseif ($_GET['action'] == 'deshabilitar') {
        // Actualizar el estado del usuario a inactivo
        $actualizar_usuario = $conn->prepare("UPDATE examenes SET estado_examen = 0 WHERE id_examen = :id_usuario");
    }
    $actualizar_usuario->bindParam(':id_usuario', $id_usuario);
    $actualizar_usuario->execute();
    $mensaje = $_GET['action'] == 'habilitar' ? 'habilitado' : 'deshabilitado';
    header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=$mensaje");
    exit();
}

// Eliminar asignatura
if (isset($_GET['eliminar'])) {
    $id_examen = $_GET['eliminar'];

    try {
        // Iniciar una transacción
        $conn->beginTransaction();

        // Obtener los IDs de formularios relacionados
        $stmt = $conn->prepare("SELECT id_formulario FROM formularios WHERE examenes_id_examen = ?");
        $stmt->execute([$id_examen]);
        $formularios = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

        foreach ($formularios as $id_formulario) {
            // Obtener los IDs de preguntas relacionadas
            $stmt = $conn->prepare("SELECT id_pregunta FROM preguntas WHERE formularios_id_formulario = ?");
            $stmt->execute([$id_formulario]);
            $preguntas = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);

            foreach ($preguntas as $id_pregunta) {
                // Eliminar opciones relacionadas
                $stmt = $conn->prepare("DELETE FROM opciones WHERE preguntas_id_pregunta = ?");
                $stmt->execute([$id_pregunta]);
            }

            // Eliminar preguntas relacionadas
            $stmt = $conn->prepare("DELETE FROM preguntas WHERE formularios_id_formulario = ?");
            $stmt->execute([$id_formulario]);
        }

        // Eliminar formularios relacionados
        $stmt = $conn->prepare("DELETE FROM formularios WHERE examenes_id_examen = ?");
        $stmt->execute([$id_examen]);

        // Eliminar el examen de la tabla examenes
        $stmt = $conn->prepare("DELETE FROM examenes WHERE id_examen = ?");
        $stmt->execute([$id_examen]);

        // Confirmar la transacción
        $conn->commit();

        // Redirigir con un mensaje de éxito
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=eliminado");
        exit();
    } catch (PDOException $e) {
        // Revertir la transacción en caso de error
        $conn->rollBack();

        // Redirigir con un mensaje de error
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=error&error=" . urlencode($e->getMessage()));
        exit();
    }
}

?>
<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">

        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <h2 class="text-gray-900 text-5xl dark:text-white">Crear Cabecera de Examen</h2>
            <div class="mt-2 p-4">
                <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">Seleccione el Tipo de Examen</h3>
                <ul class="grid w-full gap-6 md:grid-cols-4">
                    <li>
                        <input id="prueba_escrita" name="tipo_examen" type="radio" value="Prueba Escrita" class="hidden peer" required />
                        <label for="prueba_escrita" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                            <div class="block">
                                <div class="w-full text-lg font-semibold">Prueba Escrita</div>
                                <div class="w-full">La prueba escrita puede incluir preguntas de opción múltiple, problemas para resolver con código, preguntas de respuesta libre y preguntas teóricas de corto desarrollo.</div>
                            </div>
                        </label>
                    </li>
                    <li>
                        <input id="ejercicio_codificacion" name="tipo_examen" type="radio" value="Ejercicio de Codificacion" class="hidden peer" required />
                        <label for="ejercicio_codificacion" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                            <div class="block">
                                <div class="w-full text-lg font-semibold">Ejercicios de Codificacion</div>
                                <div class="w-full">Los ejercicios de codificación requieren escribir código para resolver problemas específicos, evaluar la lógica utilizada, la eficiencia del código y su corrección.</div>
                            </div>
                        </label>
                    </li>
                    <li>
                        <input id="proyecto_practico" name="tipo_examen" type="radio" value="Proyecto Práctico" class="hidden peer" required />
                        <label for="proyecto_practico" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                            <div class="block">
                                <div class="w-full text-lg font-semibold">Proyecto Práctico</div>
                                <div class="w-full">El proyecto práctico implica implementar componentes o módulos específicos, aplicando los conceptos y técnicas aprendidas para resolver problemas concretos.</div>
                            </div>
                        </label>
                    </li>
                    <li>
                        <input id="examen_final" name="tipo_examen" type="radio" value="Examen Final" class="hidden peer" required />
                        <label for="examen_final" class="inline-flex items-center justify-between w-full p-5 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer dark:hover:text-gray-300 dark:border-gray-700 dark:peer-checked:text-blue-500 peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-100 dark:text-gray-400 dark:bg-gray-800 dark:hover:bg-gray-700">
                            <div class="block">
                                <div class="w-full text-lg font-semibold">Examen Final</div>
                                <div class="w-full">El examen final cubre todo el contenido del curso, evaluando el conocimiento y habilidades adquiridas</div>
                            </div>
                        </label>
                    </li>
                </ul>

            </div>

            <div class="mt-2 p-4">
                <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">Seleccione la Parcial</h3>
                <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input type="radio" id="primera_parcial" name="parcial_examenes" value="Primera Parcial" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="primera_parcial" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Primera Parcial </label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input type="radio" id="segunda_parcial" name="parcial_examenes" value="Segunda Parcial" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="segunda_parcial" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Segunda Parcial</label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input type="radio" id="trabajo_practico" name="parcial_examenes" value="Trabajo Practico" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="trabajo_practico" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Trabajo Practico</label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input type="radio" id="recuperatorio_primera_parcial" name="parcial_examenes" value="Recuperatorio de Primera Parcial" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="recuperatorio_primera_parcial" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Recuperatorio de Primera Parcial</label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input type="radio" id="recuperatorio_segunda_parcial" name="parcial_examenes" value="Recuperatorio de Segunda Parcial" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="recuperatorio_segunda_parcial" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Recuperatorio de Segunda Parcial</label>
                        </div>
                    </li>
                    <li class="w-full dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input type="radio" id="examen_final" name="parcial_examenes" value="Examen Final" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">
                            <label for="examen_final" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Examen Final</label>
                        </div>
                    </li>
                </ul>
            </div>

            <div class="mt-2 p-4">


                <div class="relative z-0 w-full mb-5 group">
                    <input type="text" name="puntaje_examen" id="puntaje_examen" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="puntaje_examen" class="peer-focus:font-medium absolute text-sm text-gray-900 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Total de Puntos</label>
                </div>
            </div>


            <div class="mt-2 p-4">
                <?php
                try { // Consulta SQL con JOIN para obtener curso, semestre, turno y nombre de la asignatura
                    $sql = "SELECT dc.asignacion_curso_id_asignacion_curso, dc.asignaturas_id_asignatura, ac.anho, ac.semestre, ac.turno, a.nombre_asignatura 
                                FROM detalle_curso dc 
                                JOIN asignacion_curso ac ON dc.asignacion_curso_id_asignacion_curso = ac.id_asignacion_curso 
                                JOIN asignaturas a ON dc.asignaturas_id_asignatura = a.id_asignatura 
                                WHERE dc.docentes_ci_docente = :docente_ci";

                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':docente_ci', $docente_ci);
                    $stmt->execute();
                ?>
                    <div class="relative z-0 w-full mb-5 group">
                        <div class="relative z-0 w-full mb-5 group">
                            <label for="asignatura" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleccione su Curso/Semestre/Asignatura</label>
                            <select id="asignatura" name="asignatura" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected value="">Seleccione</option>
                                <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                                    <option value="<?php echo $row['asignaturas_id_asignatura'] . "|" . $row['asignacion_curso_id_asignacion_curso']; ?>">
                                        <?php echo $row['anho'] . " - " . $row['semestre'] . " - " . $row['turno'] . " - " . $row['nombre_asignatura']; ?>
                                    </option>
                            <?php endwhile;
                            } catch (PDOException $e) {
                                echo "Error: " . $e->getMessage();
                            }
                            ?>
                            </select>
                        </div>
                        <?php
                        try {
                            // Consulta SQL para obtener las fechas del calendario
                            $sql = "SELECT * FROM calendarios";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $currentDate = date('Y-m-d'); // Obtén la fecha actual en formato YYYY-MM-DD
                        ?>
                            <div class="relative z-0 w-full mb-5 group">
                                <label for="calendario" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleccione la Fecha del Examen</label>
                                <select id="calendario" name="calendario" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                    <option selected value="">Seleccione</option>
                                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
                                        // Compara la fecha del calendario con la fecha actual
                                        if ($row['fechas_calendario'] >= $currentDate) : ?>
                                            <option value="<?php echo htmlspecialchars($row['id_calendario']); ?>">
                                                <?php echo htmlspecialchars($row['fechas_calendario']); ?>
                                            </option>
                                <?php endif;
                                    endwhile;
                                } catch (PDOException $e) {
                                    echo "Error: " . $e->getMessage();
                                }
                                ?>
                                </select>
                            </div>

                    </div>
                    <label class="text-gray-900 dark:text-gray-400">Estado</label>
                    <div class="grid md:grid-cols-2 md:gap-6">
                        <div class="relative z-0 w-full mb-5 group">
                            <input checked id="activo" type="radio" value="1" name="estado" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="activo" class="w-full py-4 ms-2 text-sm font-medium text-gray-500 dark:text-gray-300">Activo</label>
                        </div>
                        <div class="relative z-0 w-full mb-5 group">
                            <input id="inactivo" type="radio" value="0" name="estado" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="inactivo" class="w-full py-4 ms-2 text-sm font-medium text-gray-500 dark:text-gray-300">Inactivo</label>
                        </div>
                    </div>
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Crear Cabecera de Examen</button>
        </form>
    </div>
    <?php
    $sql_exams = "
SELECT 
    examenes.*, 
    asignacion_curso.anho, 
    asignacion_curso.semestre, 
    asignacion_curso.turno, 
    asignaturas.nombre_asignatura,
    calendarios.fechas_calendario
FROM 
    examenes 
INNER JOIN 
    asignacion_curso 
ON 
    examenes.detalle_curso_asignacion_curso_id_asignacion_curso = asignacion_curso.id_asignacion_curso 
INNER JOIN 
    asignaturas 
ON 
    examenes.detalle_curso_asignaturas_id_asignatura = asignaturas.id_asignatura
INNER JOIN
    calendarios
ON
    examenes.calendarios_id_calendario = calendarios.id_calendario
WHERE 
    examenes.detalle_curso_docentes_ci_docente = :docente_ci
";
    $stmt_exams = $conn->prepare($sql_exams);
    $stmt_exams->bindParam(':docente_ci', $docente_ci);
    $stmt_exams->execute();
    $currentDate = date('Y-m-d'); // Obtén la fecha actual en formato YYYY-MM-DD
    ?>
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="border w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <caption class="p-5 text-lg font-semibold text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800">
                    Examenes Creados
                    <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Aqui se encuentra la cabecera de todos sus examenes creados <span class="text-yellow-300">Verifique bien su examen</span>.</p>
                </caption>
                <thead class="text-center text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Curso</th>
                        <th scope="col" class="px-6 py-3">Asignatura</th>
                        <th scope="col" class="px-6 py-3">Puntaje</th>
                        <th scope="col" class="px-6 py-3">Tipo de Examen</th>
                        <th scope="col" class="px-6 py-3">Fecha de Examen</th>
                        <th scope="col" class="px-6 py-3">Estado de Examen</th>
                        <th scope="col-2" rowspan="2" class="px-6 py-3">Crear Detalle de Examen</th>
                        <th scope="col-2" rowspan="2" class="px-6 py-3">Eliminar</th>
                    </tr>
                </thead>
                <?php while ($exams = $stmt_exams->fetch(PDO::FETCH_ASSOC)) : ?>
                    <tbody>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 text-center">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <?php echo htmlspecialchars($exams['anho'] . " - " . $exams['semestre']) . " - " . $exams['turno']; ?>
                            </th>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($exams['nombre_asignatura']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($exams['puntaje_examen']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($exams['tipo_examen']); ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo htmlspecialchars($exams['fechas_calendario']); ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($exams['estado_examen'] == 1) : ?>
                                    <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?action=deshabilitar&id=" . $exams['id_examen']); ?>" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 deshabilitar">Deshabilitar</a>
                                <?php else : ?>
                                    <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?action=habilitar&id=" . $exams['id_examen']); ?>" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800 habilitar">Habilitar</a>
                                <?php endif; ?>
                            </td>
                            <?php
                            // Determinar si la fecha es pasada
                            $isDatePassed = $exams['fechas_calendario'] < $currentDate;

                            // Determinar la URL en función del tipo de examen
                            $url = 'crear-examen-detalle.php?id_examen=' . $exams['id_examen'];
                            if (in_array($exams['tipo_examen'], ['Ejercicios de Codificacion', 'Proyecto Práctico', 'Examen Final'])) {
                                $url = 'crear-examen-detalle.php?id_examen=' . $exams['id_examen'];
                            }
                            ?>
                            <td class="px-6 py-4">
                                <?php if (!$isDatePassed) : ?>
                                    <a href="<?php echo $url; ?>" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-80 hover:underline detalle_examen" type="button">Crear Preguntas</a>
                                <?php else : ?>
                                    <span class="text-gray-500">Fecha pasada</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">
                                <a href="?eliminar=<?php echo $exams['id_examen']; ?>" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 hover:underline eliminar" type="button">ELIMINAR</a>
                            </td>
                        </tr>
                    </tbody>
                <?php endwhile; ?>
            </table>
        </div>
    </div>


</div>




<?php
include "../vistas/footer/footer-docente.php";
?>