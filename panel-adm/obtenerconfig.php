<?php
// panel-adm/obtenerconfig.php
// Función para obtener la configuración desde el archivo config.json
function obtenerConfiguracion()
{
    $archivo = __DIR__ . '/config.json';

    if (!file_exists($archivo)) {
        die('Error: el archivo config.json no existe.');
    }

    $contenido = file_get_contents($archivo);

    if ($contenido === false) {
        die('Error al leer el archivo config.json.');
    }

    $configuracion = json_decode($contenido, true);

    if ($configuracion === null) {
        die('Error al decodificar el contenido de config.json.');
    }

    return $configuracion;
}

// Función para obtener información de usuario y contraseña desde config.json
// panel-adm/obtenerconfig.php

function obtenerCredenciales()
{
    // Incluye el archivo con las credenciales de ingreso
    require_once("datosingreso.php");

    // Retorna las credenciales obtenidas desde datosingreso.php
    return obtenerDatosIngreso();
}


// Función para guardar la configuración en el archivo config.json
function guardarConfiguracion($token, $chat_id)
{
    $archivo = __DIR__ . '/config.json';

    // Lee la configuración actual
    $configuracion = obtenerConfiguracion();

    // Actualiza el token y el chat_id
    $configuracion['token'] = $token;
    $configuracion['chat_id'] = $chat_id;

    // Guarda la configuración actualizada en el archivo
    file_put_contents($archivo, json_encode($configuracion, JSON_PRETTY_PRINT));
}

?>