<?php
// Iniciar el almacenamiento en búfer de salida
ob_start();
// Incluir la conexión a la base de datos
include('../include/database/database.php');
// Incluir archivo de funciones y verificar autenticación
include("../include/funciones/funciones.php");
$auten = estadoAutenticado();
if (!$auten) {
   header('location: ../index.php');
   exit; // Asegurarse de que el script no sigue ejecutándose
}

$usuario = $_SESSION['usuario'];

// Fetch user details from the database
try {
   $stmt = $conn->prepare("SELECT ci_alumno, nombre_alumno, apellido_alumno, correo_alumno FROM alumnos WHERE usuario_alumno = :usuario");
   $stmt->bindParam(':usuario', $usuario);
   $stmt->execute();
   $result = $stmt->fetch(PDO::FETCH_ASSOC);

   if ($result) {
      $nombre = $result['nombre_alumno'];
      $apellido = $result['apellido_alumno'];
      $correo = $result['correo_alumno'];
      $ci = $result['ci_alumno'];
   } else {
      echo "No user data found.";
      exit;
   }
} catch (PDOException $e) {
   echo "Database error: " . $e->getMessage();
   exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="icon" href="https://uninorte.edu.py/wp-content/uploads/2020/03/uninorte-favicon-192x192.png" sizes="192x192">
   <link href="../css/style.css" rel="stylesheet">
   <link href="../css/UniNorte.css" rel="stylesheet">


   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/codemirror.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/theme/dracula.min.css">
   <!-- Opcional: Estilos para el addon de autocompletado -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/addon/hint/show-hint.min.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.css">
   <!-- Incluir hojas de estilo de CodeMirror -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/codemirror.min.css">
   <!-- Incluir el tema que prefieras -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/theme/dracula.min.css">
   <title>Portal Alumnos UniNorte</title>

   <!-- Incluir JavaScript de CodeMirror -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/codemirror.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.62.0/codemirror.min.js"></script>

   <!-- Incluir modos o addons específicos según necesidad -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/mode/javascript/javascript.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/mode/xml/xml.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/mode/css/css.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/mode/htmlmixed/htmlmixed.min.js"></script>

   <!-- Opcional: Incluir addon para autocompletado -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/addon/hint/show-hint.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/addon/hint/javascript-hint.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/addon/hint/html-hint.min.js"></script>


   <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/mode/clike/clike.min.js"></script>
   <!-- Opcional: Estilos para el addon de autocompletado -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/addon/hint/show-hint.min.css">

   <!-- Incluir addon para autocompletado -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/addon/hint/show-hint.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/addon/hint/cpp-hint.min.js"></script>



   <!-- Incluir JavaScript de CodeMirror justo antes del cierre del body -->



   <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/mode/sql/sql.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.68.0/mode/php/php.min.js"></script>






</head>

<body>
   <header>
      <nav class="fixed top-0 z-50 w-full bg-white border-b  dark:bg-gray-800 dark:border-gray-700">
         <div class="px-3 py-3 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
               <div class="flex items-center justify-start rtl:justify-end">
                  <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar" aria-controls="logo-sidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                     <span class="sr-only">Open sidebar</span>
                     <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                     </svg>
                  </button>
                  <a href="index.php" class="flex ms-2 md:me-24">
                     <img src="https://uninorte.edu.py/wp-content/uploads/2020/03/uninorte-favicon-192x192.png" class="h-8 me-3" alt="FlowBite Logo" />
                     <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap dark:text-white">Portal Alumnos UniNorte</span>
                  </a>
               </div>
               <div class="flex items-center">
                  <div class="flex items-center ms-3">
                     <div>
                        <!-- ACA TIENE QUE HABER UNA CONSULTA DONDE IMAGEN DE PERFIL SE PONDRA -->
                        <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" aria-expanded="false" data-dropdown-toggle="dropdown-user">
                           <span class="sr-only">Open user menu</span>
                           <img class="w-8 h-8 rounded-full" src="https://flowbite.com/docs/images/people/profile-picture-5.jpg" alt="user photo">

                        </button>
                     </div>
                     <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600" id="dropdown-user">
                        <div class="px-4 py-3" role="none">
                           <p class="text-sm text-gray-900 dark:text-white text" role="none">
                              <?php
                              echo htmlspecialchars($nombre) . ' ' . htmlspecialchars($apellido);
                              ?>
                           </p>
                           <?php if ($correo == null) : ?>
                              <p class="text-sm font-medium text-yellow-300 truncate dark:text-yellow-300">
                                 No olvide agregar su correo en Configuracion
                              </p>
                           <?php else : ?>
                              <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300">
                                 <?php echo htmlspecialchars($correo); ?>
                              </p>
                           <?php endif; ?>
                        </div>
                        <ul class="py-1" role="none">
                           <li>
                              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Configuracion</a>
                           </li>
                           <li>
                              <a href="../include/funciones/logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white" role="menuitem">Cerrar Session</a>
                           </li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </nav>
   </header>


   <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
      <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
         <ul class="space-y-2 font-medium">
            <li class="border-b">
               <a href="index.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <svg class="w-6 h-6 text-blue-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                     <path fill-rule="evenodd" d="M11.293 3.293a1 1 0 0 1 1.414 0l6 6 2 2a1 1 0 0 1-1.414 1.414L19 12.414V19a2 2 0 0 1-2 2h-3a1 1 0 0 1-1-1v-3h-2v3a1 1 0 0 1-1 1H7a2 2 0 0 1-2-2v-6.586l-.293.293a1 1 0 0 1-1.414-1.414l2-2 6-6Z" clip-rule="evenodd" />
                  </svg>
                  <span class="flex-1 ms-3 whitespace-nowrap">Menu Principal</span>
               </a>
            </li>
            <li class="border-b">
               <a href="examenes-pendientes.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <svg class="w-6 h-6 text-blue-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                     <path fill-rule="evenodd" d="M8 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1h2a2 2 0 0 1 2 2v15a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h2Zm6 1h-4v2H9a1 1 0 0 0 0 2h6a1 1 0 1 0 0-2h-1V4Zm-3 8a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Zm2 5a1 1 0 0 1 1-1h3a1 1 0 1 1 0 2h-3a1 1 0 0 1-1-1Zm-2-1a1 1 0 1 0 0 2h.01a1 1 0 1 0 0-2H9Z" clip-rule="evenodd" />
                  </svg>

                  <span class="flex-1 ms-3 whitespace-nowrap">Examenes Pendientes</span>
               </a>
            </li>

            <li class="border-b">
               <a href="puntaje-examenes.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <svg class="w-6 h-6 text-blue-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                     <path fill-rule="evenodd" d="M9 2a1 1 0 0 0-1 1H6a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V5a2 2 0 0 0-2-2h-2a1 1 0 0 0-1-1H9Zm1 2h4v2h1a1 1 0 1 1 0 2H9a1 1 0 0 1 0-2h1V4Zm5.707 8.707a1 1 0 0 0-1.414-1.414L11 14.586l-1.293-1.293a1 1 0 0 0-1.414 1.414l2 2a1 1 0 0 0 1.414 0l4-4Z" clip-rule="evenodd" />
                  </svg>

                  <span class="flex-1 ms-3 whitespace-nowrap">Examenes Hechos</span>
               </a>
            </li>

            <li class="border-b">
               <a href="registro-asistencia-alumno.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <svg class="w-6 h-6 text-blue-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                     <path fill-rule="evenodd" d="M18 14a1 1 0 1 0-2 0v2h-2a1 1 0 1 0 0 2h2v2a1 1 0 1 0 2 0v-2h2a1 1 0 1 0 0-2h-2v-2Z" clip-rule="evenodd" />
                     <path fill-rule="evenodd" d="M15.026 21.534A9.994 9.994 0 0 1 12 22C6.477 22 2 17.523 2 12S6.477 2 12 2c2.51 0 4.802.924 6.558 2.45l-7.635 7.636L7.707 8.87a1 1 0 0 0-1.414 1.414l3.923 3.923a1 1 0 0 0 1.414 0l8.3-8.3A9.956 9.956 0 0 1 22 12a9.994 9.994 0 0 1-.466 3.026A2.49 2.49 0 0 0 20 14.5h-.5V14a2.5 2.5 0 0 0-5 0v.5H14a2.5 2.5 0 0 0 0 5h.5v.5c0 .578.196 1.11.526 1.534Z" clip-rule="evenodd" />
                  </svg>

                  <span class="flex-1 ms-3 whitespace-nowrap">Registro de Asistencia</span>
               </a>
            </li>
            <!-- 
            <li>
               <a href="calendario-admin.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                     <path fill-rule="evenodd" d="M18 5.05h1a2 2 0 0 1 2 2v2H3v-2a2 2 0 0 1 2-2h1v-1a1 1 0 1 1 2 0v1h3v-1a1 1 0 1 1 2 0v1h3v-1a1 1 0 1 1 2 0v1Zm-15 6v8a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-8H3ZM11 18a1 1 0 1 0 2 0v-1h1a1 1 0 1 0 0-2h-1v-1a1 1 0 1 0-2 0v1h-1a1 1 0 1 0 0 2h1v1Z" clip-rule="evenodd" />
                  </svg>
                  <span class="flex-1 ms-3 whitespace-nowrap">Calendario de Examenes</span>
               </a>
            </li>

            <li>
               <a href="crear-curso-admin.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                     <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z" clip-rule="evenodd" />
                  </svg>
                  <span class="flex-1 ms-3 whitespace-nowrap">Crear Curso/Semestre</span>
               </a>
            </li>

            <li>
               <a href="asignacion-curso-docente-asignatura.php" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                  <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                     <path fill-rule="evenodd" d="M12 6a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7Zm-1.5 8a4 4 0 0 0-4 4 2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-3Zm6.82-3.096a5.51 5.51 0 0 0-2.797-6.293 3.5 3.5 0 1 1 2.796 6.292ZM19.5 18h.5a2 2 0 0 0 2-2 4 4 0 0 0-4-4h-1.1a5.503 5.503 0 0 1-.471.762A5.998 5.998 0 0 1 19.5 18ZM4 7.5a3.5 3.5 0 0 1 5.477-2.889 5.5 5.5 0 0 0-2.796 6.293A3.501 3.501 0 0 1 4 7.5ZM7.1 12H6a4 4 0 0 0-4 4 2 2 0 0 0 2 2h.5a5.998 5.998 0 0 1 3.071-5.238A5.505 5.505 0 0 1 7.1 12Z" clip-rule="evenodd" />
                  </svg>
                  <span class="flex-1 ms-3 whitespace-nowrap">Asignar Curso/Semestre</span>
               </a>
            </li> -->
         </ul>
      </div>
   </aside>