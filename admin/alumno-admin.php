<?php
include "../vistas/header/header-admin.php";
include("../include/database/database.php"); // Incluye el archivo de conexión a la base de datos

// Verificar si se envió el formulario para crear o editar un alumno
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ci'])) {

    $ci = $_POST['ci'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $usuario = $_POST['ci'];
    $contraseña = $_POST['ci'];
    $telefono = $_POST['telefono'];
    $id_asignacion_curso = $_POST['id_asignacion_curso'];

    // Hashear la contraseña
    $hashed_password = password_hash($contraseña, PASSWORD_DEFAULT);

    try {
        if (isset($_POST['ci_alumno'])) {
            // Actualizar el alumno existente
            $id_alumno = $_POST['id_alumno'];
            $stmt = $conn->prepare("UPDATE alumnos SET  nombre_alumno = ?, apellido_alumno = ?, correo_alumno = ?, usuario_alumno = ?, contra_alumno = ?, telefono_alumno = ?, asignacion_curso_id_asignacion_curso = ? WHERE ci_alumno = ?");
            $stmt->execute([$nombre, $apellido, $correo, $usuario, $hashed_password, $telefono, $id_asignacion_curso, $id_alumno]);

            // Redirigir con un mensaje de éxito
            header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=actualizado");
            exit();
        } else {
            // Verificar si el CI ya existe en la base de datos
            $stmt_verificar = $conn->prepare("SELECT * FROM alumnos WHERE ci_alumno = ?");
            $stmt_verificar->execute([$ci]);

            if ($stmt_verificar->rowCount() > 0) {
                // Redirigir con un mensaje de error
                header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=existe");
                exit();
            } else {
                // Insertar nuevos datos en la base de datos
                $stmt_insertar = $conn->prepare("INSERT INTO alumnos (ci_alumno, nombre_alumno, apellido_alumno, correo_alumno, usuario_alumno, contra_alumno, telefono_alumno, asignacion_curso_id_asignacion_curso) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt_insertar->execute([$ci, $nombre, $apellido, $correo, $usuario, $hashed_password, $telefono, $id_asignacion_curso]);

                // Redirigir con un mensaje de éxito
                header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=guardado");
                exit();
            }
        }
    } catch (PDOException $e) {
        // Redirigir con un mensaje de error
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=error&error=" . urlencode($e->getMessage()));
        exit();
    }
}

// Eliminar alumno
if (isset($_GET['eliminar'])) {
    $ci_alumno = $_GET['eliminar'];

    try {
        $stmt = $conn->prepare("DELETE FROM alumnos WHERE ci_alumno = ?");
        $stmt->execute([$ci_alumno]);

        // Redirigir con un mensaje de éxito
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=eliminado");
        exit();
    } catch (PDOException $e) {
        // Redirigir con un mensaje de error
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=error&error=" . urlencode($e->getMessage()));
        exit();
    }
}

// Obtener datos para editar
$alumno_editar = null;
if (isset($_GET['editar'])) {
    $ci_alumno = $_GET['editar'];

    try {
        $stmt = $conn->prepare("SELECT * FROM alumnos WHERE ci_alumno = ?");
        $stmt->execute([$ci_alumno]);
        $alumno_editar = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Redirigir con un mensaje de error
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=error&error=" . urlencode($e->getMessage()));
        exit();
    }
}

// Consulta SQL para obtener las asignaciones de curso disponibles
$sql_asignaciones = "SELECT id_asignacion_curso, CONCAT(anho, ' - ', semestre,' - ', turno) AS curso_semestre FROM asignacion_curso";
$stmt_asignaciones = $conn->query($sql_asignaciones);
$asignaciones = $stmt_asignaciones->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-300 border-dashed rounded-lg dark:border-gray-700 mt-14">

        <h3 class="mb-4 text-4xl font-bold text-blue-800 dark:text-white border-b">
            <?php echo $alumno_editar ? 'Modificar Alumno' : 'Crear Alumno'; ?>

        </h3>
        <form class="max-w-md mx-auto" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <?php if ($alumno_editar) : ?>
                <input type="hidden" name="id_alumno" value="<?php echo $alumno_editar['ci_alumno']; ?>">
            <?php endif; ?>
            <div class="relative z-0 w-full mb-5 group">
                <input type="number" name="ci" id="ci" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?php echo $alumno_editar['ci_alumno'] ?? ''; ?>" />
                <label for="ci" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Numero de Cedula</label>
            </div>
            <div class="grid md:grid-cols-2 md:gap-6">
                <div class="relative z-0 w-full mb-5 group">
                    <input type="text" name="nombre" id="nombre" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?php echo $alumno_editar['nombre_alumno'] ?? ''; ?>" />
                    <label for="nombre" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nombre</label>
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <input type="text" name="apellido" id="apellido" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?php echo $alumno_editar['apellido_alumno'] ?? ''; ?>" />
                    <label for="apellido" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Apellido</label>
                </div>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <input type="email" name="correo" id="correo" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?php echo $alumno_editar['correo_alumno'] ?? ''; ?>" />
                <label for="correo" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Correo</label>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="telefono" id="telefono" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?php echo $alumno_editar['telefono_alumno'] ?? ''; ?>" />
                <label for="telefono" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Telefono</label>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <label for="id_asignacion_curso" class="sr-only">Asignación de Curso</label>
                <select name="id_asignacion_curso" id="id_asignacion_curso" class="block py-2.5 px-0 w-full text-sm text-gray-500 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" required>
                    <option value="" disabled selected>Seleccione una asignación de curso</option>
                    <?php foreach ($asignaciones as $asignacion) : ?>
                        <option value="<?php echo $asignacion['id_asignacion_curso']; ?>" <?php echo isset($alumno_editar['asignacion_curso_id_asignacion_curso']) && $alumno_editar['asignacion_curso_id_asignacion_curso'] == $asignacion['id_asignacion_curso'] ? 'selected' : ''; ?>>
                            <?php echo $asignacion['curso_semestre']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Guardar</button>
            <?php if ($alumno_editar) : ?>
                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 cancelar">Cancelar</a>
            <?php endif; ?>
        </form>
        <br>
    </div>
    <?php
    // Preparar la consulta SQL para obtener los datos de la tabla Alumnos
    $stmt = $conn->query("SELECT alumnos.*, asignacion_curso.anho, asignacion_curso.turno, asignacion_curso.semestre FROM alumnos INNER JOIN asignacion_curso ON alumnos.asignacion_curso_id_asignacion_curso = asignacion_curso.id_asignacion_curso");
    ?>

    <div class="p-4 border-2 border-gray-300 border-dashed rounded-lg dark:border-gray-700 mt-14">
        
    <h3 class="mb-4 text-4xl font-bold text-blue-800 dark:text-white border-b"> Tabla de Alumnos </h3>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="pb-4 bg-white dark:bg-gray-900">
                <label for="table-search" class="sr-only">Search</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="text" id="table-search" class="block pt-2 ps-10 text-sm text-gray-900 border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar por numero de Cedula" onkeyup="buscarAlumno()">
                </div>
            </div>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 text-center">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Cedula de Identidad
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Nombre y Apellido
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Correo
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Usuario
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Telefono
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Año/Semestre/Turno
                        </th>
                        <th scope="col-2" class="px-6 py-3">
                            Accion
                        </th>
                    </tr>
                </thead>
                <tbody id="tabla-alumnos">
                    <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                <?php echo $row['ci_alumno']; ?>
                            </th>
                            <td class="px-6 py-4">
                                <?php echo $row['nombre_alumno'] . "  " . $row['apellido_alumno']; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo $row['correo_alumno']; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo $row['usuario_alumno']; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo $row['telefono_alumno']; ?>
                            </td>
                            <td class="px-6 py-4">
                                <?php echo $row['anho'] . " - " . $row['semestre'] . " - " . $row['turno']; ?>
                            </td>
                            <td class="px-6 py-4">
                                <a href="?editar=<?php echo $row['ci_alumno']; ?>" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-80 hover:underline editar" type="button">EDITAR</a>
                                <a href="?eliminar=<?php echo $row['ci_alumno']; ?>" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 hover:underline eliminar" type="button">ELIMINAR</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
    <script>
        function buscarAlumno() {
            // Obtener el valor de la búsqueda
            var input = document.getElementById('table-search');
            var filter = input.value.toUpperCase();
            var table = document.getElementById('tabla-alumnos');
            var tr = table.getElementsByTagName('tr');

            // Recorrer todas las filas de la tabla y ocultar las que no coincidan con la búsqueda
            for (var i = 0; i < tr.length; i++) {
                var td = tr[i].getElementsByTagName('th')[0]; // Obtén el primer th (ci_alumno)
                if (td) {
                    var txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = '';
                    } else {
                        tr[i].style.display = 'none';
                    }
                }
            }
        }
    </script>

    <?php
    include "../vistas/footer/footer-admin.php";
    ?>