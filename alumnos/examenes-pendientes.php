<?php
include "../vistas/header/header-alumno.php";
// Mostrar mensaje si existe
if (isset($_SESSION['mensaje'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['mensaje'] . "</div>";
    unset($_SESSION['mensaje']); // Eliminar el mensaje después de mostrarlo
}
?>

<?php
$consulta_examenes = $conn->query("
SELECT 
    a.ci_alumno,
    e.id_examen, 
    e.tipo_examen, 
    e.puntaje_examen, 
    e.parcial_examenes
FROM 
    alumnos a
JOIN 
    examenes e
ON 
    a.asignacion_curso_id_asignacion_curso = e.detalle_curso_asignacion_curso_id_asignacion_curso
WHERE 
    a.ci_alumno = $ci
");
?>

<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <!-- titulo -->
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 ">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Tipo de Examen
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            Puntaje del Examen
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            Parcial del Examen
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Estado del Examen
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            Accion
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $consulta_examenes->fetch(PDO::FETCH_ASSOC)) : ?>
                        <?php
                        // Verificar si existe un formulario para el examen actual
                        $consulta_formulario = $conn->prepare("SELECT id_formulario FROM formularios WHERE examenes_id_examen = :id_examen");
                        $consulta_formulario->bindParam(':id_examen', $fila['id_examen'], PDO::PARAM_INT);
                        $consulta_formulario->execute();
                        $formulario = $consulta_formulario->fetch(PDO::FETCH_ASSOC);

                        // Verificar si existen preguntas para el formulario
                        $formulario_valido = false;
                        if ($formulario) {
                            $consulta_preguntas = $conn->prepare("SELECT id_pregunta FROM preguntas WHERE formularios_id_formulario = :id_formulario");
                            $consulta_preguntas->bindParam(':id_formulario', $formulario['id_formulario'], PDO::PARAM_INT);
                            $consulta_preguntas->execute();
                            $preguntas = $consulta_preguntas->fetchAll(PDO::FETCH_ASSOC);
                            if (count($preguntas) > 0) {
                                $formulario_valido = true;
                            }
                        }
                        ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                            <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                <?php echo $fila['tipo_examen']; ?>
                            </th>
                            <td class="px-6 py-4 text-center"><?php echo $fila['puntaje_examen']; ?></td>
                            <td class="px-6 py-4 text-center"><?php echo $fila['parcial_examenes']; ?></td>

                            <td class="px-6 py-4">
                                <?php if ($formulario_valido) : ?>
                                    <div class="flex items-center">
                                        <div class="h-2.5 w-2.5 rounded-full bg-green-500 me-2"></div> Activo
                                    </div>
                                <?php else : ?>
                                    <div class="flex items-center">
                                        <div class="h-2.5 w-2.5 rounded-full bg-red-500 me-2"></div> Inactivo
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <?php if ($formulario_valido) : ?>
                                    <a href="rendir-examen.php?id_examen=<?php echo htmlspecialchars($fila['id_examen']); ?>" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800 rendir">
                                        Rendir Examen
                                        <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                                        </svg>
                                    </a>
                                <?php else : ?>
                                    <button type="button" class="text-white bg-red-500 dark:bg-red-500 cursor-not-allowed font-medium rounded-lg text-sm px-5 py-2.5 text-center" disabled>
                                        Examen Deshabilitado
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

    </div>
</div>




<?php
include "../vistas/footer/footer-alumno.php";
?>