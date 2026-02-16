<?php
session_start(); // Inicia la sesión para usar variables de sesión

// Muestra el contenido del array $_POST para propósitos de depuración
var_dump($_POST);

// Incluye el archivo de conexión a la base de datos
include '../../include/database/database.php';

$id_examen = $_POST['id_examen'];
$id_formulario = $_POST['id_formulario'];
$ci_alumno = $_POST['ci_alumno'];

try {
    // Iniciar una transacción
    $conn->beginTransaction();

    foreach ($_POST as $key => $value) {
        if (strpos($key, 'pregunta_') === 0) {
            $id_pregunta = str_replace('pregunta_', '', $key);

            // Obtener el tipo de pregunta de la base de datos
            $stmt = $conn->prepare("SELECT tipo_pregunta FROM preguntas WHERE id_pregunta = :id_pregunta");
            $stmt->bindParam(':id_pregunta', $id_pregunta, PDO::PARAM_INT);
            $stmt->execute();
            $tipo_pregunta = $stmt->fetchColumn();

            if ($tipo_pregunta == 'texto') {
                $respuesta_texto = $value;
                $stmt = $conn->prepare("INSERT INTO respuesta_alumnos (id_examen, id_formulario, ci_alumno, id_pregunta, respuesta_texto) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$id_examen, $id_formulario, $ci_alumno, $id_pregunta, $respuesta_texto]);
                echo "Insertada respuesta de texto para pregunta $id_pregunta<br>";
            } elseif ($tipo_pregunta == 'opcion_multiple') {
                $id_opcion = $value;
                $stmt = $conn->prepare("INSERT INTO respuesta_alumnos (id_examen, id_formulario, ci_alumno, id_pregunta, id_opcion) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$id_examen, $id_formulario, $ci_alumno, $id_pregunta, $id_opcion]);
                echo "Insertada opción múltiple para pregunta $id_pregunta<br>";
            } elseif ($tipo_pregunta == 'vf') {
                $respuesta_vf = $value;
                $justificacion_vf = isset($_POST['justificacion_' . $id_pregunta]) ? $_POST['justificacion_' . $id_pregunta] : null;
                $stmt = $conn->prepare("INSERT INTO respuesta_alumnos (id_examen, id_formulario, ci_alumno, id_pregunta, respuesta_vf, justificacion_vf) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$id_examen, $id_formulario, $ci_alumno, $id_pregunta, $respuesta_vf, $justificacion_vf]);
                echo "Insertada respuesta VF para pregunta $id_pregunta<br>";
            } elseif ($tipo_pregunta == 'CodigoPro') {
                $respuesta_codigo = $value;
                $stmt = $conn->prepare("INSERT INTO respuesta_alumnos (id_examen, id_formulario, ci_alumno, id_pregunta, respuesta_codigo) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$id_examen, $id_formulario, $ci_alumno, $id_pregunta, $respuesta_codigo]);
                echo "Insertada respuesta de código para pregunta $id_pregunta<br>";
                var_dump($_POST);
                $codigo = $_POST['code'];
                $archivo = tempnam(sys_get_temp_dir(), 'codigo_') . '.cpp';
                file_put_contents($archivo, $codigo);
                
                $output = shell_exec("sh /scripts/verify_code.sh $archivo 2>&1");
                echo "<h2>Resultado:</h2>";
                echo "<pre>$output</pre>";
            }
        }
    }

    // Obtener las respuestas correctas filtradas por el formulario del examen
    $stmt = $conn->prepare("
        SELECT 
            p.id_pregunta,
            o.id_opcion,
            o.texto_opcion,
            o.es_correcta
        FROM 
            preguntas p
        JOIN 
            opciones o ON p.id_pregunta = o.preguntas_id_pregunta
        WHERE 
            p.formularios_id_formulario = ? AND o.es_correcta = 1
    ");
    $stmt->execute([$id_formulario]);
    $respuestas_correctas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mensaje de depuración: Mostrar las respuestas correctas obtenidas
    echo "Respuestas correctas obtenidas: ";
    var_dump($respuestas_correctas);

    // Transformar las respuestas correctas en un formato más fácil de usar
    $correct_answers = [];
    foreach ($respuestas_correctas as $row) {
        $correct_answers[$row['id_pregunta']] = [
            'id_opcion' => $row['id_opcion'],
            'texto_opcion' => $row['texto_opcion']
        ];
    }

    // Obtener las respuestas de los alumnos filtradas por el formulario y el examen
    $stmt = $conn->prepare("
        SELECT 
            id_respuesta,
            respuesta_texto,
            ci_alumno,
            id_pregunta,
            id_opcion,
            respuesta_vf,
            justificacion_vf,
            respuesta_codigo,
            puntaje_total
        FROM 
            respuesta_alumnos
        WHERE 
            id_examen = ? AND id_formulario = ?
    ");
    $stmt->execute([$id_examen, $id_formulario]);
    $respuestas_alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Mensaje de depuración: Mostrar las respuestas de los alumnos obtenidas
    echo "Respuestas de los alumnos obtenidas: ";
    var_dump($respuestas_alumnos);

    // Inicializar el puntaje total del alumno
    $puntaje_total_alumno = [];

    foreach ($respuestas_alumnos as $respuesta) {
        $ci_alumno = $respuesta['ci_alumno'];
        $id_pregunta = $respuesta['id_pregunta'];
        $id_opcion_alumno = $respuesta['id_opcion'];

        if (!isset($puntaje_total_alumno[$ci_alumno])) {
            $puntaje_total_alumno[$ci_alumno] = 0;
        }

        // Verificar la respuesta
        if (isset($correct_answers[$id_pregunta])) {
            if ($correct_answers[$id_pregunta]['id_opcion'] == $id_opcion_alumno) {
                // Incrementar el puntaje si la respuesta es correcta
                $puntaje_total_alumno[$ci_alumno] += 1; // Ajusta este valor según el puntaje de cada pregunta
            }
        }
    }

    // Mensaje de depuración: Mostrar los puntajes calculados
    echo "Puntajes calculados: ";
    var_dump($puntaje_total_alumno);

    // Actualizar el puntaje total de cada alumno en la base de datos
    foreach ($puntaje_total_alumno as $ci_alumno => $puntaje) {
        $stmt = $conn->prepare("
            UPDATE respuesta_alumnos
            SET puntaje_total = ?
            WHERE ci_alumno = ? AND id_examen = ? AND id_formulario = ?
        ");
        $stmt->execute([$puntaje, $ci_alumno, $id_examen, $id_formulario]);

        // Mensaje de depuración: Mostrar que se ha actualizado el puntaje
        echo "Puntaje actualizado para alumno $ci_alumno: $puntaje<br>";
    }

    // Confirmar la transacción
    $conn->commit();

    // Mensaje de éxito
    $_SESSION['mensaje'] = "Examen finalizado y corregido exitosamente.";

    // Redirección a la página de exámenes pendientes
    header("Location: ../examenes-pendientes.php");
    exit; // Asegúrate de usar exit después de header para evitar ejecución adicional

} catch (PDOException $e) {
    // Revertir la transacción en caso de error
    $conn->rollBack();

    // Guardar el mensaje de error en la sesión
    $_SESSION['mensaje'] = "Error al finalizar el examen: " . $e->getMessage();

    // Redirección a la página de exámenes pendientes
    header("Location: ../examenes-pendientes.php");
    exit; // Asegúrate de usar exit después de header para evitar ejecución adicional
}
?>
