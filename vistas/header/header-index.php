<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://uninorte.edu.py/wp-content/uploads/2020/03/uninorte-favicon-192x192.png" sizes="192x192"> <!-- LOGO DE LA UNIVERSIDAD DEL NORTE -->
    <link href="css/style.css" rel="stylesheet">
    <title>Pagina Principal Control de Evaluaciones</title>
    <script src="js/appmenu.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> <!-- SweetAlert -->
    <script>
        function esComputadora() {
            return (window.innerWidth >= 1024);
        }

        document.addEventListener("DOMContentLoaded", function() {
            if (!esComputadora()) {
                swal({
                    title: 'Acceso restringido',
                    text: 'Este sitio web solo está disponible en computadoras de escritorio.',
                    icon: 'error',
                    button: false,
                    closeOnClickOutside: false
                });
            }
        });
    </script>
</head>

<body>
    <header class="border-b ">
        <nav class="bg-white border-gray-900 dark:bg-gray-900">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                <a href="index.php" class="flex items-center space-x-3 rtl:space-x-reverse">
                    <img src="https://uninorte.edu.py/wp-content/uploads/2020/03/uninorte-favicon-192x192.png" class="h-14" alt="Uninorte Logo" />
                    <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">Control de Evaluaciones</span>
                </a>
                <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                    <a href="login.php" type="button" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 dark:border-blue-500 dark:text-blue-500 dark:hover:text-white dark:hover:bg-blue-500 dark:focus:ring-blue-800">Iniciar Sesión</a>
                </div>
                <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-cta">
                    <ul class="flex flex-col font-medium p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                        <li>
                            <a href="index.php" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Menú Principal</a>
                        </li>
                        <li>
                            <a href="reglamento.php" class="block py-2 px-3 md:p-0 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">Reglamento</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>