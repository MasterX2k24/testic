<?php
// panel-adm/configip.php

// Incluye la configuración
require_once("obtenerconfig.php");

// Inicia la sesión
session_start();

// Verifica si el usuario está autenticado, si no, redirige a la página de inicio de sesión
if (!isset($_SESSION["autenticado"]) || $_SESSION["autenticado"] !== true) {
    header("Location: login.php");
    exit;
}

// Obtiene la configuración actual
$configuracion = obtenerConfiguracion();

// Ruta del archivo para las IPs bloqueadas
$rutaArchivoIP = __DIR__ . '/ip_blocked.json';

// Ruta del archivo de registro de errores
$rutaArchivoLog = __DIR__ . '/error_log.txt';

// Función para registrar errores
function registrarError($mensaje)
{
    global $rutaArchivoLog;

    $mensajeError = "[" . date("Y-m-d H:i:s") . "] " . $mensaje . PHP_EOL;

    // Agrega el mensaje de error al archivo de registro
    file_put_contents($rutaArchivoLog, $mensajeError, FILE_APPEND);
}

// Maneja la actualización de la configuración
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["guardarConfiguracion"])) {
        $token = $_POST["token"];
        $chat_id = $_POST["chat_id"];

        // Guarda la nueva configuración
        guardarConfiguracion($token, $chat_id);
    } elseif (isset($_POST["guardarConfiguracionIP"])) {
        $ipBloqueada = $_POST["ip_bloqueada"];
        $nombreIP = $_POST["nombre_ip"];

        // Guarda la nueva configuración de IP bloqueada
        try {
            guardarConfiguracionIP($ipBloqueada, $nombreIP, $rutaArchivoIP);
        } catch (Exception $e) {
            // Registra el error en el archivo de registro
            registrarError($e->getMessage());
        }
    } elseif (isset($_POST["eliminarConfiguracionIP"])) {
        $ipAEliminar = $_POST["eliminar_ip"];

        // Elimina la IP de la configuración
        try {
            eliminarConfiguracionIP($ipAEliminar, $rutaArchivoIP);
        } catch (Exception $e) {
            // Registra el error en el archivo de registro
            registrarError($e->getMessage());
        }
    }

    // Recarga la página para reflejar los cambios
    header("Location: configip.php");
    exit;
}

// Función para guardar la configuración de IP bloqueada
function guardarConfiguracionIP($ipBloqueada, $nombreIP, $archivoIP)
{
    if (file_exists($archivoIP)) {
        $contenido = file_get_contents($archivoIP);
        $ipsBloqueadas = json_decode($contenido, true);

        if ($ipsBloqueadas === null) {
            $ipsBloqueadas = [];
        }
    } else {
        $ipsBloqueadas = [];
    }

    // Agrega la nueva IP y nombre a la lista
    $ipsBloqueadas[] = ['ip' => $ipBloqueada, 'nombre' => $nombreIP];

    // Guarda la configuración en el archivo
    $configuracionIP = json_encode($ipsBloqueadas, JSON_PRETTY_PRINT);

    if (file_put_contents($archivoIP, $configuracionIP) === false) {
        // Si la escritura falla, lanza una excepción
        throw new Exception("Error al guardar la configuración de IP bloqueada.");
    }
}

// Función para eliminar la configuración de IP
function eliminarConfiguracionIP($ipAEliminar, $archivoIP)
{
    if (file_exists($archivoIP)) {
        $contenido = file_get_contents($archivoIP);
        $ipsBloqueadas = json_decode($contenido, true);

        if ($ipsBloqueadas !== null) {
            // Elimina la IP de la lista
            foreach ($ipsBloqueadas as $key => $ip) {
                if ($ip['ip'] === $ipAEliminar) {
                    unset($ipsBloqueadas[$key]);
                    break;
                }
            }

            // Guarda la configuración actualizada en el archivo
            $configuracionIP = json_encode($ipsBloqueadas, JSON_PRETTY_PRINT);

            if (file_put_contents($archivoIP, $configuracionIP) === false) {
                // Si la escritura falla, lanza una excepción
                throw new Exception("Error al eliminar la configuración de IP bloqueada.");
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <?php include("uiux/header.php"); ?>
    <title>Configuración de IP</title>
    <link rel="stylesheet" href="panel-adm/css/main.css">
    <style>
        .formip {
            display: inline;
            margin: 0;
        }

        .ip-container-wrapper {
            max-width: 600px;
            /* Ajusta según tus preferencias */
            margin: 10 auto;
            /* Centra el contenedor horizontalmente */
        }

        .ip-container {
            border: 1px solid #ccc;
            /* Agrega un borde al contenedor de cada IP */
            padding: 10px;
            /* Ajusta según tus preferencias */
            margin-bottom: 10px;
            /* Ajusta según tus preferencias */
        }

        .ip-info {
            margin-bottom: 10px;
            /* Ajusta según tus preferencias */
        }

        .buttonx {
            background: none;
            border: none;
            color: red;
            cursor: pointer;
            font-size: inherit;
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <!-- Contenido principal -->
    <h1>Configuración de IP</h1>
    <!-- Contenedor de configuración de IP -->
    <div id="configuracionIP-container">
        <!-- Formulario para configurar IP bloqueada y buscar -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="nombre_ip">Nombre:</label>
            <input type="text" id="nombre_ip" name="nombre_ip" required>

            <label for="ip_bloqueada">IP a Bloquear:</label>
            <input type="text" id="ip_bloqueada" name="ip_bloqueada" required>

            <button type="submit" name="guardarConfiguracionIP">Guardar Configuración de IP Bloqueada</button>
        </form>

        <!-- Formulario de búsqueda -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
            <label for="buscar_ip">Buscar IP/Nombre:</label>
            <input type="text" id="buscar_ip" name="buscar_ip">
            <button type="submit" name="buscarIP">Buscar</button>
        </form>

        <!-- Lista de IPs bloqueadas -->
        <?php
        if (file_exists($rutaArchivoIP)) {
            $contenido = file_get_contents($rutaArchivoIP);
            $ipsBloqueadas = json_decode($contenido, true);

            if ($ipsBloqueadas !== null && !empty($ipsBloqueadas)) {
                echo '<h2>IPs Bloqueadas:</h2>';
                echo '<div class="ip-container-wrapper">'; // Contenedor para limitar el ancho
                echo '<ul>';
                foreach ($ipsBloqueadas as $ip) {
                    echo '<li>';
                    echo '<div class="ip-container">';
                    echo '    <div class="ip-info">';
                    echo '        <strong>Nombre:</strong> ' . htmlspecialchars($ip['nombre']) . '<br>';
                    echo '        <strong>IP Numerica:</strong> ' . htmlspecialchars($ip['ip']);
                    echo '    </div>';
                    echo '    <form class="formip" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post">';
                    echo '        <input type="hidden" name="eliminar_ip" value="' . $ip['ip'] . '">';
                    echo '        <button class="buttonx" type="submit" name="eliminarConfiguracionIP">X</button>';
                    echo '    </form>';
                    echo '</div>';
                    echo '</li>';
                }
                echo '</ul>';
                echo '</div>'; // Cierre del contenedor
            }
        }

        // Procesa la búsqueda de IP
        if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["buscarIP"])) {
            $terminoBusqueda = $_GET["buscar_ip"];

            // Filtra las IPs bloqueadas que coinciden con el término de búsqueda
            $ipsCoincidentes = array_filter($ipsBloqueadas, function ($ip) use ($terminoBusqueda) {
                return strpos($ip['nombre'], $terminoBusqueda) !== false || strpos($ip['ip'], $terminoBusqueda) !== false;
            });

            // Muestra las IPs coincidentes
            if (!empty($ipsCoincidentes)) {
                echo '<h2>IPs Bloqueadas (Coincidentes con la Búsqueda):</h2>';
                echo '<div class="ip-container-wrapper">';
                echo '<ul>';
                foreach ($ipsCoincidentes as $ip) {
                    echo '<li>';
                    echo "Nombre: {$ip['nombre']}<br>";
                    echo "IP Numerica: {$ip['ip']}";
                    echo '<form class="formip" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '" method="post" style="display:inline;">';
                    echo '<input type="hidden" name="eliminar_ip" value="' . $ip['ip'] . '">';
                    echo '<button class="buttonx" type="submit" name="eliminarConfiguracionIP">X</button>';
                    echo '</form>';
                    echo '</li>';
                }
                echo '</ul>';
                echo '</div>';
            } else {
                echo '<p>No se encontraron IPs coincidentes.</p>';
            }
        }
        ?>
    </div>
    <?php include("uiux/footer.php"); ?>
    <script>
        // Initialising the canvas
        var canvas = document.querySelector('canvas'),
            ctx = canvas.getContext('2d');

        // Setting the width and height of the canvas
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;

        // Setting up the letters
        var letters = 'ABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZABCDEFGHIJKLMNOPQRSTUVXYZ';
        letters = letters.split('');

        // Setting up the columns
        var fontSize = 10,
            columns = canvas.width / fontSize;

        // Setting up the drops
        var drops = [];
        for (var i = 0; i < columns; i++) {
            drops[i] = 1;
        }

        // Setting up the draw function
        function draw() {
            ctx.fillStyle = 'rgba(0, 0, 0, .1)';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            for (var i = 0; i < drops.length; i++) {
                var text = letters[Math.floor(Math.random() * letters.length)];
                ctx.fillStyle = '#0f0';
                ctx.fillText(text, i * fontSize, drops[i] * fontSize);
                drops[i]++;
                if (drops[i] * fontSize > canvas.height && Math.random() > .95) {
                    drops[i] = 0;
                }
            }
        }

        // Loop the animation
        setInterval(draw, 33);
    </script>
</body>

</html>