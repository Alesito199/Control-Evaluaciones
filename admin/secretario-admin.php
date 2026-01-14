<?php
include "../vistas/header/header-admin.php";
include("../include/database/database.php");

// Variable para almacenar el mensaje de estado actualizado
$mensaje = "";

// Verificar si se ha enviado el formulario para crear un nuevo usuario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['usuario'];
    $estado = $_POST['estado'];
    $id_funcionario = $id_funcionario; // Asegurar que $id_funcionario está definido

    // Hashear la contraseña
    $contrasena_hashed = password_hash($contrasena, PASSWORD_DEFAULT);

    try {
        // Verificar si el usuario ya existe en la base de datos
        $stmt_verificar = $conn->prepare("SELECT * FROM secretarios WHERE secretario_usuario = ?");
        $stmt_verificar->execute([$usuario]);
        if ($stmt_verificar->rowCount() > 0) {
            // Usuario ya registrado
            header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=existe");
            exit();
        } else {
            // Insertar nuevo usuario en la base de datos
            $stmt_insertar = $conn->prepare("INSERT INTO secretarios (nombre_secretario, apellido_secretario, secretario_usuario, secretario_contra, estado_secretario, funcionarios_id_funcionario) 
                                             VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_insertar->execute([$nombre, $apellido, $usuario, $contrasena_hashed, $estado, $id_funcionario]);
            // Redirigir después de la inserción para evitar el re-envío del formulario
            header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=guardado");
            exit();
        }
    } catch (PDOException $e) {
        // Manejo de la excepción en caso de error en la conexión
        header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=error&error=" . urlencode($e->getMessage()));
        exit();
    }
}

// Verificar si se solicitó habilitar o deshabilitar un usuario
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id_usuario = $_GET['id'];
    if ($_GET['action'] == 'habilitar') {
        // Actualizar el estado del usuario a activo
        $actualizar_usuario = $conn->prepare("UPDATE secretarios SET estado_secretario = 1 WHERE id_secretario = :id_usuario");
    } elseif ($_GET['action'] == 'deshabilitar') {
        // Actualizar el estado del usuario a inactivo
        $actualizar_usuario = $conn->prepare("UPDATE secretarios SET estado_secretario = 0 WHERE id_secretario = :id_usuario");
    }
    $actualizar_usuario->bindParam(':id_usuario', $id_usuario);
    $actualizar_usuario->execute();
    $mensaje = $_GET['action'] == 'habilitar' ? 'habilitado' : 'deshabilitado';
    header("Location: " . $_SERVER['PHP_SELF'] . "?mensaje=$mensaje");
    exit();
}
?>

<div class="p-4 sm:ml-64">
    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">
        <h3 class="mb-4 text-4xl font-bold text-blue-800 dark:text-white border-b">Crear Secretario</h3>

        <form class="max-w-md mx-auto border border-dashed p-2" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
            <div class="relative z-0 w-full mb-5 group">
                <input type="number" name="usuario" id="usuario" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                <label for="usuario" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Numero de Cedula</label>
            </div>
            <div class="grid md:grid-cols-2 md:gap-6">
                <div class="relative z-0 w-full mb-5 group">
                    <input type="text" name="nombre" id="nombre" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="nombre" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Nombre</label>
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <input type="text" name="apellido" id="apellido" class="block py-2.5 px-0 w-full text-sm text-gray-900 bg-transparent border-0 border-b-2 border-gray-300 appearance-none dark:text-white dark:border-gray-600 dark:focus:border-blue-500 focus:outline-none focus:ring-0 focus:border-blue-600 peer" placeholder=" " required />
                    <label for="apellido" class="peer-focus:font-medium absolute text-sm text-gray-500 dark:text-gray-400 duration-300 transform -translate-y-6 scale-75 top-3 -z-10 origin-[0] peer-focus:start-0 rtl:peer-focus:translate-x-1/4 peer-focus:left-auto peer-focus:text-blue-600 peer-focus:dark:text-blue-500 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-6">Apellido</label>
                </div>
            </div>
            <label class="text-gray-500 dark:text-gray-400">Estado</label>
            <div class="grid md:grid-cols-2 md:gap-6">
                <div class="relative z-0 w-full mb-5 group">
                    <input checked id="activo" type="radio" value="1" name="estado" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="activo" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Activo</label>
                </div>
                <div class="relative z-0 w-full mb-5 group">
                    <input id="inactivo" type="radio" value="0" name="estado" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <label for="inactivo" class="w-full py-4 ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Inactivo</label>
                </div>
            </div>

            <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Crear Secretario</button>
        </form>
    </div>

    <?php
    // Consulta para obtener usuarios
    $consulta_usuarios = $conn->query("SELECT * FROM secretarios");
    ?>

    <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">

        <h3 class="mb-4 text-4xl font-bold text-blue-800 dark:text-white border-b">Tabla de Secretarios</h3>

        <div class="relative overflow-x-auto shadow-md sm:rounded-lg overflow-y-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-40">
                <thead class="text-xs text-gray-700 uppercase bg-blue-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nombre y Apellido</th>
                        <th scope="col" class="px-6 py-3">Numero de Cedula</th>
                        <th scope="col" class="px-6 py-3">Estado</th>
                        <th scope="col" class="px-6 py-3 text-center">Habilitar/Deshabilitar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($fila = $consulta_usuarios->fetch(PDO::FETCH_ASSOC)) : ?>
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <th scope="row" class="flex items-center px-6 py-4 text-gray-900 whitespace-nowrap dark:text-white">
                                <div class="ps-3">
                                    <div class="text-base font-semibold"><?php echo htmlspecialchars($fila['nombre_secretario'] . " " .  $fila['apellido_secretario']); ?></div>
                                </div>
                            </th>
                            <td class="px-6 py-4 "><?php echo htmlspecialchars($fila['secretario_usuario']); ?></td>
                            <td class="px-6 py-4">
                                <?php if ($fila['estado_secretario'] == 1) : ?>
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
                                <?php if ($fila['estado_secretario'] == 1) : ?>
                                    <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?action=deshabilitar&id=" . $fila['id_secretario']); ?>" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-red-500 dark:text-red-500 dark:hover:text-white dark:hover:bg-red-600 dark:focus:ring-red-900 deshabilitar">Deshabilitar</a>
                                <?php else : ?>
                                    <a href="<?php echo htmlspecialchars($_SERVER['PHP_SELF'] . "?action=habilitar&id=" . $fila['id_secretario']); ?>" class="text-green-700 hover:text-white border border-green-700 hover:bg-green-800 focus:ring-4 focus:outline-none focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-green-500 dark:text-green-500 dark:hover:text-white dark:hover:bg-green-600 dark:focus:ring-green-800 habilitar">Habilitar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "../vistas/footer/footer-admin.php"; ?>