<?php
include "vistas/header/header-index.php";
?>
<div class="container mx-auto">
    <main class="py-8">
        <h1 class="text-5xl border-b text-blue-800 font-extrabold dark:text-white p-3 text-center justify-center">Un poco sobre la Universidad y sus Objetivos</h1>
        <div class="w-full bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 mt-8">
            <div class="sm:hidden">
                <label for="tabs" class="sr-only">Select tab</label>
                <select id="tabs" class="bg-gray-50 border-0 border-b border-gray-200 text-gray-900 text-sm rounded-t-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option>HISTORIA</option>
                    <option>MISION</option>
                    <option>VISION</option>
                </select>
            </div>
            <ul class="hidden text-sm font-medium text-center text-gray-500 divide-x divide-gray-200 rounded-lg sm:flex dark:divide-gray-600 dark:text-gray-400 rtl:divide-x-reverse" id="fullWidthTab" data-tabs-toggle="#fullWidthTabContent" role="tablist">
                <li class="w-full">
                    <button id="stats-tab" data-tabs-target="#stats" type="button" role="tab" aria-controls="stats" aria-selected="true" class="inline-block w-full p-4 rounded-ss-lg bg-gray-50 hover:bg-gray-100 focus:outline-none dark:bg-gray-700 dark:hover:bg-gray-600">HISTORIA</button>
                </li>
                <li class="w-full">
                    <button id="about-tab" data-tabs-target="#about" type="button" role="tab" aria-controls="about" aria-selected="false" class="inline-block w-full p-4 bg-gray-50 hover:bg-gray-100 focus:outline-none dark:bg-gray-700 dark:hover:bg-gray-600">MISION</button>
                </li>
                <li class="w-full">
                    <button id="faq-tab" data-tabs-target="#faq" type="button" role="tab" aria-controls="faq" aria-selected="false" class="inline-block w-full p-4 rounded-se-lg bg-gray-50 hover:bg-gray-100 focus:outline-none dark:bg-gray-700 dark:hover:bg-gray-600">VISION</button>
                </li>
            </ul>
            <div id="fullWidthTabContent" class="border-t border-gray-200 dark:border-gray-600">
                <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="stats" role="tabpanel" aria-labelledby="stats-tab">
                    <dl class="grid max-w-screen-xl grid-cols-1 gap-8 p-4 mx-auto text-gray-900 sm:grid-cols-2 xl:grid-cols-1 dark:text-white sm:p-8">
                        <div class="flex flex-col items-center">
                            <img class="rounded-t-lg h-48" src="others/img/historia.jpg" alt="LOGO DE LA UNIVERSIDAD" />
                        </div>
                        <div class="flex flex-col items-center justify-center">
                            <p class="mb-4 text-gray-800 dark:text-gray-400 flex-grow">La Universidad del Norte es una entidad autónoma, de derecho privado, creada el 27 de mayo de 1991 por Decreto del Poder Ejecutivo Nº 9689 conforme a lo establecido por la Ley N º 828/80 de Universidades. A más de 30 años de su fundación la Universidad del Norte es la cuarta universidad más antigua del Paraguay.</p>
                        </div>
                    </dl>
                </div>

                <div class="hidden p-4 bg-white rounded-lg md:p-8 dark:bg-gray-800" id="about" role="tabpanel" aria-labelledby="about-tab">
                    <h2 class="mb-5 text-2xl font-extrabold tracking-tight text-gray-900 dark:text-white">MISION</h2>
                    <!-- Lista -->
                    <ul role="list" class="space-y-4 text-gray-500 dark:text-gray-400">
                        <li class="flex space-x-2 rtl:space-x-reverse items-center">
                            <svg class="flex-shrink-0 w-3.5 h-3.5 text-blue-600 dark:text-blue-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z" />
                            </svg>
                            <span class="leading-tight">Consolidarse como una Institución de Educación Superior innovadora en la enseñanza, investigación, gestión y realización de actividades de impacto social que impulsan la cultura, mediante una Comunidad Educativa comprometida.</span>
                        </li>

                    </ul>
                </div>
                <div class="hidden p-4 bg-white rounded-lg dark:bg-gray-800" id="faq" role="tabpanel" aria-labelledby="faq-tab">
                    <div id="accordion-flush" data-accordion="collapse" data-active-classes="bg-white dark:bg-gray-800 text-gray-900 dark:text-white" data-inactive-classes="text-gray-500 dark:text-gray-400">
                        <h2 id="accordion-flush-heading-1">
                            <button type="button" class="flex items-center justify-between w-full py-5 font-medium text-left rtl:text-right text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400" data-accordion-target="#accordion-flush-body-1" aria-expanded="true" aria-controls="accordion-flush-body-1">
                                <span>VISION</span>
                                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5" />
                                </svg>
                            </button>
                        </h2>
                        <div id="accordion-flush-body-1" class="hidden" aria-labelledby="accordion-flush-heading-1">
                            <div class="py-5 border-b border-gray-200 dark:border-gray-700">
                                <p class="mb-2 text-gray-500 dark:text-gray-400">Somos una Institución privada de Educación Superior, con cobertura nacional, que ofrece a sus alumnos y colaboradores la oportunidad de adquirir una formación competitiva asumiendo la responsabilidad de contribuir al desarrollo sostenible de la sociedad.</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <section class="mx-auto py-8">
        <h2 class="text-4xl font-extrabold border-b text-blue-800 dark:text-white p-2 text-center justify-center">Objetivos del Sistema de Control de Evaluaciones</h2>

        <div class="grid grid-cols-2  mt-8">
            <div class="flex flex-col items-center  bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" src="others/img/mision.jpg" alt="">
                <div class="flex flex-col justify-between p-4 leading-normal text-center">
                    <h5 class="mb-2 border-b mt-2 text-2xl font-bold tracking-tight text-blue-800 dark:text-white">Objetivo Principal</h5>
                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Desarrollar un sistema web para el control de evaluaciones de los estudiantes en la asignatura de programación de la Universidad del Norte, sede Caacupé, año 2024.</p>
                </div>
            </div>

            <div class="flex flex-col items-center  bg-white border border-gray-200 rounded-lg shadow md:flex-row   dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg" src="others/img/objetivoPrincipal.png" alt="">
                <div class="flex flex-col justify-between p-4 leading-normal text-center">
                    <h5 class="mb-2 text-2xl border-b mt-2 font-bold tracking-tight text-blue-800 dark:text-white">Objetivo Especifico</h5>

                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">

                        <span class="leading-tight"> - Identificar los indicadores específicos en la evaluación de habilidades de programación y como puede abordarlos el sistema de evaluación de la Universidad del Norte, sede Caacupé, año 2024.</span>
                    </p>

                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">

                        <span class="leading-tight"> - Diseñar un módulo para evaluar las tareas de programación según el lenguaje de programación elegido en la asignatura.</span>
                    </p>

                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">

                        <span class="leading-tight"> - Desarrollar una arquitectura segura y eficiente para la corrección automática de evaluaciones en la asignatura de programación de la Universidad del Norte, sede Caacupé, año 2024.</span>
                    </p>
                </div>
            </div>

        </div>

    </section>
</div>


<?php
include "vistas/footer/footer-index.php";
?>