<?php
include "../vistas/header/header-alumno.php";

// CONSULTA PARA VER SI TRAE EL METODO GET
if (isset($_GET['id_examen'])) {
    $id_examen = $_GET['id_examen'];
} else {
    header("Location: ../index.php");
    exit;
}

try {
    $consulta_examen = $conn->prepare("
    SELECT e.id_examen, e.tipo_examen, e.puntaje_examen, e.parcial_examenes, 
           do.nombre_docente, do.apellido_docente,
           dc.anho, dc.semestre, dc.turno,
           asig.nombre_asignatura, 
           ca.fechas_calendario 
    FROM 
        examenes e
    JOIN
        docentes do ON e.detalle_curso_docentes_ci_docente = do.ci_docente
    JOIN
        asignacion_curso dc ON e.detalle_curso_asignacion_curso_id_asignacion_curso = dc.id_asignacion_curso
    JOIN
        asignaturas asig ON e.detalle_curso_asignaturas_id_asignatura = asig.id_asignatura
    JOIN
        calendarios ca ON e.calendarios_id_calendario = ca.id_calendario
    WHERE e.id_examen = :id_examen");

    $consulta_examen->bindParam(':id_examen', $id_examen, PDO::PARAM_INT);
    $consulta_examen->execute();
    $examen = $consulta_examen->fetch(PDO::FETCH_ASSOC);

    $formulario_examen = $conn->prepare("SELECT id_formulario, notas_formulario, indicadores_formulario
                                     FROM formularios 
                                     WHERE examenes_id_examen = :id_examen");
    $formulario_examen->bindParam(':id_examen', $id_examen, PDO::PARAM_INT);
    $formulario_examen->execute();
    $formulario = $formulario_examen->fetch(PDO::FETCH_ASSOC);

    $preguntas = []; // Inicializar como un array vacío

    if ($formulario) {
        $id_formulario = $formulario['id_formulario'];

        $preguntas_examen = $conn->prepare("SELECT id_pregunta, texto_pregunta, tipo_pregunta 
                                        FROM preguntas 
                                        WHERE formularios_id_formulario = :id_formulario");
        $preguntas_examen->bindParam(':id_formulario', $id_formulario, PDO::PARAM_INT);
        $preguntas_examen->execute();
        $preguntas = $preguntas_examen->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "No se encontró el formulario para el examen.";
    }
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>

<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        <!-- Cabecera del examen -->
        <div class="bg-blue-50 border-gray-200 dark:bg-gray-900">
            <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="../others/img/Imagen1.png" class="h-8" alt="Flowbite Logo" />
                </div>
                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                    <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Facultad de Ingenieria-Carrera de Ingenieria Informatica</span>
                </div>
                <div class="flex items-center space-x-6 rtl:space-x-reverse">
                    <label class="text-sm text-gray-900 dark:text-white border-b">TP: <?php echo $examen['puntaje_examen'] ?></label>
                    <label class="text-sm text-gray-900 dark:text-white border-b">PC: </label>
                    <label class="text-sm text-gray-900 dark:text-white border-b">Fecha de Examen: <?php echo $examen['fechas_calendario'] ?></label>
                </div>
            </div>
        </div>
        <!-- Fin de la cabecera del examen -->

        <div class="bg-gray-50 dark:bg-gray-700">
            <div class="max-w-screen-xl px-4 py-3 mx-auto">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="flex items-center justify-center rounded border p-2 bg-white">
                        <label class="text-lg text-gray-900 dark:text-white">Alumno/a: <span class="text-gray-900 dark:text-white"> <?php echo $nombre . ' ' . $apellido; ?></span> </label>
                    </div>
                    <div class="flex items-center justify-center border p-2 bg-white">
                        <label class="text-base text-gray-900 dark:text-white">Numero de Cedula: <span class="text-gray-900 dark:text-white"><?php echo $ci; ?></span></label>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="flex items-center justify-center rounded border p-2 bg-white">
                        <label class="text-base text-gray-900 dark:text-white">Curso: <?php echo $examen['anho'] ?></label>
                    </div>
                    <div class="flex items-center justify-center rounded border p-2 bg-white">
                        <label class="text-base text-gray-900 dark:text-white">Semestre: <?php echo $examen['semestre'] ?></label>
                    </div>
                    <div class="flex items-center justify-center rounded border p-2 bg-white">
                        <label class="text-base text-gray-900 dark:text-white">Turno: <?php echo $examen['turno'] ?></label>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4 mb-6">
                    <div class="flex items-center justify-center rounded border p-2 bg-white">
                        <label class="text-base text-gray-900 dark:text-white" aria-current="page">Docente: <?php echo $examen['nombre_docente'] . ' ' . $examen['apellido_docente'] ?></label>
                    </div>
                    <div class="flex items-center justify-center rounded border p-2 bg-white">
                        <label class="text-base text-gray-900 dark:text-white" aria-current="page">Asignatura: <?php echo $examen['nombre_asignatura'] ?></label>
                    </div>
                    <div class="flex items-center justify-center rounded border p-2 bg-white">
                        <label class="text-base text-gray-900 dark:text-white">Tipo de Examen: <?php echo $examen['tipo_examen'] ?></label>
                    </div>
                    <div class="flex items-center justify-center rounded border p-2 bg-white">
                        <label class="text-base text-gray-900 dark:text-white">Parcial: <?php echo htmlspecialchars($examen['parcial_examenes']); ?></label>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin de la cabecera de examen -->
    </div>

    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        <form class="border" action="examen-alumno/guardar-examen.php" method="post">
            <div class="bg-gray-50 dark:bg-gray-700">
                <div class="max-w-screen-xl px-4 py-3 mx-auto">
                    <!-- Campos ocultos para enviar id_examen y ci_alumno -->
                    <input type="hidden" name="id_examen" value="<?php echo $id_examen; ?>">
                    <input type="hidden" name="ci_alumno" value="<?php echo $ci; ?>">
                    <input type="hidden" name="id_formulario" value="<?php echo $id_formulario ?? ''; ?>">
                    <!-- Variables para manejar las etiquetas de las secciones -->
                    <?php
                    $label_mostrado_texto = false;
                    $label_mostrado_opcion_mul = false;
                    $label_mostrado_vf = false;
                    $label_mostrado_copro = false;

                    foreach ($preguntas as $pregunta) : ?>
                        <div class="mb-6">
                            <!-- Pregunta tipo texto -->
                            <?php if ($pregunta['tipo_pregunta'] == 'texto') : ?>
                                <div class="mt-4">
                                    <!-- Etiqueta para la sección de preguntas de texto -->
                                    <?php if (!$label_mostrado_texto) : ?>
                                        <label class="block mb-2 text-base font-medium text-gray-900 dark:text-white">
                                            Preguntas.
                                        </label>
                                        <?php $label_mostrado_texto = true; ?>
                                    <?php endif; ?>
                                    <label for="pregunta_<?php echo $pregunta['id_pregunta']; ?>" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        <?php echo htmlspecialchars($pregunta['texto_pregunta']); ?>
                                    </label>
                                    <textarea id="pregunta_<?php echo $pregunta['id_pregunta']; ?>" name="pregunta_<?php echo $pregunta['id_pregunta']; ?>" rows="4" class="form-textarea w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required></textarea>
                                </div>
                            <?php endif; ?>

                            <!-- Pregunta tipo opción múltiple -->
                            <?php if ($pregunta['tipo_pregunta'] == 'opcion_mul') : ?>
                                <div class="mt-4">
                                    <!-- Etiqueta para la sección de preguntas de opción múltiple -->
                                    <?php if (!$label_mostrado_opcion_mul) : ?>
                                        <label class="block mb-2 text-base font-medium text-gray-900 dark:text-white">
                                            Preguntas de opción múltiple.
                                        </label>
                                        <?php $label_mostrado_opcion_mul = true; ?>
                                    <?php endif; ?>
                                    <label for="pregunta_<?php echo $pregunta['id_pregunta']; ?>" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        <?php echo htmlspecialchars($pregunta['texto_pregunta']); ?>
                                    </label>
                                    <select id="pregunta_<?php echo $pregunta['id_pregunta']; ?>" name="pregunta_<?php echo $pregunta['id_pregunta']; ?>" class="form-select w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
                                        <option value="">Seleccione una opción</option>
                                        <?php
                                        $opciones_examen = $conn->prepare("SELECT id_opcion, texto_opcion FROM opciones WHERE preguntas_id_pregunta = :id_pregunta");
                                        $opciones_examen->bindParam(':id_pregunta', $pregunta['id_pregunta'], PDO::PARAM_INT);
                                        $opciones_examen->execute();
                                        $opciones = $opciones_examen->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($opciones as $opcion) :
                                        ?>
                                            <option value="<?php echo htmlspecialchars($opcion['id_opcion']); ?>">
                                                <?php echo htmlspecialchars($opcion['texto_opcion']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif; ?>

                            <!-- Pregunta tipo verdadero o falso -->
                            <?php if ($pregunta['tipo_pregunta'] == 'vf') : ?>
                                <div class="mt-4">
                                    <!-- Etiqueta para la sección de preguntas de verdadero o falso -->
                                    <?php if (!$label_mostrado_vf) : ?>
                                        <label class="block mb-2 text-base font-medium text-gray-900 dark:text-white">
                                            Preguntas de verdadero o falso.
                                        </label>
                                        <?php $label_mostrado_vf = true; ?>
                                    <?php endif; ?>
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                        <?php echo htmlspecialchars($pregunta['texto_pregunta']); ?>
                                    </label>
                                    <div class="flex items-center space-x-2">
                                        <label for="vf_<?php echo $pregunta['id_pregunta']; ?>_true" class="text-gray-900 dark:text-white">
                                            <input type="radio" id="vf_<?php echo $pregunta['id_pregunta']; ?>_true" name="pregunta_<?php echo $pregunta['id_pregunta']; ?>" value="true" class="form-radio" required> Verdadero
                                        </label>
                                        <label for="vf_<?php echo $pregunta['id_pregunta']; ?>_false" class="text-gray-900 dark:text-white">
                                            <input type="radio" id="vf_<?php echo $pregunta['id_pregunta']; ?>_false" name="pregunta_<?php echo $pregunta['id_pregunta']; ?>" value="false" class="form-radio" required> Falso
                                        </label>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Pregunta tipo Código -->
                            <?php if ($pregunta['tipo_pregunta'] == 'CodigoPro') : ?>
                                <div class="mt-4">
                                    <!-- Etiqueta para la sección de preguntas de código -->
                                    <?php if (!$label_mostrado_copro) : ?>
                                        <label class="text-lg text-gray-900 dark:text-white">Resuelve: </label>
                                    <?php $label_mostrado_copro = true;
                                    endif; ?>
                                    <label for="languageSelect">Selecciona el lenguaje:</label>
                                    <select id="languageSelect">
                                        <option value="text/x-c++src">C++</option>
                                        <option value="text/x-csrc">C</option>
                                        <option value="text/x-java">Java</option>
                                        <option value="javascript">JavaScript</option>
                                        <option value="text/x-mysql">SQL</option>
                                        <option value="application/x-httpd-php">PHP</option>
                                        <option value="text/html">HTML</option>
                                        <option value="text/css">CSS</option>
                                    </select>
                                    <label class="text-lg text-gray-900 dark:text-white"><span class="text-gray-900 dark:text-white"><?php echo $pregunta['texto_pregunta'] ?></span></label>
                                    <!-- Editor de código -->
                                    <textarea id="editor" name="code" class="block w-full text-sm text-gray-800 border-gray-900 dark:bg-gray-800 focus:ring-0 dark:text-white dark:placeholder-gray-400"></textarea>
                                    <input type="hidden" name="<?php echo $pregunta['id_pregunta']; ?>_code" id="id" value="<?php echo $pregunta['id_pregunta']; ?>">
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <button type="submit" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800 float-right">Finalizar Examen</button>
        </form>
    </div>

</div>

<?php include "../vistas/footer/footer-alumno.php"; ?>

<script>
    window.onload = function() {
        var editor = CodeMirror.fromTextArea(document.getElementById("editor"), {
            lineNumbers: true,
            mode: "text/x-c++src",
            theme: "dracula",
            autofocus: true,
            extraKeys: {
                "Ctrl-Space": "autocomplete"
            }
        });

        document.getElementById("languageSelect").addEventListener("change", function() {
            var selectedLanguage = this.value;
            editor.setOption("mode", selectedLanguage);
        });

        function ejecutarCodigo() {
            var codigo = editor.getValue(); // Obtiene el código del editor
            var outputTextarea = document.getElementById("mostrarejecutado");
            outputTextarea.value = ""; // Limpiar el textarea antes de mostrar el nuevo resultado

            // Interceptar console.log
            var originalConsoleLog = console.log;
            console.log = function(message) {
                outputTextarea.value += message + "\n";
                originalConsoleLog.apply(console, arguments);
            };

            try {
                // Evalúa el código en un entorno seguro
                eval(codigo);
            } catch (error) {
                console.error("Error al ejecutar el código:", error);
                outputTextarea.value += "Error al ejecutar el código: " + error + "\n";
            }

            // Restaurar console.log
            console.log = originalConsoleLog;
        }

        window.ejecutarCodigo = ejecutarCodigo;
    };

    function mostrarJustificacion(select, preguntaId) {
        var justificacionTextarea = document.getElementById('justificacion_' + preguntaId);
        if (select.value == "0") {
            justificacionTextarea.style.display = 'block';
        } else {
            justificacionTextarea.style.display = 'none';
        }
    }
</script>


<?php
require_once "../vistas/footer/footer-alumno.php";
?>