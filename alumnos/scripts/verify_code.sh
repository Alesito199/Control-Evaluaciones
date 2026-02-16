#!/bin/bash

# Almacena el nombre del archivo pasado como argumento
archivo=$1
# Intenta compilar el archivo usando g++
g++ $archivo -o ${archivo}.out

# Verifica si la compilación fue exitosa
if [ $? -eq 0 ]; then
    # Mensaje en caso de éxito
    echo "El código esta correctamente."
else
    # Mensaje en caso de error
    echo "Error en la compilación del código."
fi

# Elimina el archivo temporal y el archivo compilado
rm -f $archivo ${archivo}.out
