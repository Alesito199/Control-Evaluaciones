# Usar una imagen base de PHP con Apache
FROM php:8.1-apache

# Instalar dependencias necesarias
RUN apt-get update && \
    apt-get install -y g++ && 

# Copiar archivos de la aplicación al contenedor
COPY . /var/www/html

# Copiar el script de verificación al contenedor
COPY alumnos/scripts/verify_code.sh /scripts/verify_code.sh

# Asegurarse de que el script tenga permisos de ejecución
RUN chmod +x /scripts/verify_code.sh

# Configuración del puerto en el contenedor
EXPOSE 80
