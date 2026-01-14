<?php
include "../vistas/header/header-admin.php";
include("../include/database/database.php");

// Verificar si se envió el formulario para crear o editar una asignatura
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre_asignatura'])) {
    $nombre_asignatura = $_POST['nombre_asignatura'];

    try {
        if (isset($_POST['id_asignatura'])) {
            // Actualizar la asignatura existente
            $id_asignatura = $_POST['id_asignatura'];
            $stmt = $conn->prepare("UPDATE asignaturas SET nombre_asignatura = ? WHERE id_asignatura = ?");
            $stmt->execute([$nombre_asignatura, $id_asignatura]);

            // Redirigir con un mensaje de éxito
            header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=actualizado");
            exit();
        } else {
            // Verificar si la asignatura ya existe
            $stmt_verificar = $conn->prepare("SELECT * FROM asignaturas WHERE nombre_asignatura = ?");
            $stmt_verificar->execute([$nombre_asignatura]);

            if ($stmt_verificar->rowCount() > 0) {
                // Redirigir con un mensaje de error
                header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=existe");
                exit();
            } else {
                // Insertar nueva asignatura
                $stmt = $conn->prepare("INSERT INTO asignaturas (nombre_asignatura) VALUES (?)");
                $stmt->execute([$nombre_asignatura]);

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

// Eliminar asignatura
if (isset($_GET['eliminar'])) {
    $id_asignatura = $_GET['eliminar'];

    try {
        $stmt = $conn->prepare("DELETE FROM asignaturas WHERE id_asignatura = ?");
        $stmt->execute([$id_asignatura]);

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
$asignatura_editar = null;
if (isset($_GET['editar'])) {
    $id_asignatura = $_GET['editar'];

    try {
        $stmt = $conn->prepare("SELECT * FROM asignaturas WHERE id_asignatura = ?");
        $stmt->execute([$id_asignatura]);
        $asignatura_editar = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Redirigir con un mensaje de error
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=error&error=" . urlencode($e->getMessage()));
        exit();
    }
}
?>

<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14 ">


        <h3 class="mb-4 text-4xl font-bold text-blue-800 dark:text-white border-b">
            <?php echo $asignatura_editar ? 'Modificar Asignatura' : 'Crear Asignatura'; ?>

        </h3>
        <form class="max-w-md mx-auto" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <?php if ($asignatura_editar) : ?>
                <input type="hidden" name="id_asignatura" value="<?php echo $asignatura_editar['id_asignatura']; ?>">
            <?php endif; ?>
            <div class="relative z-0 w-full mb-5 group">
                <input type="text" name="nombre_asignatura" id="nombre_asignatura" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required value="<?php echo $asignatura_editar['nombre_asignatura'] ?? ''; ?>" <?php echo $asignatura_editar ? 'autofocus' : ''; ?> />
                <label for="nombre_asignatura" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nombre de la Asignatura</label>
            </div>

            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <?php echo $asignatura_editar ? 'Actualizar Asignatura' : 'Crear Asignatura'; ?>
            </button>
            <?php if ($asignatura_editar) : ?>
                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 cancelar">Cancelar</a>
            <?php endif; ?>
        </form>
    </div>

    <?php
    // Consulta para obtener las asignaturas
    $consulta_asignaturas = $conn->query("SELECT * FROM asignaturas");
    ?>

    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        <h3 class="mb-4 text-4xl font-bold text-blue-800 dark:text-white border-b">Tabla de Asignaturas</h3>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-center text-gray-900 uppercase bg-blue-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">
                            Nombre de la Asignatura
                        </th>
                        <th class="px-6 py-3">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $consulta_asignaturas->fetch(PDO::FETCH_ASSOC)) : ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                            <td class="px-6 py-4">
                                <?php echo $fila["nombre_asignatura"]; ?>
                            </td>
                            <td class="px-6 py-4">
                                <a href="?editar=<?php echo $fila['id_asignatura']; ?>" id="updateProductButton" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center  dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-80 hover:underline editar" type="button">EDITAR</a>
                                <a href="?eliminar=<?php echo $fila['id_asignatura']; ?>" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center  dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 hover:underline eliminar" type="button">ELIMINAR</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include "../vistas/footer/footer-admin.php";
?>