<?php
$host = 'localhost';       // Host de la base de datos
$dbname = 'prueba20';  // Nombre de la base de datos
$username = 'root';      // Usuario de la base de datos
$password = '';   // Contraseña del usuario de la base de datos

try {
    // Creando una nueva conexión PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Estableciendo el modo de error PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Manejo de la excepción en caso de error en la conexión
    die("Error de conexión: " . $e->getMessage());
}
?>
<?php
/* $host = $_ENV["MYSQL_HOST"];       // Host de la base de datos
$dbname = $_ENV["MYSQL_DATABASE"];  // Nombre de la base de datos
$username = $_ENV["MYSQL_USER"];      // Usuario de la base de datos
$password = $_ENV["MYSQL_PASSWORD"];   // Contraseña del usuario de la base de datos
$port = $_ENV["MYSQL_PORT"];       // Puerto de la base de datos

try {
    // Creando una nueva conexión PDO
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $username, $password);

    // Estableciendo el modo de error PDO a excepción
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    // Manejo de la excepción en caso de error en la conexión
    die("Error de conexión: " . $e->getMessage());
} */
?>
