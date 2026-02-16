<?php
include '../../include/database/database.php';

$id_examen = $_POST['id_examen'];
echo "<pre>";
print_r($_POST);
echo "</pre>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar si las claves necesarias están presentes
    if (isset($_POST['recordatorio'], $_POST['indicadores'], $_POST['preguntas'])) {
        $notas = $_POST['recordatorio'];
        $indicadores = $_POST['indicadores'];
        $preguntas = $_POST['preguntas'];

      /*   // Imprimir datos para depuración
        echo "<pre>";
        print_r($_POST);
        echo "</pre>"; */

        try {
            // Iniciar la transacción
            $conn->beginTransaction();

            // Insertar el formulario
            $stmt = $conn->prepare("INSERT INTO formularios (notas_formulario, indicadores_formulario, examenes_id_examen) VALUES (:notas, :indicadores, :id_examen)");
            $stmt->bindParam(':notas', $notas);
            $stmt->bindParam(':indicadores', $indicadores);
            $stmt->bindParam(':id_examen', $id_examen);
            if ($stmt->execute()) {
                echo "Formulario insertado correctamente.<br>";
            } else {
                echo "Error al insertar el formulario.<br>";
            }
            $id_formulario = $conn->lastInsertId();

            foreach ($preguntas as $pregunta) {
                $texto_pregunta = $pregunta['texto'];
                $tipo = isset($pregunta['opciones']) ? 'opcion_multiple' : (isset($pregunta['es_correcta']) ? 'vf' : (isset($pregunta['CodigoPro']) ? 'CodigoPro' : 'texto'));
                $es_correcta = isset($pregunta['es_correcta']) ? $pregunta['es_correcta'] : null;
                $justificacion = isset($pregunta['justificacion']) ? $pregunta['justificacion'] : null;

                // Insertar la pregunta
                $stmt = $conn->prepare("INSERT INTO preguntas (formularios_id_formulario, texto_pregunta, tipo_pregunta) VALUES (:formulario_id, :pregunta, :tipo)");
                $stmt->bindParam(':formulario_id', $id_formulario);
                $stmt->bindParam(':pregunta', $texto_pregunta);
                $stmt->bindParam(':tipo', $tipo);
                if ($stmt->execute()) {
                    echo "Pregunta insertada correctamente.<br>";
                } else {
                    echo "Error al insertar la pregunta.<br>";
                }
                $id_pregunta = $conn->lastInsertId();

                if ($tipo == 'opcion_multiple') {
                    $opciones = $pregunta['opciones'];
                    $correcta = isset($pregunta['correcta']) ? $pregunta['correcta'] : [];

                    foreach ($opciones as $index => $opcion) {
                        $es_correcta = in_array($index, $correcta) ? 1 : 0;

                        // Insertar la opción
                        $stmt = $conn->prepare("INSERT INTO opciones (preguntas_id_pregunta, texto_opcion, es_correcta) VALUES (:pregunta_id, :opcion, :es_correcta)");
                        $stmt->bindParam(':pregunta_id', $id_pregunta);
                        $stmt->bindParam(':opcion', $opcion);
                        $stmt->bindParam(':es_correcta', $es_correcta);
                        if ($stmt->execute()) {
                            echo "Opción insertada correctamente.<br>";
                        } else {
                            echo "Error al insertar la opción.<br>";
                        }
                    }
                } else if ($tipo == 'vf') {
                    // Insertar la respuesta verdadero/falso
                    $stmt = $conn->prepare("INSERT INTO opciones (preguntas_id_pregunta, es_correcta) VALUES (:pregunta_id, :es_correcta)");
                    $stmt->bindParam(':pregunta_id', $id_pregunta);
                    $stmt->bindParam(':es_correcta', $es_correcta);
                    if ($stmt->execute()) {
                        echo "Respuesta insertada correctamente.<br>";
                    } else {
                        echo "Error al insertar la respuesta.<br>";
                    }

                    if ($es_correcta == 0 && $justificacion) {
                        // Insertar la justificación si la respuesta es falsa
                        $stmt = $conn->prepare("UPDATE opciones SET texto_opcion = :justificacion WHERE preguntas_id_pregunta = :pregunta_id");
                        $stmt->bindParam(':pregunta_id', $id_pregunta);
                        $stmt->bindParam(':justificacion', $justificacion);
                        if ($stmt->execute()) {
                            echo "Justificación actualizada correctamente.<br>";
                        } else {
                            echo "Error al actualizar la justificación.<br>";
                        }
                    }
                }
            }

            // Confirmar la transacción
            $conn->commit(); 
            header("Location: ../crear-examen.php?mensaje=creado");
            exit();
        } catch (PDOException $e) {
            // Revertir la transacción en caso de error
            $conn->rollBack();
            echo "Error al guardar los datos: " . $e->getMessage();
        }
    } else {
        echo "Los datos del formulario no están completos.";
    }
} else {
    echo "Método de solicitud no válido.";
}
