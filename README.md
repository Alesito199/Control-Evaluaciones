# 📚 Sistema de Gestión de Exámenes de Programación - Universidad del Norte

## 🎓 Proyecto de Titulación

Sistema web desarrollado como trabajo de titulación para la Universidad del Norte, enfocado en la gestión y evaluación automática de exámenes de programación.

## 📋 Descripción

Esta plataforma permite a docentes crear, gestionar y calificar exámenes de programación de manera automatizada, mientras que los estudiantes pueden rendir sus evaluaciones con corrección inmediata de código. El sistema verifica la sintaxis y compilación del código mediante un script de validación automatizado.

### Características Principales

- ✅ **Evaluación Automática**: Verificación de código en tiempo real
- 👥 **Gestión de Usuarios**: Administradores, docentes, alumnos y secretarios
- 📝 **Creación de Exámenes**: Interface intuitiva para docentes
- 📊 **Seguimiento de Asistencia**: Registro de asistencia de estudiantes
- 📅 **Calendario Académico**: Gestión de fechas importantes
- 📄 **Material Didáctico**: Compartir recursos educativos
- 🎯 **Asignación de Cursos**: Gestión de materias y asignaciones

## 💻 Lenguajes de Programación Soportados

El sistema actualmente soporta la evaluación de código en:

- **C++** (Lenguaje principal)
- **C** (Compatible con el compilador g++)

La verificación se realiza mediante el compilador `g++` que puede compilar tanto código C++ como C.

## 🏗️ Arquitectura del Sistema

### Roles de Usuario

1. **Administrador** 
   - Gestión de alumnos, docentes y secretarios
   - Asignación de cursos y asignaturas
   - Administración del calendario académico

2. **Docente**
   - Creación y gestión de exámenes
   - Corrección de evaluaciones
   - Registro de asistencia
   - Subir material didáctico

3. **Alumno**
   - Rendir exámenes pendientes
   - Visualizar calificaciones
   - Acceso a material de estudio

4. **Secretario**
   - Gestión administrativa
   - Soporte en la gestión de cursos

## 🛠️ Tecnologías Utilizadas

### Backend
- **PHP 8.1** con Apache
- **MySQL** (Base de datos)
- **PDO** para conexión a base de datos

### Frontend
- **HTML5 / CSS3**
- **JavaScript**
- **Tailwind CSS 3.4.4** (Framework CSS)
- **Flowbite 2.3.0** (Componentes UI)
- **Flowbite Datepicker 1.2.7**

### DevOps
- **Docker & Docker Compose** (Containerización)
- **Bash Scripts** (Verificación de código)
- **g++** (Compilador C/C++)

## 📁 Estructura del Proyecto

```
Tesis-Control/
├── admin/              # Panel de administración
├── alumnos/           # Módulo de estudiantes
│   ├── examen-alumno/ # Sistema de exámenes
│   └── scripts/       # Scripts de verificación
├── docente/           # Módulo de docentes
│   └── build-examen/  # Constructor de exámenes
├── include/
│   ├── database/      # Configuración de BD
│   └── funciones/     # Funciones auxiliares
├── vistas/
│   ├── header/        # Cabeceras por rol
│   └── footer/        # Pies de página
├── css/               # Estilos personalizados
├── js/                # Scripts JavaScript
├── others/            # Recursos adicionales
├── docker-compose.yml # Configuración Docker
├── Dockerfile         # Imagen Docker
└── tailwind.config.js # Configuración Tailwind
```

## 🚀 Instalación y Configuración

### Requisitos Previos

- Docker y Docker Compose
- Node.js (para compilar assets de Tailwind)
- Servidor MySQL (si no usa Docker)

### Instalación con Docker (Recomendado)

1. **Clonar el repositorio**
   ```bash
   git clone <url-del-repositorio>
   cd Tesis-Control
   ```

2. **Configurar variables de entorno**
   
   Crear un archivo `.env` con las siguientes variables:
   ```env
   DB_HOST=localhost
   DB_PORT=3306
   DB_USER=root
   DB_PASSWORD=tu_password
   DB_NAME=prueba20
   ```

3. **Instalar dependencias de Node.js**
   ```bash
   npm install
   ```

4. **Construir y levantar los contenedores**
   ```bash
   docker-compose up -d --build
   ```

5. **Acceder a la aplicación**
   
   Abrir el navegador en: `http://localhost`

### Instalación Manual (WAMP/XAMPP)

1. **Clonar el repositorio** en la carpeta `www` o `htdocs`

2. **Instalar g++** (para Windows):
   - Descargar e instalar MinGW o MSYS2
   - Agregar g++ al PATH del sistema

3. **Configurar la base de datos**
   - Importar el esquema de la base de datos
   - Editar [include/database/database.php](include/database/database.php) con las credenciales correctas

4. **Instalar dependencias de Node.js**
   ```bash
   npm install
   ```

5. **Compilar assets de Tailwind** (si es necesario)
   ```bash
   npx tailwindcss -i ./css/style.css -o ./css/output.css --watch
   ```

## 🔧 Configuración de la Base de Datos

Editar el archivo [include/database/database.php](include/database/database.php):

```php
$host = 'localhost';
$dbname = 'prueba20';
$username = 'root';
$password = '';
```

## 🧪 Sistema de Verificación de Código

El sistema utiliza un script Bash para verificar la sintaxis y compilación del código:

**Ubicación**: [alumnos/scripts/verify_code.sh](alumnos/scripts/verify_code.sh)

### Funcionamiento

1. Recibe el archivo de código como parámetro
2. Compila el código usando `g++`
3. Verifica el estado de la compilación
4. Retorna éxito o error
5. Limpia archivos temporales

```bash
#!/bin/bash
archivo=$1
g++ $archivo -o ${archivo}.out

if [ $? -eq 0 ]; then
    echo "El código esta correctamente."
else
    echo "Error en la compilación del código."
fi

rm -f $archivo ${archivo}.out
```

## 📖 Uso del Sistema

### Para Docentes

1. **Iniciar sesión** con credenciales de docente
2. **Crear un examen**:
   - Ir a "Crear Examen"
   - Definir título, descripción y fecha
   - Agregar preguntas de programación
3. **Revisar exámenes**: Ver resultados y calificaciones automáticas
4. **Gestionar asistencia**: Registrar asistencia de estudiantes

### Para Alumnos

1. **Iniciar sesión** con credenciales de estudiante
2. **Ver exámenes pendientes**
3. **Rendir examen**:
   - Escribir código en el editor
   - El sistema verifica automáticamente la sintaxis
   - Enviar respuestas
4. **Ver resultados** inmediatos

### Para Administradores

1. **Gestionar usuarios**: Crear y administrar cuentas
2. **Asignar cursos**: Vincular docentes con asignaturas
3. **Configurar calendario**: Establecer fechas académicas

## 🐳 Docker

### Imagen Docker

El proyecto incluye un `Dockerfile` que:
- Usa PHP 8.1 con Apache
- Instala el compilador g++
- Configura permisos para el script de verificación
- Expone el puerto 80

### Docker Compose

Orquesta los servicios necesarios:
- Servicio web (PHP + Apache)
- Configuración de variables de entorno
- Mapeo de puertos

## 🔒 Seguridad

- ✅ Conexión a base de datos mediante PDO (previene SQL Injection)
- ✅ Sesiones para manejo de autenticación
- ✅ Validación de código en entorno aislado
- ⚠️ **Nota**: En producción, configurar adecuadamente las credenciales de la base de datos

## 🤝 Contribuciones

Este proyecto fue desarrollado como trabajo de titulación por **Alejandro Aquino**. Para consultas o sugerencias, contactar a: alexs199.ale@gmail.com

## 📄 Licencia

Proyecto académico desarrollado para la Universidad del Norte.

## 👨‍💻 Autor

**Alejandro Aquino**
- 📧 Email: alexs199.ale@gmail.com
- 🎓 Proyecto de Titulación - Universidad del Norte

---

## 📞 Soporte

Para problemas o consultas relacionadas con el sistema:
- Contactar a: **Alejandro Aquino**
- Email: alexs199.ale@gmail.com

## 🎯 Trabajo Futuro

Posibles mejoras para futuras versiones:
- Soporte para más lenguajes (Python, Java)
- Ejecución de casos de prueba automatizados
- Sistema de plagios
- Exportación de reportes en PDF
- API REST para integración con otros sistemas
- Modo oscuro completo
- Notificaciones en tiempo real

---

