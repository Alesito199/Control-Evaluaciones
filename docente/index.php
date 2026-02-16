<?php
include "../vistas/header/header-docente.php";
// Consultar los tres exámenes más recientes
$query_examenes = $conn->prepare("
    SELECT 
        e.id_examen, e.tipo_examen, e.parcial_examenes, 
        cur.anho,cur.semestre,cur.turno, 
        asig.nombre_asignatura, 
        c.fechas_calendario 
    FROM examenes e
    JOIN calendarios c ON e.calendarios_id_calendario = c.id_calendario
    JOIN asignaturas asig ON e.detalle_curso_asignaturas_id_asignatura = asig.id_asignatura
    JOIN asignacion_curso cur ON e.detalle_curso_asignacion_curso_id_asignacion_curso = cur.id_asignacion_curso
    WHERE e.detalle_curso_docentes_ci_docente = :ci_docente
    ORDER BY c.fechas_calendario DESC
    LIMIT 4
");
$query_examenes->execute(['ci_docente' => $ci]);
$result_examenes = $query_examenes->fetchAll();
?>
<div class="p-4 sm:ml-64">
      <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
            <section class="bg-white dark:bg-gray-900">
                  <div class="grid md:grid-cols-2 gap-8">
                        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12">
                              <h2 class="text-blue-700 dark:text-white text-3xl font-extrabold mb-2 border-b">
                                    <?php if (count($result_examenes) > 0) : ?>
                                          Fecha de Exámenes más Recientes
                                    <?php else : ?>
                                          Exámenes en Proceso
                                    <?php endif; ?>
                              </h2>
                              <ol class="relative border-l border-gray-200 dark:border-gray-700">
                                    <?php if (count($result_examenes) > 0) : ?>
                                          <?php foreach ($result_examenes as $examen) : ?>
                                                <li class="mb-10 ms-4">
                                                      <div class="absolute w-3 h-3 bg-blue-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                                      <time class="mb-1 text-sm font-normal leading-none text-blue-400 dark:text-blue-500">Fecha de Examen: <?php echo htmlspecialchars($examen['fechas_calendario']); ?></time>
                                                      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                            <?php echo htmlspecialchars($examen['parcial_examenes']); ?> - Asignatura: <?php echo htmlspecialchars($examen['nombre_asignatura']); ?>
                                                      </h3>
                                                      <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">Curso: <?php echo htmlspecialchars($examen['anho'] . ' - ' . $examen['semestre'] . ' - ' . $examen['turno']); ?></p>
                                                </li>
                                          <?php endforeach; ?>
                                    <?php else : ?>
                                          <li class="mb-10 ml-4">
                                                <div class="absolute w-3 h-3 bg-blue-200 rounded-full mt-1.5 -left-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                                <time class="mb-1 text-sm font-normal leading-none text-blue-400 dark:text-blue-500">No hay exámenes recientes disponibles</time>
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ningún examen programado</h3>
                                                <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">Por favor, espere nuevos exámenes o contacte con el administrador.</p>
                                          </li>
                                    <?php endif; ?>
                              </ol>
                              <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative text-center" role="alert">
                                    <strong class="font-bold">¡Recordatorio!</strong>
                                    <span class="block sm:inline">No olvide crear los exámenes para los alumnos.</span>
                              </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12">
                              <a href="#" class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400 mb-2">
                                    <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                                          <path d="M17 11h-2.722L8 17.278a5.512 5.512 0 0 1-.9.722H17a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1ZM6 0H1a1 1 0 0 0-1 1v13.5a3.5 3.5 0 1 0 7 0V1a1 1 0 0 0-1-1ZM3.5 15.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2ZM16.132 4.9 12.6 1.368a1 1 0 0 0-1.414 0L9 3.55v9.9l7.132-7.132a1 1 0 0 0 0-1.418Z" />
                                    </svg>
                                    Crear Examen
                              </a>
                              <h2 class="text-gray-900 dark:text-white text-3xl font-extrabold mb-2">Crear Examen</h2>
                              <p class="text-lg font-normal text-gray-500 dark:text-gray-400 mb-4">En esta sección, podrá crear nuevos exámenes para los alumnos de manera eficiente y organizada. La plataforma le permitirá seleccionar asignaturas específicas, definir las fechas en las que se llevarán a cabo los exámenes, y agregar detalles adicionales que son cruciales para la correcta administración de las evaluaciones. Podrá especificar el tipo de examen, ya sea parcial, final o trabajo rractico. Además, la sección incluye opciones para incluir indicadores, criterios de evaluación.</p>
                              <p class="text-lg font-normal text-gray-500 dark:text-gray-400 mb-4">Es fundamental que los exámenes sean configurados correctamente para evitar inconvenientes durante el proceso de evaluación. Asegúrese de revisar cada uno de los detalles antes de finalizar la creación del examen. La plataforma también proporciona recordatorios automáticos para ayudar a los docentes a mantenerse al tanto de las fechas y requisitos establecidos. Asimismo, se recomienda comunicarse con los estudiantes sobre cualquier cambio o información relevante respecto a sus evaluaciones para asegurar que todos estén debidamente informados y preparados. <span class="text-red-700 bg-gray-50 ">Recuerde que una buena planificación en la creación de exámenes puede contribuir en gran medida al éxito académico de los alumnos, facilitando un ambiente de aprendizaje más estructurado y efectivo</span>.</p>
                              <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline font-medium text-lg inline-flex items-center">Ir a Detalle del Examen
                                    <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                          <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                    </svg>
                              </a>
                        </div>
                  </div>
                  <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12 mb-8 mt-8">
                        <a href="#" class="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-blue-400 mb-2">
                              <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                                    <path d="M11 0H2a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm8.585 1.189a.994.994 0 0 0-.9-.138l-2.965.983a1 1 0 0 0-.685.949v8a1 1 0 0 0 .675.946l2.965 1.02a1.013 1.013 0 0 0 1.032-.242A1 1 0 0 0 20 12V2a1 1 0 0 0-.415-.811Z" />
                              </svg>
                              Tutorial
                        </a>
                        <h1 class="text-gray-900 dark:text-white text-3xl md:text-5xl font-extrabold mb-2">Registro Asistencia</h1>
                        <p class="text-lg font-normal text-gray-500 dark:text-gray-400 mb-4">En esta sección, podrá registrar automáticamente la asistencia de los alumnos para cada curso, lo que simplifica el proceso. La fecha de asistencia se establece automáticamente, permitiendo a los docentes centrarse en marcar la presencia o ausencia de cada estudiante.</p>
                       
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative text-center mb-4" role="alert">
                              <strong class="font-bold">¡Atención!</strong>
                              <span class="block sm:inline">No olvide registrar la asistencia de los alumnos.</span>
                        </div>
                        <a href="#" class="inline-flex justify-center items-center py-2.5 px-5 text-base font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                              Leer más
                              <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                              </svg>
                        </a>
                  </div>

            </section>
      </div>
</div>


<?php
include "../vistas/footer/footer-docente.php";
?>