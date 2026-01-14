<?php
include "../vistas/header/header-admin.php";
?>


<div class="p-4 sm:ml-64">
   <div class="p-4 border-2 border-gray-200 border-dashed rounded-lg dark:border-gray-700 mt-14">


      <section class="bg-blue-50 dark:bg-gray-900">
         <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-12">
            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12 mb-8">
               <a href="#" class="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-blue-400 mb-2">
                  <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                     <path d="M11 0H2a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm8.585 1.189a.994.994 0 0 0-.9-.138l-2.965.983a1 1 0 0 0-.685.949v8a1 1 0 0 0 .675.946l2.965 1.02a1.013 1.013 0 0 0 1.032-.242A1 1 0 0 0 20 12V2a1 1 0 0 0-.415-.811Z" />
                  </svg>
                  Tutorial
               </a>
               <h1 class="text-gray-900 dark:text-white text-3xl md:text-5xl font-extrabold mb-2">Acceda a información esencial sobre el Panel de Gestión</h1>
               <p class="text-lg font-normal text-gray-500 dark:text-gray-400 mb-6">En esta sección, encontrará una guía completa sobre cómo utilizar el sistema, además de acceder al manual del usuario destinado a los secretarios. Esta información es crucial para asegurar que todos los usuarios puedan navegar eficientemente por el sistema y aprovechar al máximo sus funcionalidades.</p>
               <a href="#" class="inline-flex justify-center items-center py-2.5 px-5 text-base font-medium text-center text-white rounded-lg bg-gray-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                  Leer más
                  <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                  </svg>
               </a>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
               <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12">
                  <a href="#" class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400 mb-2">
                     <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                        <path d="M17 11h-2.722L8 17.278a5.512 5.512 0 0 1-.9.722H17a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1ZM6 0H1a1 1 0 0 0-1 1v13.5a3.5 3.5 0 1 0 7 0V1a1 1 0 0 0-1-1ZM3.5 15.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2ZM16.132 4.9 12.6 1.368a1 1 0 0 0-1.414 0L9 3.55v9.9l7.132-7.132a1 1 0 0 0 0-1.418Z" />
                     </svg>
                     Docentes
                  </a>
                  <h2 class="text-gray-900 dark:text-white text-3xl font-extrabold mb-2">Gestión de Docentes</h2>
                  <p class="text-lg font-normal text-gray-500 dark:text-gray-400 mb-4">En esta sección, podrá agregar y gestionar la información de los docentes de programación que participan en el sistema. Esto incluye datos personales, asignaturas que imparten y horarios, asegurando una administración eficaz del personal educativo.</p>
                  <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline font-medium text-lg inline-flex items-center">Ir a la sección
                     <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                     </svg>
                  </a>
               </div>

               <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12">
                  <a href="#" class="bg-purple-100 text-purple-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-purple-400 mb-2">
                     <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4 1 8l4 4m10-8 4 4-4 4M11 1 9 15" />
                     </svg>
                     Alumnos
                  </a>
                  <h2 class="text-gray-900 dark:text-white text-3xl font-extrabold mb-2">Alumnos</h2>
                  <p class="text-lg font-normal text-gray-500 dark:text-gray-400 mb-4">En esta pestaña, podrá agregar y gestionar la información de los alumnos inscritos en el sistema. Esto incluye la actualización de datos personales, historial académico y progreso en los cursos, asegurando una experiencia educativa completa y organizada.</p>
                  <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline font-medium text-lg inline-flex items-center">Ir a ella
                     <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                     </svg>
                  </a>
               </div>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mt-8">
               <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12">
                  <a href="#" class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-green-400 mb-2">
                     <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 18">
                        <path d="M17 11h-2.722L8 17.278a5.512 5.512 0 0 1-.9.722H17a1 1 0 0 0 1-1v-5a1 1 0 0 0-1-1ZM6 0H1a1 1 0 0 0-1 1v13.5a3.5 3.5 0 1 0 7 0V1a1 1 0 0 0-1-1ZM3.5 15.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2ZM16.132 4.9 12.6 1.368a1 1 0 0 0-1.414 0L9 3.55v9.9l7.132-7.132a1 1 0 0 0 0-1.418Z" />
                     </svg>
                     Asignaturas
                  </a>
                  <h2 class="text-gray-900 dark:text-white text-3xl font-extrabold mb-2">Asignaturas</h2>
                  <p class="text-lg font-normal text-gray-500 dark:text-gray-400 mb-4">Acceda a la sección dedicada a la gestión de asignaturas de programación disponibles en el sistema. Aquí podrá añadir nuevas asignaturas, modificar las existentes y organizar el plan de estudios de manera eficiente.</p>
                  <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline font-medium text-lg inline-flex items-center">Ir a ella
                     <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                     </svg>
                  </a>
               </div>

               <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12">
                  <a href="#" class="bg-purple-100 text-purple-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-purple-400 mb-2">
                     <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4 1 8l4 4m10-8 4 4-4 4M11 1 9 15" />
                     </svg>
                     Curso
                  </a>
                  <h2 class="text-gray-900 dark:text-white text-3xl font-extrabold mb-2">Cursos</h2>
                  <p class="text-lg font-normal text-gray-500 dark:text-gray-400 mb-4">En esta pestaña, podrá agregar y gestionar los cursos disponibles, especificando el año y el semestre en el que se dictarán. Además, tendrá la opción de editar o eliminar cursos existentes, asegurando que la información esté siempre actualizada y relevante para los alumnos y docentes.</p>
                  <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline font-medium text-lg inline-flex items-center">Leer más
                     <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                     </svg>
                  </a>
               </div>

               <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12">
                  <a href="#" class="bg-purple-100 text-purple-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-purple-400 mb-2">
                     <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4 1 8l4 4m10-8 4 4-4 4M11 1 9 15" />
                     </svg>
                     Calendario
                  </a>
                  <h2 class="text-gray-900 dark:text-white text-3xl font-extrabold mb-2">Calendario de Exámenes</h2>
                  <p class="text-lg font-normal text-gray-500 dark:text-gray-400 mb-4">Aquí podrá agregar y administrar los calendarios de exámenes, asegurando que todos los docentes tengan acceso a la programación de las pruebas. Esto facilitará la planificación de las clases y permitirá a los estudiantes estar al tanto de las fechas importantes, evitando conflictos en su agenda académica.</p>
                  <a href="#" class="text-blue-600 dark:text-blue-500 hover:underline font-medium text-lg inline-flex items-center">Leer más
                     <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                     </svg>
                  </a>
               </div>
            </div>


            <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-8 md:p-12 mb-8 mt-8">
               <a href="#" class="bg-blue-100 text-blue-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded-md dark:bg-gray-700 dark:text-blue-400 mb-2">
                  <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
                     <path d="M11 0H2a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h9a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm8.585 1.189a.994.994 0 0 0-.9-.138l-2.965.983a1 1 0 0 0-.685.949v8a1 1 0 0 0 .675.946l2.965 1.02a1.013 1.013 0 0 0 1.032-.242A1 1 0 0 0 20 12V2a1 1 0 0 0-.415-.811Z" />
                  </svg>
                  DOCENTES/CURSOS
               </a>
               <h1 class="text-gray-900 dark:text-white text-3xl md:text-5xl font-extrabold mb-2">Asignar Docente a Cursos</h1>
               <p class="text-lg font-normal text-gray-500 dark:text-gray-400 mb-6">En esta sección se realizan las asignaciones de los docentes a sus respectivos cursos, asegurando que cada clase esté correctamente cubierta. Los docentes podrán ser asignados o reasignados fácilmente, y la información se actualizará en tiempo real para reflejar los cambios, mejorando así la gestión educativa.</p>
               <a href="#" class="inline-flex justify-center items-center py-2.5 px-5 text-base font-medium text-center text-white rounded-lg bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900">
                  Leer más
                  <svg class="w-3.5 h-3.5 ms-2 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9" />
                  </svg>
               </a>
            </div>
            
         </div>
      </section>
   </div>
</div>






<?php
include "../vistas/footer/footer-admin.php";
?>