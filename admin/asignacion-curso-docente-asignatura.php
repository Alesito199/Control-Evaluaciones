<?php
include "../vistas/header/header-admin.php";
include("../include/database/database.php"); // Incluye el archivo de conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ci_docente']) && isset($_POST['id_asignacion_curso']) && isset($_POST['id_asignatura'])) {
    try {
        // Obtener los datos del formulario
        $ci_docente = $_POST['ci_docente'];
        $id_asignacion_curso = $_POST['id_asignacion_curso'];
        $id_asignatura = $_POST['id_asignatura'];

        var_dump($_POST); // Para verificar los datos recibidos

        if (isset($_POST['ci_docente_old']) && !empty($_POST['ci_docente_old']) && isset($_POST['id_asignacion_curso_old']) && isset($_POST['id_asignatura_old'])) {
            // Si se proporcionan los identificadores antiguos, actualizar el registro existente
            $ci_docente_old = $_POST['ci_docente_old'];
            $id_asignacion_curso_old = $_POST['id_asignacion_curso_old'];
            $id_asignatura_old = $_POST['id_asignatura_old'];
            
            echo "Entrando en el bloque de actualización"; // Para depuración

            // Preparar la consulta de actualización
            $sql_update = "UPDATE detalle_curso 
                           SET docentes_ci_docente = :ci_docente, asignacion_curso_id_asignacion_curso = :id_asignacion_curso, asignaturas_id_asignatura = :id_asignatura 
                           WHERE docentes_ci_docente = :ci_docente_old AND asignacion_curso_id_asignacion_curso = :id_asignacion_curso_old AND asignaturas_id_asignatura = :id_asignatura_old";
            $stmt = $conn->prepare($sql_update);
            
            // Bind de parámetros
            $stmt->bindParam(':ci_docente', $ci_docente);
            $stmt->bindParam(':id_asignacion_curso', $id_asignacion_curso);
            $stmt->bindParam(':id_asignatura', $id_asignatura);
            $stmt->bindParam(':ci_docente_old', $ci_docente_old);
            $stmt->bindParam(':id_asignacion_curso_old', $id_asignacion_curso_old);
            $stmt->bindParam(':id_asignatura_old', $id_asignatura_old);
            
            // Ejecutar la consulta
            $stmt->execute();

            // Redirigir para mostrar mensaje de éxito
            header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=actualizado");
            exit();
        } else {
            echo "Entrando en el bloque de inserción"; // Para depuración

            // Si no se proporcionan los identificadores antiguos, insertar un nuevo registro
            $sql_insert = "INSERT INTO detalle_curso (docentes_ci_docente, asignacion_curso_id_asignacion_curso, asignaturas_id_asignatura) 
                           VALUES (:ci_docente, :id_asignacion_curso, :id_asignatura)";
            $stmt = $conn->prepare($sql_insert);
            
            // Bind de parámetros
            $stmt->bindParam(':ci_docente', $ci_docente);
            $stmt->bindParam(':id_asignacion_curso', $id_asignacion_curso);
            $stmt->bindParam(':id_asignatura', $id_asignatura);
            
            // Ejecutar la consulta
            $stmt->execute();

            // Redirigir para mostrar mensaje de éxito
            header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=guardado");
            exit();
        }
    } catch (PDOException $e) {
        // Mostrar el mensaje de error si ocurre una excepción
        echo "Error: " . $e->getMessage();
    }
}
// Procesar la solicitud de eliminación
if (isset($_GET['eliminar'])) {
    try {
        $ci_docente = $_GET['ci_docente'];
        $id_asignacion_curso = $_GET['id_asignacion_curso'];
        $id_asignatura = $_GET['id_asignatura'];
        $sql_delete = "DELETE FROM detalle_curso WHERE docentes_ci_docente = :ci_docente AND asignacion_curso_id_asignacion_curso = :id_asignacion_curso AND asignaturas_id_asignatura = :id_asignatura";
        $stmt = $conn->prepare($sql_delete);
        $stmt->bindParam(':ci_docente', $ci_docente);
        $stmt->bindParam(':id_asignacion_curso', $id_asignacion_curso);
        $stmt->bindParam(':id_asignatura', $id_asignatura);
        $stmt->execute();

        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=eliminado");
        exit();
    } catch (PDOException $e) {
        echo "Error al eliminar la asignación: " . $e->getMessage();
    }
}

// Obtener la asignación actual para la edición
$ci_docente_edit = $id_asignacion_curso_edit = $id_asignatura_edit = '';

$editar = false ;

if (isset($_GET['editar']) && $_GET['editar'] == 'true') {
    $editar = true ;
    $ci_docente_edit = $_GET['ci_docente'];
    $id_asignacion_curso_edit = $_GET['id_asignacion_curso'];
    $id_asignatura_edit = $_GET['id_asignatura'];

}
?>
<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-300 border-dashed rounded-lg dark:border-gray-700 mt-14">
        <h3 class="mb-4 text-4xl font-bold text-blue-800 dark:text-white border-b"> <?php echo $editar ? 'Modificar Asignacion de Curso, Docente y Asignatura' : 'Crear Asignacion de Curso, Docente y Asignatura '; ?></h3>
        <form class="border border-dashed p-8" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <?php if (isset($ci_docente_edit) && isset($id_asignacion_curso_edit) && isset($id_asignatura_edit)): ?>
                <input type="hidden" name="ci_docente_old" value="<?php echo $ci_docente_edit; ?>">
                <input type="hidden" name="id_asignacion_curso_old" value="<?php echo $id_asignacion_curso_edit; ?>">
                <input type="hidden" name="id_asignatura_old" value="<?php echo $id_asignatura_edit; ?>">
            <?php endif; ?>
            <div class="relative z-0 w-full mb-5 group">
                <?php $result_docentes = $conn->query('SELECT * FROM docentes'); ?>
                <label for="ci_docente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleccione el Docente</label>
                <select id="ci_docente" name="ci_docente" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>Seleccione un docente</option>
                    <?php if ($result_docentes->rowCount() > 0) :
                        while ($row = $result_docentes->fetch(PDO::FETCH_ASSOC)) : ?>
                            <option value="<?php echo $row['ci_docente']; ?>" <?php echo ($row['ci_docente'] == $ci_docente_edit) ? 'selected' : ''; ?>><?php echo $row['nombre_docente'] .  " " . $row['apellido_docente']; ?></option>
                    <?php endwhile;
                    endif; ?>
                </select>
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <?php $sql_asignacion_curso = "SELECT * FROM asignacion_curso";
                $result_asignacion_curso = $conn->query($sql_asignacion_curso); ?>
                <label for="id_asignacion_curso" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleccione el Semestre</label>
                <select id="id_asignacion_curso" name="id_asignacion_curso" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>Seleccione un semestre</option>
                    <?php if ($result_asignacion_curso->rowCount() > 0) :
                        while ($row = $result_asignacion_curso->fetch(PDO::FETCH_ASSOC)) : ?>
                            <option value="<?php echo $row['id_asignacion_curso']; ?>" <?php echo ($row['id_asignacion_curso'] == $id_asignacion_curso_edit) ? 'selected' : ''; ?>><?php echo $row['semestre']; ?></option>
                    <?php endwhile;
                    endif; ?>
                </select>
            </div>

            <div class="relative z-0 w-full mb-5 group">
                <?php $sql_asignaturas = "SELECT * FROM asignaturas";
                $result_asignaturas = $conn->query($sql_asignaturas); ?>
                <label for="id_asignatura" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleccione la Asignatura</label>
                <select id="id_asignatura" name="id_asignatura" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>Seleccione una asignatura</option>
                    <?php if ($result_asignaturas->rowCount() > 0) :
                        while ($row = $result_asignaturas->fetch(PDO::FETCH_ASSOC)) : ?>
                            <option value="<?php echo $row['id_asignatura']; ?>" <?php echo ($row['id_asignatura'] == $id_asignatura_edit) ? 'selected' : ''; ?>><?php echo $row['nombre_asignatura']; ?></option>
                    <?php endwhile;
                    endif; ?>
                </select>
            </div>
            <button type="submit" name="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"><?php echo $editar ? 'Actualizar' : 'Guardar'; ?></button>
        </form>

  
    <?php
    // Mostrar la tabla de detalle_curso
    $sql_detalle_curso = "SELECT detalle_curso.*, docentes.nombre_docente, docentes.apellido_docente, asignacion_curso.anho, asignacion_curso.turno, asignaturas.nombre_asignatura 
                          FROM detalle_curso 
                          INNER JOIN docentes ON detalle_curso.docentes_ci_docente = docentes.ci_docente 
                          INNER JOIN asignacion_curso ON detalle_curso.asignacion_curso_id_asignacion_curso = asignacion_curso.id_asignacion_curso 
                          INNER JOIN asignaturas ON detalle_curso.asignaturas_id_asignatura = asignaturas.id_asignatura";
    $result_detalle_curso = $conn->query($sql_detalle_curso);
    ?>

    <div class="p-4 border-2 border-gray-300 border-dashed rounded-lg dark:border-gray-700 mt-14">
        <h3 class="text-center mb-4 text-4xl font-bold text-blue-800 dark:text-white border-b">Tabla de Asignaciones de Curso, Docente y Asignatura.</h3>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">

            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 text-center">
                <thead class="text-xs text-gray-700 uppercase bg-blue-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Docente
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Año
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Turno
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nombre de la Asignatura
                        </th>
                        <th scope="col-2" class="px-6 py-3">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_detalle_curso->rowCount() > 0) :
                        while ($row = $result_detalle_curso->fetch(PDO::FETCH_ASSOC)) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4">
                                    <?php echo $row["nombre_docente"] . " " . $row["apellido_docente"]; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php echo $row["anho"]; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php echo $row["turno"]; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php echo $row["nombre_asignatura"]; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?editar=true&ci_docente=' . $row['docentes_ci_docente'] . '&id_asignacion_curso=' . $row['asignacion_curso_id_asignacion_curso'] . '&id_asignatura=' . $row['asignaturas_id_asignatura']; ?>" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-80 hover:underline editar">EDITAR</a>
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?eliminar=true&ci_docente=' . $row['docentes_ci_docente'] . '&id_asignacion_curso=' . $row['asignacion_curso_id_asignacion_curso'] . '&id_asignatura=' . $row['asignaturas_id_asignatura']; ?>" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 hover:underline eliminar">ELIMINAR</a>
                                </td>
                            </tr>
                    <?php endwhile;
                    endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include "../vistas/footer/footer-admin.php";
?>