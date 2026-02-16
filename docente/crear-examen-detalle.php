<?php
include "../vistas/header/header-docente.php";
?>
<?php
// Obtener id_examen y docente_ci (estos valores deberían estar disponibles)
$id_examen = $_GET['id_examen']; // o de donde sea que obtengas este valor
$docente_ci = $ci ?? 'default_usuario'; // Asegúrate de validar esto adecuadamente

// Consulta para obtener los detalles del examen junto con los datos adicionales
$sql = "
    SELECT 
        e.tipo_examen,
        e.puntaje_examen,
        e.parcial_examenes,
        d.nombre_docente AS nombre_docente,
        d.apellido_docente AS apellido_docente,
        a.nombre_asignatura AS asignatura
    FROM 
        examenes e
    JOIN 
        docentes d ON e.detalle_curso_docentes_ci_docente = d.ci_docente
    JOIN 
        asignaturas a ON e.detalle_curso_asignaturas_id_asignatura = a.id_asignatura
    WHERE 
        e.id_examen = :id_examen AND e.detalle_curso_docentes_ci_docente = :docente_ci";

$stmt_exams = $conn->prepare($sql);
$stmt_exams->bindParam(':id_examen', $id_examen);
$stmt_exams->bindParam(':docente_ci', $docente_ci);
$stmt_exams->execute();
$examen = $stmt_exams->fetch(PDO::FETCH_ASSOC);

// Verificar si se obtuvo el examen
if (!$examen) {
    echo "No se encontró el examen.";
    exit;
}
// Obtener los datos
$tipo_examen = $examen['tipo_examen'];
$puntos = $examen['puntaje_examen'];
$parcial = $examen['parcial_examenes'];
$nombre_docente = $examen['nombre_docente'];
$apellido_docente = $examen['apellido_docente'];
$asignatura = $examen['asignatura'];
?>
<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">

        <form id="crearFormulario" action="build-examen/guardar-examen.php" method="post">
            <input type="hidden" id="id_examen" name="id_examen" value="<?php echo htmlspecialchars($id_examen); ?>">
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                    <label for="docente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Docente</label>
                    <input type="text" id="docente" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo htmlspecialchars($nombre_docente) . ' ' . htmlspecialchars($apellido_docente); ?>" readonly />
                </div>
                <div>
                    <label for="asignatura" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Asignatura</label>
                    <input type="text" id="asignatura" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo htmlspecialchars($asignatura); ?>" readonly />
                </div>
            </div>
            <div class="grid gap-6 mb-6 md:grid-cols-3">
                <div>
                    <label for="tipo_examen" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipo de Examen</label>
                    <input type="text" id="tipo_examen" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo htmlspecialchars($tipo_examen); ?>" readonly />
                </div>
                <div>
                    <label for="total_puntos" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total_puntos</label>
                    <input type="text" id="total_puntos" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo htmlspecialchars($puntos); ?>" readonly />
                </div>
                <div>
                    <label for="parcial" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Parcial</label>
                    <input type="text" id="parcial" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" value="<?php echo htmlspecialchars($parcial); ?>" readonly />
                </div>
            </div>

            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div>
                    <label for="recordatorio" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Recordatorio que deben tener los Alumnos a la hora de realizar el Examen</label>
                    <textarea id="recordatorio" name="recordatorio" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Recordatorio que deben tener los Alumnos a la hora de realizar el Examen..."></textarea>
                </div>
                <div>
                    <label for="indicadores" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Indicadores del Examen o Criterios de Evaluacion</label>
                    <textarea id="indicadores" name="indicadores" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Indcadores del Examen o Criterios de Evaluacion.."></textarea>
                </div>
            </div>
            <div class="mb-6 gap-4" id=preguntasContainer>


                <div class="inline-flex rounded-md shadow-sm" role="group">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-transparent border border-gray-900 rounded-s-lg hover:bg-gray-900 hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-gray-900 focus:text-white dark:border-white dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700" onclick="agregarPregunta()">Agregar Pregunta de Opción Múltiple
                    </button>
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-transparent border-b border-t border-gray-900 hover:bg-gray-900 hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-gray-900 focus:text-white dark:border-white dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700" onclick="agregarPreguntaTexto()">Agregar Pregunta de Texto Libre
                    </button>
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-transparent  border border-gray-900 hover:bg-gray-900 hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-gray-900 focus:text-white dark:border-white dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700" onclick="mostrarCantidadVF()">Verdadero/Falso
                    </button>
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-transparent border-t border-b border-gray-900 hover:bg-gray-900 hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-gray-900 focus:text-white dark:border-white dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700" onclick="agregarCodigoProgra()">Codigo de Programacion
                    </button>
                    <button type="button" class="px-4 py-2 text-sm font-medium text-gray-900 bg-transparent border border-gray-900 rounded-e-lg hover:bg-gray-900 hover:text-white focus:z-10 focus:ring-2 focus:ring-gray-500 focus:bg-gray-900 focus:text-white dark:border-white dark:text-white dark:hover:text-white dark:hover:bg-gray-700 dark:focus:bg-gray-700" onclick="agregarCorrespondencia()">Correspondencia
                    </button>
                </div>

            </div>


            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Guardar Examen</button>
            <a href="crear-examen.php" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 cancelar">Cancelar</a>
        </form>


    </div>
</div>


<script>
    let preguntaCount = 0;
    var preguntaVFCount = 0;

    function agregarPregunta() {
        preguntaCount++;
        const preguntasContainer = document.getElementById('preguntasContainer');

        const preguntaDiv = document.createElement('div');
        preguntaDiv.classList.add('pregunta');
        preguntaDiv.id = `pregunta${preguntaCount}`;
        preguntaDiv.innerHTML = `
             <div class="grid gap-6 mb-6 md:grid-cols-2 mt-4">
              
                <div class="form-group">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="pregunta${preguntaCount}">Pregunta ${preguntaCount} (Opción Múltiple):</label>
                    <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="preguntas[${preguntaCount}][texto]" required>
                </div>
             
            
                <div id="opciones${preguntaCount}" class="opciones block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                    <h4>Opciones</h4>
                    <button type="button" class="btn px-3 mt-4 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" onclick="agregarOpcion(${preguntaCount})">Agregar Opción</button>
                </div>
             
                  
            `;
        preguntasContainer.appendChild(preguntaDiv);
    }

    function agregarOpcion(preguntaId) {
        const opcionesContainer = document.getElementById(`opciones${preguntaId}`);

        const opcionDiv = document.createElement('div');
        opcionDiv.classList.add('form-group');
        opcionDiv.innerHTML = `
        
            <div class="mt-4">
                <input type="text" class="bg-gray-50 mt-2 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="preguntas[${preguntaId}][opciones][]" required>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Correcta</label>
                <input type="checkbox" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300" name="preguntas[${preguntaId}][correcta][]" value="${opcionesContainer.children.length}">
            </div>
              
        </div>
            `;
        opcionesContainer.appendChild(opcionDiv);
    }

    function agregarPreguntaTexto() {
        preguntaCount++;
        const preguntasContainer = document.getElementById('preguntasContainer');

        const preguntaDiv = document.createElement('div');
        preguntaDiv.classList.add('pregunta');
        preguntaDiv.id = `pregunta${preguntaCount}`;
        preguntaDiv.innerHTML = `
                <div class="form-group mt-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="pregunta${preguntaCount}">Pregunta ${preguntaCount} (Texto Libre):</label>
                    <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" name="preguntas[${preguntaCount}][texto]" required>
                </div>
            `;
        preguntasContainer.appendChild(preguntaDiv);
    }



    function mostrarCantidadVF() {
        var cantidadVFHtml = `
        <div class="form-group mt-4" id="cantidadVFContainer">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="cantidadVF">Cantidad de Preguntas Verdadero/Falso:</label>
            <input type="number" class="bg-gray-50 mt-2 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="cantidadVF" name="cantidadVF" min="1">
            <button type="button" class="btn px-3 mt-4 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" onclick="agregarPreguntasVF()">Agregar Preguntas</button>
        </div>`;
        document.getElementById('preguntasContainer').insertAdjacentHTML('beforeend', cantidadVFHtml);
    }

    function agregarPreguntasVF() {
        var cantidad = document.getElementById('cantidadVF').value;
        if (cantidad < 1) return;
        var preguntasContainer = document.getElementById('preguntasContainer');

        // Añadir el encabezado solo una vez
        var header = document.querySelector('.header-vf');
        if (!header) {
            var h4 = `<h4 class="header-vf">Responda con "Verdadero" o "Falso":</h4>`;
            preguntasContainer.insertAdjacentHTML('beforeend', h4);
        }
        for (var i = 0; i < cantidad; i++) {
            var preguntaId = 'pregunta-vf-' + preguntaVFCount;
            var pregunta = `
        <div class="grid gap-6 mb-6 mt-4">
            <div class="form-group pregunta-vf mt-4" id="${preguntaId}">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="vf-${preguntaId}">Pregunta Verdadero/Falso ${preguntaVFCount + 1}:</label>
                <input class="bg-gray-50 mt-2 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" type="text" id="vf-${preguntaId}" name="preguntas[${preguntaVFCount}][texto]">

                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="vfRespuesta-${preguntaId}">Respuesta:</label>
                <select class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="vfRespuesta-${preguntaId}" name="preguntas[${preguntaVFCount}][es_correcta]" onchange="toggleJustificacion('${preguntaId}')">
                    <option value="1">Verdadero</option>
                    <option value="0">Falso</option>
                </select>
                
                <div class="mt-2" id="justificacionContainer-${preguntaId}" style="display:none;">
                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="vfJustificacion-${preguntaId}">Justifica la Falsa:</label>
                    <textarea class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" id="vfJustificacion-${preguntaId}" name="preguntas[${preguntaVFCount}][justificacion]"></textarea>
                </div>
            </div>
        </div>`;
            preguntasContainer.insertAdjacentHTML('beforeend', pregunta);
            preguntaVFCount++;
        }
        document.getElementById('cantidadVFContainer').remove();
    }

    function toggleJustificacion(preguntaId) {
        var selectElement = document.getElementById('vfRespuesta-' + preguntaId);
        var justificacionContainer = document.getElementById('justificacionContainer-' + preguntaId);

        if (selectElement.value === '0') {
            justificacionContainer.style.display = 'block';
        } else {
            justificacionContainer.style.display = 'none';
        }
    }

    function agregarCodigoProgra() {
        preguntaCount++;
        const preguntasContainer = document.getElementById('preguntasContainer');

        const preguntaDiv = document.createElement('div');
        preguntaDiv.classList.add('pregunta');
        preguntaDiv.id = `pregunta${preguntaCount}`;

        preguntaDiv.innerHTML = `
        <div class="form-group mt-4">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="pregunta${preguntaCount}">
                Pregunta ${preguntaCount} (Codigo):
            </label>
            <textarea type="text" 
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" 
                   name="preguntas[${preguntaCount}][texto]" 
                   required></textarea>
            <input type="hidden" name="preguntas[${preguntaCount}][CodigoPro]" value="1">
        </div>
    `;

        preguntasContainer.appendChild(preguntaDiv);
    }
    /*     function agregarCompletarBlanco() {
            completarBlancoCount++;
            const pregunta = `
            <div class="form-group mt-4">
                <label for="completar${completarBlancoCount}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Completar en Blanco ${completarBlancoCount}:</label>
                <input type="text" id="completar${completarBlancoCount}" name="completar[${completarBlancoCount}][texto]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Escribe la frase con el espacio en blanco" required>
                <label for="completarRespuesta${completarBlancoCount}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white mt-2">Respuesta:</label>
                <input type="text" id="completarRespuesta${completarBlancoCount}" name="completar[${completarBlancoCount}][respuesta]" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Escribe la respuesta correcta" required>
            </div>`;
            document.getElementById('preguntasContainer').insertAdjacentHTML('beforeend', pregunta);
        }



     */

    function generarPDF() {
        const element = document.getElementById('contenido-examen');
        const opt = {
            margin: 1,
            filename: 'examen.pdf',
            image: {
                type: 'jpeg',
                quality: 0.98
            },
            html2canvas: {
                scale: 2
            },
            jsPDF: {
                unit: 'in',
                format: 'letter',
                orientation: 'portrait'
            }
        };
        html2pdf().from(element).set(opt).save();
    }

    function guardarExamen() {
        // Obtener el formulario por su ID
        var formulario = document.getElementById("crearFormulario");

        // Crear un objeto FormData para recopilar los datos del formulario
        var formData = new FormData(formulario);

        // Crear una nueva solicitud HTTP (POST) utilizando XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Especificar la URL a la que se enviarán los datos del formulario
        var url = "build-examen/guardar-examen.php";

        // Abrir la solicitud
        xhr.open("POST", url, true);

        // Configurar el manejo de la respuesta
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // La solicitud se completó con éxito
                    console.log("Examen guardado correctamente.");
                    // Aquí podrías hacer algo adicional después de guardar el examen, como redirigir a otra página
                } else {
                    // La solicitud falló
                    console.error("Error al guardar el examen:", xhr.status);
                }
            }
        };

        // Enviar los datos del formulario
        xhr.send(formData);
    }

    document.getElementById('cancelarBtn').addEventListener('click', function() {
        // Limpiar todos los inputs del formulario
        const form = document.querySelector('form');
        form.reset();

        // Redirigir a examen.php
        window.location.href = 'crear-examen.php';
    });
</script>



<?php
include "../vistas/footer/footer-docente.php";
?>

