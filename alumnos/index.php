<?php
include "../vistas/header/header-alumno.php";
$id_alumno = $ci;
// Consultar los exámenes pendientes que aún no se rindieron
$query_examenes_pendientes = $conn->prepare("
    SELECT 
        e.id_examen, e.tipo_examen, e.parcial_examenes, 
        cur.anho, cur.semestre, cur.turno, 
        asig.nombre_asignatura, 
        c.fechas_calendario 
    FROM examenes e
    JOIN calendarios c ON e.calendarios_id_calendario = c.id_calendario
    JOIN asignaturas asig ON e.detalle_curso_asignaturas_id_asignatura = asig.id_asignatura
    JOIN asignacion_curso cur ON e.detalle_curso_asignacion_curso_id_asignacion_curso = cur.id_asignacion_curso
    WHERE c.fechas_calendario >= CURDATE()
    ORDER BY c.fechas_calendario ASC
    LIMIT 4
");
$query_examenes_pendientes->execute();
$result_examenes_pendientes = $query_examenes_pendientes->fetchAll();
// Consultar las asistencias del alumno
$query_asistencias = $conn->prepare("
    SELECT 
        a.fecha_asistencia, a.presente_asistencia
    FROM registro_asistencia a
    JOIN asignacion_curso c ON a.detalle_curso_asignacion_curso_id_asignacion_curso = c.id_asignacion_curso
    WHERE a.alumnos_ci_alumno = :id_alumno
    ORDER BY a.fecha_asistencia ASC
    LIMIT 5
");
$query_asistencias->execute(['id_alumno' => $id_alumno]);
$result_asistencias = $query_asistencias->fetchAll();

// Consultar las puntuaciones de los exámenes del alumno
$query_puntuaciones = $conn->prepare("
    SELECT 
        e.parcial_examenes AS nombre_examen, 
        p.puntuacion, 
        p.errores, 
        c.fechas_calendario 
    FROM puntuaciones p
    JOIN examenes e ON p.id_examen = e.id_examen
    JOIN calendarios c ON e.calendarios_id_calendario = c.id_calendario
    WHERE p.ci_alumno = :id_alumno
    ORDER BY c.fechas_calendario ASC
");
$query_puntuaciones->execute(['id_alumno' => $id_alumno]);
$result_puntuaciones = $query_puntuaciones->fetchAll();
?>

<div class="p-4 sm:ml-64">
      <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
            <section class="bg-white dark:bg-gray-900">
                  <div class="grid md:grid-cols-2 gap-8">
                        <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12">
                              <h2 class="text-blue-700 dark:text-white text-3xl font-extrabold mb-2 border-b">
                                    <?php if (count($result_examenes_pendientes) > 0) : ?>
                                          Exámenes Pendientes
                                    <?php else : ?>
                                          No hay Exámenes Pendientes
                                    <?php endif; ?>
                              </h2>
                              <ol class="relative border-l border-gray-200 dark:border-gray-700">
                                    <?php if (count($result_examenes_pendientes) > 0) : ?>
                                          <?php foreach ($result_examenes_pendientes as $examen) : ?>
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
                                          <li class="mb-10 ms-4">
                                                <div class="absolute w-3 h-3 bg-blue-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                                <time class="mb-1 text-sm font-normal leading-none text-blue-400 dark:text-blue-500">No hay exámenes pendientes disponibles</time>
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Ningún examen pendiente</h3>
                                                <p class="mb-4 text-base font-normal text-gray-500 dark:text-gray-400">Por favor, espere nuevos exámenes o contacte con el administrador.</p>
                                          </li>
                                    <?php endif; ?>
                              </ol>
                              <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative text-center" role="alert">
                                    <strong class="font-bold">¡Atención!</strong>
                                    <span class="block sm:inline">No olvide revisar los exámenes pendientes.</span>
                              </div>
                        </div>
                        <div class=" bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12 overflow-y-auto">
                              <h2 class="text-blue-700 dark:text-white text-3xl font-extrabold mb-2 border-b">
                                    <?php if (count($result_asistencias) > 0) : ?>
                                          Registro de Asistencia
                                    <?php else : ?>
                                          No hay Registro de Asistencia
                                    <?php endif; ?>
                              </h2>
                              <ol class=" border-l border-gray-200 dark:border-gray-700">
                                    <?php if (count($result_asistencias) > 0) : ?>
                                          <?php foreach ($result_asistencias as $asistencia) : ?>
                                                <li class="mb-10 ms-4">
                                                      <div class="absolute w-3 h-3 bg-blue-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                                      <time class="mb-1 text-sm font-normal leading-none text-blue-400 dark:text-blue-500">Fecha: <?php echo htmlspecialchars($asistencia['fecha_asistencia']); ?></time>
                                                      <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                            Estado: <?php echo htmlspecialchars($asistencia['presente_asistencia'] == 1 ? 'Presente' : 'Ausente'); ?>
                                                      </h3>
                                                </li>
                                          <?php endforeach; ?>
                                    <?php else : ?>
                                          <li class="mb-10 ms-4">
                                                <div class="absolute w-3 h-3 bg-blue-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                                <time class="mb-1 text-sm font-normal leading-none text-blue-400 dark:text-blue-500">No hay registro de asistencia disponible</time>
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">El docente aún no ha registrado la asistencia del curso</h3>
                                          </li>
                                    <?php endif; ?>
                                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-center" role="alert">
                                          <strong class="font-bold">¡Recordatorio!</strong>
                                          <span class="block sm:inline">Estas son las ultimas cinco asistencia registradas por el docente para ver la cantidad toda vaya a la pestanha de Registro Asistencia.</span>
                                    </div>
                              </ol>
                        </div>
                  </div>
                  <div class=" bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12 overflow-y-auto mt-8">
                        <h2 class="text-blue-700 dark:text-white text-3xl font-extrabold mb-2 border-b">
                              <?php if (count($result_puntuaciones) > 0) : ?>
                                    Puntuaciones de Exámenes
                              <?php else : ?>
                                    No hay Puntuaciones Registradas
                              <?php endif; ?>
                        </h2>

                        <ol class="relative border-l border-gray-200 dark:border-gray-700">
                              <?php if (count($result_puntuaciones) > 0) : ?>
                                    <?php foreach ($result_puntuaciones as $puntuacion) : ?>
                                          <li class="mb-10 ms-4">
                                                <div class="absolute w-3 h-3 bg-blue-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                                <time class="mb-1 text-sm font-normal leading-none text-blue-400 dark:text-blue-500">Fecha: <?php echo htmlspecialchars($puntuacion['fechas_calendarios']); ?></time>
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                                      Examen: <?php echo htmlspecialchars($puntuacion['nombre_examen']); ?> - Puntuación: <?php echo htmlspecialchars($puntuacion['puntuacion']); ?> - Errores: <?php echo htmlspecialchars($puntuacion['errores']); ?>
                                                </h3>
                                          </li>
                                    <?php endforeach; ?>
                              <?php else : ?>
                                    <li class="mb-10 ms-4">
                                          <div class="absolute w-3 h-3 bg-blue-200 rounded-full mt-1.5 -start-1.5 border border-white dark:border-gray-900 dark:bg-gray-700"></div>
                                          <time class="mb-1 text-sm font-normal leading-none text-blue-400 dark:text-blue-500"> No hay puntuaciones de exámenes disponibles</time>
                                          <h3 class="text-lg font-semibold text-gray-900 dark:text-white">El alumno aún no tiene puntuaciones registradas</h3>
                                    </li>
                              <?php endif; ?>
                              <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative text-center" role="alert">
                                    <strong class="font-bold">¡Atención!</strong>
                                    <span class="block sm:inline">No olvide revisar los exámenes con el docente ante cualquier duda.</span>
                              </div>
                        </ol>
                  </div>
            </section>
      </div>
</div>

<?php
include "../vistas/footer/footer-alumno.php";
?>