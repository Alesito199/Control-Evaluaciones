<?php
ob_start(); // Iniciar el almacenamiento en búfer de salida
include "../vistas/header/header-admin.php";
include("../include/database/database.php");

// Definir las variables $esfuncionario y $id antes de usarlas
$id_funcionario = isset($id_funcionario) ? $id_funcionario : null;
$esFuncionario = isset($esFuncionario) ? $id_funcionario : null;
$id = isset($id) ? $id : null; // Asignar un valor predeterminado

$errorMessage = ''; // Variable para almacenar el mensaje de error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $anho = $_POST['año'];
        $semestre = $_POST['semestre'];
        $turno = $_POST['turno'];
        $id_asignacion_curso = isset($_POST['id_asignacion_curso']) ? $_POST['id_asignacion_curso'] : null;

        if ($id_curso) {
            // Actualizar datos en la base de datos
            $sql_update = "UPDATE asignacion_curso SET anho = :anho, semestre = :semestre, turno = :turno WHERE id_asignacion_curso = :id_asignacion_curso";
            $stmt = $conn->prepare($sql_update);
            $stmt->bindParam(':anho', $anho);
            $stmt->bindParam(':semestre', $semestre);
            $stmt->bindParam(':turno', $turno);
            $stmt->bindParam(':id_asignacion_curso', $id_asignacion_curso);
            $stmt->execute();
            header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=actualizado");
            exit();
        } else {
            // Verificar si ya existe la combinación de año, semestre y turno
            $sql_verificar = "SELECT * FROM asignacion_curso WHERE anho = :anho AND semestre = :semestre AND turno = :turno";
            $stmt_verificar = $conn->prepare($sql_verificar);
            $stmt_verificar->bindParam(':anho', $anho);
            $stmt_verificar->bindParam(':semestre', $semestre);
            $stmt_verificar->bindParam(':turno', $turno);
            $stmt_verificar->execute();

            if ($stmt_verificar->rowCount() > 0) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=existe");
                exit();
            } else {
                // Insertar nuevos datos en la base de datos
                $sql_insert = "INSERT INTO asignacion_curso (anho, semestre, turno, secretarios_id_secretario, funcionarios_id_funcionario) VALUES (:anho, :semestre, :turno, :usuario, :funcionario)";
                $stmt = $conn->prepare($sql_insert);
                $stmt->bindParam(':anho', $anho);
                $stmt->bindParam(':semestre', $semestre);
                $stmt->bindParam(':turno', $turno);
                $stmt->bindParam(':usuario', $id);
                $stmt->bindParam(':funcionario', $esFuncionario);
                $stmt->execute();
                header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=guardado");
                exit();
            }
        }
    } catch (PDOException $e) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=error&error=" . urlencode($e->getMessage()));
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['eliminar'])) {
    try {
        $id_asignacion_curso = $_GET['eliminar'];
        $sql_delete = "DELETE FROM asignacion_curso WHERE id_asignacion_curso = :id_asignacion_curso";
        $stmt = $conn->prepare($sql_delete);
        $stmt->bindParam(':id_asignacion_curso', $id_asignacion_curso);
        $stmt->execute();
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=eliminado");
        exit();
    } catch (PDOException $e) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=error&error=" . urlencode($e->getMessage()));
        exit();
    }
}

if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
    if ($mensaje == "existe") {
        $errorMessage = "Ya existe una asignación de curso con el mismo año, semestre y turno.";
    } elseif ($mensaje == "error" && isset($_GET['error'])) {
        $errorMessage = urldecode($_GET['error']);
    }
}

$id_curso_editar = isset($_GET['editar']) ? $_GET['editar'] : null;
$curso_editar = null;
if ($id_curso_editar) {
    $sql_editar = "SELECT * FROM asignacion_curso WHERE id_asignacion_curso = :id_asignacion_curso";
    $stmt_editar = $conn->prepare($sql_editar);
    $stmt_editar->bindParam(':id_asignacion_curso', $id_curso_editar);
    $stmt_editar->execute();
    $curso_editar = $stmt_editar->fetch(PDO::FETCH_ASSOC);
}
ob_end_flush(); // Enviar la salida almacenada en el búfer y desactivar el almacenamiento en búfer
?>

<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">

        <h3 class="mb-4 text-4xl font-bold text-blue-800 dark:text-white border-b">
        <?php echo $curso_editar ? 'Editar el Curso, Semestre y Turno' : 'Crear Curso, Semestre y Turno'; ?></h3>
        <form class="max-w-sm mx-auto" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
            <?php if ($curso_editar) : ?>
                <input type="hidden" name="id_curso" value="<?php echo $curso_editar['id_asignacion_curso']; ?>">
            <?php endif; ?>
            <div class="relative z-0 w-full mb-5 group">
                <label for="año" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleccione el Año</label>
                <select id="año" name="año" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>Seleccione el Año</option>
                    <option value="Primer Año" <?php echo isset($curso_editar['anho']) && $curso_editar['anho'] == 'Primer Año' ? 'selected' : ''; ?>>Primer Año</option>
                    <option value="Segundo Año" <?php echo isset($curso_editar['anho']) && $curso_editar['anho'] == 'Segundo Año' ? 'selected' : ''; ?>>Segundo Año</option>
                    <option value="Tercer Año" <?php echo isset($curso_editar['anho']) && $curso_editar['anho'] == 'Tercer Año' ? 'selected' : ''; ?>>Tercer Año</option>
                    <option value="Cuarto Año" <?php echo isset($curso_editar['anho']) && $curso_editar['anho'] == 'Cuarto Año' ? 'selected' : ''; ?>>Cuarto Año</option>
                    <option value="Quinto Año" <?php echo isset($curso_editar['anho']) && $curso_editar['anho'] == 'Quinto Año' ? 'selected' : ''; ?>>Quinto Año</option>
                </select>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <label for="semestre" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Seleccione el Semestre</label>
                <select id="semestre" name="semestre" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option selected>Seleccione el Semestre</option>
                    <?php for ($i = 1; $i <= 10; $i++) : ?>
                        <option value="<?php echo "$i º Semestre"; ?>" <?php echo isset($curso_editar['semestre']) && $curso_editar['semestre'] == "$i º Semestre" ? 'selected' : ''; ?>><?php echo $i; ?>º Semestre</option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <h3 class="mb-4 font-semibold text-gray-900 dark:text-white">Turno</h3>
                <ul class="items-center w-full text-sm font-medium text-gray-900 bg-white border border-gray-200 rounded-lg sm:flex dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="horizontal-list-radio-license" type="radio" value="Mañana" name="turno" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" <?php echo isset($curso_editar['turno']) && $curso_editar['turno'] == 'Mañana' ? 'checked' : ''; ?>>
                            <label for="horizontal-list-radio-license" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Mañana</label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="horizontal-list-radio-id" type="radio" value="Tarde" name="turno" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" <?php echo isset($curso_editar['turno']) && $curso_editar['turno'] == 'Tarde' ? 'checked' : ''; ?>>
                            <label for="horizontal-list-radio-id" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Tarde</label>
                        </div>
                    </li>
                    <li class="w-full border-b border-gray-200 sm:border-b-0 sm:border-r dark:border-gray-600">
                        <div class="flex items-center ps-3">
                            <input id="horizontal-list-radio-military" type="radio" value="Noche" name="turno" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500" <?php echo isset($curso_editar['turno']) && $curso_editar['turno'] == 'Noche' ? 'checked' : ''; ?>>
                            <label for="horizontal-list-radio-military" class="w-full py-3 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Noche</label>
                        </div>
                    </li>
                </ul>
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"><?php echo $curso_editar ? 'Actualizar Curso' : 'Crear Curso'; ?></button>
            <?php if ($curso_editar) : ?>
                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 cancelar">Cancelar</a>
            <?php endif; ?>
        </form>
    </div>
    <?php
    $sql = "SELECT * FROM asignacion_curso";
    $result = $conn->query($sql);
    ?>

    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
            <h3 class="border-b mb-4 text-4xl font-bold text-blue-800 dark:text-white text-center">Tabla de Cursos, Semestres y Turnos Creados</h3>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 text-center">
                <thead class="text-xs text-gray-700 uppercase bg-blue-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Año
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Semestre
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Turno
                        </th>
                        <th scope="col-2" class="px-6 py-3">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->rowCount() > 0) :
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) : ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4">
                                    <?php echo $row["anho"]; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php echo $row["semestre"]; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <?php echo $row["turno"]; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?editar=' . $row['id_asignacion_curso']; ?>" id="updateProductButton" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800 hover:underline editar" type="button">EDITAR</a>
                                    <a href="<?php echo $_SERVER['PHP_SELF'] . '?eliminar=' . $row['id_asignacion_curso']; ?>" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 hover:underline eliminar" type="button">ELIMINAR</a>
                                </td>
                            </tr>
                    <?php endwhile;
                    else :
                        echo "No se encontraron resultados.";
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include "../vistas/footer/footer-admin.php";
?>
