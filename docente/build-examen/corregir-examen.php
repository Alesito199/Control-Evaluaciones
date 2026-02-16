<?php 

// Obtener las respuestas correctas filtradas por el formulario del examen
$id_formulario = $_GET['id_formulario']; // Suponiendo que obtienes el ID del formulario de algún modo
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

// Transformar las respuestas correctas en un formato más fácil de usar
$correct_answers = [];
foreach ($respuestas_correctas as $row) {
    $correct_answers[$row['id_pregunta']] = [
        'id_opcion' => $row['id_opcion'],
        'texto_opcion' => $row['texto_opcion']
    ];
}

// Obtener las respuestas de los alumnos filtradas por el formulario y el examen
$id_examen = $_GET['id_examen']; // Suponiendo que obtienes el ID del examen de algún modo
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

// Actualizar el puntaje total de cada alumno en la base de datos
foreach ($puntaje_total_alumno as $ci_alumno => $puntaje) {
    $stmt = $conn->prepare("
        UPDATE respuesta_alumnos
        SET puntaje_total = ?
        WHERE ci_alumno = ? AND id_examen = ? AND id_formulario = ?
    ");
    $stmt->execute([$puntaje, $ci_alumno, $id_examen, $id_formulario]);
}



?>