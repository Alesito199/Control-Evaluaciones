<?php 
include "../vistas/header/header-docente.php";

$ci_docente = $_SESSION['ci_docente']; // Asumiendo que el ci_docente está almacenado en la sesión

try {
    $stmt = $conn->prepare("
        SELECT e.id_examen, e.nombre_examen, f.nombre_formulario
        FROM examenes e
        JOIN formularios f ON e.id_formulario = f.id_formulario
        WHERE e.ci_docente = ?
    ");
    $stmt->execute([$ci_docente]);
    $examenes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Exámenes del Docente</title>
</head>
<body>
    <h1>Exámenes Asignados</h1>
    <?php if ($examenes): ?>
        <table border="1">
            <tr>
                <th>ID Examen</th>
                <th>Nombre Examen</th>
                <th>Nombre Formulario</th>
                <th>Acciones</th>
            </tr>
            <?php foreach ($examenes as $examen): ?>
                <tr>
                    <td><?= $examen['id_examen'] ?></td>
                    <td><?= $examen['nombre_examen'] ?></td>
                    <td><?= $examen['nombre_formulario'] ?></td>
                    <td><a href="corregir-examen.php?id_examen=<?= $examen['id_examen'] ?>">Corregir</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No tienes exámenes asignados.</p>
    <?php endif; ?>
</body>
</html>


<?php 
include "../vistas/footer/footer-docente.php";
?>