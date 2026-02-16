<!-- 
include '../../include/database/database.php'; // Ajusta la ruta según tu estructura de directorios

function ejecutarCodigo($codigo, $lenguaje) {
    $directorioTrabajo = '/path/to/temp/directory'; // Directorio donde se guardará el código temporalmente
    $archivoCodigo = $directorioTrabajo . '/codigo.' . getExtension($lenguaje);

    // Guardar el código en un archivo temporal
    file_put_contents($archivoCodigo, $codigo);

    // Ejecutar el contenedor Docker con el código adecuado
    switch ($lenguaje) {
        case 'cpp':
            $comando = "docker run --rm -v $directorioTrabajo:/src -w /src gcc:latest g++ codigo.cpp -o codigo && ./codigo";
            break;
        case 'c':
            $comando = "docker run --rm -v $directorioTrabajo:/src -w /src gcc:latest gcc codigo.c -o codigo && ./codigo";
            break;
        case 'java':
            $comando = "docker run --rm -v $directorioTrabajo:/src -w /src openjdk:latest javac codigo.java && java Codigo";
            break;
        case 'js':
            $comando = "docker run --rm -v $directorioTrabajo:/src -w /src node:latest node codigo.js";
            break;
        case 'php':
            $comando = "docker run --rm -v $directorioTrabajo:/src -w /src php:latest php codigo.php";
            break;
        case 'sql':
            // Ejecutar código SQL en una base de datos temporal
            $comando = "docker run --rm -v $directorioTrabajo:/src -w /src mysql:latest mysql -u root -pPASSWORD -e 'source codigo.sql'";
            break;
        case 'css':
            // No es necesario ejecutar código CSS, solo validarlo
            return "El código CSS no necesita ejecución";
        default:
            return "Lenguaje no soportado";
    }

    // Ejecutar el comando y capturar la salida
    $resultado = shell_exec($comando . ' 2>&1');
    return $resultado;
}

function getExtension($lenguaje) {
    switch ($lenguaje) {
        case 'cpp':
            return 'cpp';
        case 'c':
            return 'c';
        case 'java':
            return 'java';
        case 'js':
            return 'js';
        case 'php':
            return 'php';
        case 'sql':
            return 'sql';
        case 'css':
            return 'css';
        default:
            return '';
    }
}

// Obtener el código del estudiante desde la base de datos
$id_respuesta = $_GET['id_respuesta']; // Suponiendo que el ID de la respuesta se pasa como parámetro
$stmt = $conn->prepare("SELECT respuesta_codigo, lenguaje FROM respuesta_alumnos WHERE id_respuesta = ?");
$stmt->bind_param('i', $id_respuesta);
$stmt->execute();
$result = $stmt->get_result();
$respuesta = $result->fetch_assoc();

$codigo = $respuesta['respuesta_codigo'];
$lenguaje = $respuesta['lenguaje'];

// Ejecutar el código del estudiante
$resultado = ejecutarCodigo($codigo, $lenguaje);

// Actualizar la base de datos con el resultado
$stmt = $conn->prepare("UPDATE respuesta_alumnos SET resultado = ?, estado = 'verificado' WHERE id_respuesta = ?");
$stmt->bind_param('si', $resultado, $id_respuesta);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "El código ha sido ejecutado y el resultado ha sido guardado.";
} else {
    echo "Hubo un error al guardar el resultado.";
}
?>*/
