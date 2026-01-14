<?php
ob_start(); // Iniciar el almacenamiento en búfer de salida
include "../vistas/header/header-admin.php";
include("../include/database/database.php"); // Incluye el archivo de conexión a la base de datos

// Procesamiento del formulario para insertar/actualizar fechas
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si se envió una fecha válida
    if (isset($_POST['fecha']) && !empty($_POST['fecha'])) {
        try {
            $fecha = DateTime::createFromFormat('d/m/Y', $_POST['fecha']);
            if ($fecha) {
                $fecha_sql = $fecha->format('Y-m-d');
                if (isset($_POST['id_calendario']) && !empty($_POST['id_calendario'])) {
                    // Actualizar la fecha en la tabla 'calendarios'
                    $sql = "UPDATE calendarios SET fechas_calendario = :fecha WHERE id_calendario = :id_calendario";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':fecha', $fecha_sql);
                    $stmt->bindParam(':id_calendario', $_POST['id_calendario']);
                } else {
                    // Insertar la fecha en la tabla 'calendarios'
                    $sql = "INSERT INTO calendarios (fechas_calendario) VALUES (:fecha)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':fecha', $fecha_sql);
                }
                $stmt->execute();

                header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=guardado");
                exit();
            } else {
                header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=error&error=" . urlencode($e->getMessage()));
                exit();
            }
        } catch (PDOException $e) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=error&error=" . urlencode($e->getMessage()));
            exit();
        }
    } else {
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=error&error=" . urlencode($e->getMessage()));
        exit();
    }
}

// Procesar la eliminación
if (isset($_GET['eliminar'])) {
    $id_calendario = $_GET['eliminar'];
    try {
        $sql = "DELETE FROM calendarios WHERE id_calendario = :id_calendario";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id_calendario', $id_calendario);
        $stmt->execute();
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=eliminado");
        exit();
    } catch (PDOException $e) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=error&error=" . urlencode($e->getMessage()));
        exit();
    }
}

// Obtener los datos para editar
$editar = false;
if (isset($_GET['editar'])) {
    $id_calendario = $_GET['editar'];
    $sql = "SELECT id_calendario, fechas_calendario FROM calendarios WHERE id_calendario = :id_calendario";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':id_calendario', $id_calendario);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $editar = true;
        $fecha = DateTime::createFromFormat('Y-m-d', $row['fechas_calendario']);
        $fecha_formateada = $fecha ? $fecha->format('d/m/Y') : $row['fechas_calendario'];
    }
}
?>

<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">

        <h3 class="mb-4 text-4xl font-bold text-blue-800 dark:text-white border-b">
            <?php echo $editar ? 'Editar Fecha del Examen' : 'Crear Fecha de los Examenes'; ?>
        </h3>
        <form class="max-w-md mx-auto" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_calendario" value="<?php echo $editar ? $row['id_calendario'] : ''; ?>">
            <div class="relative z-0 w-full mb-5 group">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z" />
                    </svg>
                </div>
                <input datepicker datepicker-autohide datepicker-format="dd/mm/yyyy" type="text" id="fecha" name="fecha" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Seleccione la fecha de examen" value="<?php echo $editar ? $fecha_formateada : ''; ?>">
            </div>

            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                <?php echo $editar ? 'Guardar Cambios' : 'Guardar Fecha'; ?>
            </button>
            <?php if ($editar) : ?>
                <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 cancelar">Cancelar</a>
            <?php endif; ?>
        </form>
    </div>

    <?php
    // Obtener las fechas de la tabla 'calendarios'
    $sql = "SELECT id_calendario, fechas_calendario FROM calendarios";
    $stmt = $conn->query($sql);
    ?>

    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        <h3 class="mb-4 text-4xl font-bold text-blue-800 dark:text-white border-b"> Fechas de Examenes </h3>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 text-center">
                <thead class="text-xs text-gray-700 uppercase bg-blue-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Fecha de los Examenes
                        </th>
                        <th scope="col-2" class="px-6 py-3">
                            Acción
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($stmt->rowCount() > 0) :
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) :
                            $fecha = DateTime::createFromFormat('Y-m-d', $row['fechas_calendario']);
                            $fecha_formateada = $fecha ? $fecha->format('d/m/Y') : $row['fechas_calendario'];
                    ?>
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <td class="px-6 py-4">
                                    <?php echo $fecha_formateada; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="?editar=<?php echo $row['id_calendario']; ?>" id="updateProductButton" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-80 hover:underline editar" type="button">EDITAR</a>
                                    <a href="?eliminar=<?php echo $row['id_calendario']; ?>" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center  dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 hover:underline eliminar" type="button">ELIMINAR</a>
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