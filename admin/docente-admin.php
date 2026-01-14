<?php
include "../vistas/header/header-admin.php";
include("../include/database/database.php");

// Si se envió el formulario
if (isset($_POST['ci'], $_POST['nombre'], $_POST['apellido'], $_POST['correo'], $_POST['telefono'])) {
    // Obtener datos del formulario
    $ci = $_POST['ci'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $usuario = $_POST['ci'];
    $contrasena = password_hash($_POST['ci'], PASSWORD_DEFAULT); // Hashear la contraseña
    $telefono = $_POST['telefono'];

    try {
        // Si existe un ID, estamos editando
        if (isset($_POST['ci_docente']) && !empty($_POST['ci_docente'])) {
            $id_docente = $_POST['ci_docente'];

            // Preparar la consulta SQL para actualizar datos del docente
            $stmt = $conn->prepare("UPDATE docentes SET ci_docente = ?, nombre_docente = ?, apellido_docente = ?, correo_docente = ?, usuario_docente = ?, contra_docente = ?, telefono_docente = ? WHERE ci_docente = ?");
            $stmt->execute([$ci, $nombre, $apellido, $correo, $usuario, $contrasena, $telefono, $id_docente]);
        } else {
            // Verificar si el CI ya existe en la base de datos
            $stmt_verificar = $conn->prepare("SELECT * FROM docentes WHERE ci_docente = ?");
            $stmt_verificar->execute([$ci]);
            if ($stmt_verificar->rowCount() > 0) {
                // CI ya registrado
                header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=existe");
                exit();
            } else {
                // Preparar la consulta SQL para insertar datos en la tabla Docentes
                $stmt = $conn->prepare("INSERT INTO docentes (ci_docente, nombre_docente, apellido_docente, correo_docente, usuario_docente, contra_docente, telefono_docente) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$ci, $nombre, $apellido, $correo, $usuario, $contrasena, $telefono]);
            }
        }

        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=guardado");
        exit();
    } catch (PDOException $e) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=error&error=" . urlencode($e->getMessage()));
        exit();
    }
}

// Si se ha solicitado eliminar un docente
if (isset($_GET['eliminar'])) {
    $id_docente = $_GET['eliminar'];

    try {
        $stmt = $conn->prepare("DELETE FROM docentes WHERE ci_docente = ?");
        $stmt->execute([$id_docente]);
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=eliminado");
        exit();
    } catch (PDOException $e) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=error&error=" . urlencode($e->getMessage()));
        exit();
    }
}

// Obtener datos de un docente para editar
$docente_editar = null;
if (isset($_GET['editar'])) {
    $id_docente = $_GET['editar'];
    $stmt = $conn->prepare("SELECT * FROM docentes WHERE ci_docente = ?");
    $stmt->execute([$id_docente]);
    $docente_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        <h3 class="mb-4 text-4xl font-bold text-blue-800 dark:text-white border-b"><?php echo $docente_editar ? 'Editar Docente' : 'Crear Docente'; ?>
        </h3>
        <form class="max-w-md mx-auto" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <?php if ($docente_editar) : ?>
                <input type="hidden" name="id_docente" value="<?php echo $docente_editar['ci_docente']; ?>">
            <?php endif; ?>
            <div class="relative z-0 w-full mb-5 group">
                <input type="number" name="ci" id="ci" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?php echo $docente_editar['ci_docente'] ?? ''; ?>" />
                <label for="ci" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Numero de Cedula</label>
            </div>
            <div class="grid md:grid-cols-2 md:gap-6">
                <div class="relative z-0 w-full mb-5 group">
                    <input type="text" name="nombre" id="nombre" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?php echo $docente_editar['nombre_docente'] ?? ''; ?>" />
                    <label for="nombre" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nombre</label>
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <input type="text" name="apellido" id="apellido" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?php echo $docente_editar['apellido_docente'] ?? ''; ?>" />
                    <label for="apellido" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Apellido</label>
                </div>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <input type="email" name="correo" id="correo" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?php echo $docente_editar['correo_docente'] ?? ''; ?>" />
                <label for="correo" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Correo Electrónico</label>
            </div>
            <div class="relative z-0 w-full mb-5 group">
                <div class="relative z-0 w-full mb-5 group">
                    <input type="tel" name="telefono" id="telefono" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?php echo $docente_editar['telefono_docente'] ?? ''; ?>" />
                    <label for="telefono" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Numero de Telefono (123-456-7890)</label>
                </div>
            </div>

            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"><?php echo $docente_editar ? 'Actualizar Docente' : 'Guardar Docente'; ?></button>
            <?php if ($docente_editar) : ?>
                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 cancelar">Cancelar</a>
            <?php endif; ?>
        </form>
    </div>

    <?php
    // Consulta SQL para obtener los datos de la tabla Docentes
    $sql = "SELECT  ci_docente, nombre_docente, apellido_docente, correo_docente, usuario_docente, telefono_docente FROM docentes";
    $result = $conn->query($sql);
    ?>

    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        <h3 class="text-center mb-4 text-4xl font-bold text-blue-800 dark:text-white border-b">Tabla de Docentes
        </h3>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="pb-4 bg-white dark:bg-gray-900">
                <label for="table-search" class="sr-only">Search</label>
                <div class="relative mt-1">
                    <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                        </svg>
                    </div>
                    <input type="text" id="table-search" class="block pt-2 ps-10 text-sm text-gray-900 border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Buscar por numero de Cedula" onkeyup="buscarDocente()">
                </div>
            </div>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr class="text-center">
                        <th scope="col" class="p-4">Cedula de Identidad</th>
                        <th scope="col" class="px-6 py-3">Nombre y Apellido</th>
                        <th scope="col" class="px-6 py-3">Correo Electrónico</th>
                        <th scope="col" class="px-6 py-3">Usuario</th>
                        <th scope="col" class="px-6 py-3">Telefono</th>
                        <th scope="col-2" class="px-6 py-3">Acciones</th>
                    </tr>
                </thead>
                <tbody id="tabla-docentes">
                    <?php if ($result->rowCount() > 0) :
                        // Mostrar datos en la tabla
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) :
                    ?>
                            <tr class="text-center bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"><?php echo $row["ci_docente"]; ?></th>
                                <td class="px-6 py-4"><?php echo $row["nombre_docente"] . " " . $row["apellido_docente"]; ?></td>
                                <td class="px-6 py-4"><?php echo $row["correo_docente"]; ?></td>
                                <td class="px-6 py-4"><?php echo $row["usuario_docente"]; ?></td>
                                <td class="px-6 py-4"><?php echo $row["telefono_docente"]; ?></td>
                                <td class="px-6 py-4">
                                    <a href="?editar=<?php echo $row['ci_docente']; ?>" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-80 hover:underline editar" type="button">EDITAR</a>
                                    <a href="?eliminar=<?php echo $row['ci_docente']; ?>" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 hover:underline eliminar" type="button">ELIMINAR</a>
                                </td>
                            </tr>
                    <?php endwhile;
                    else :
                        echo "<tr><td colspan='6'>No hay docentes registrados</td></tr>";
                    endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
function buscarDocente() {
    // Obtener el valor de la búsqueda
    var input = document.getElementById('table-search');
    var filter = input.value.toUpperCase();
    var table = document.getElementById('tabla-docentes');
    var tr = table.getElementsByTagName('tr');

    // Recorrer todas las filas de la tabla y ocultar las que no coincidan con la búsqueda
    for (var i = 0; i < tr.length; i++) {
        var td = tr[i].getElementsByTagName('th')[0]; // Obtén el primer th (ci_docente)
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