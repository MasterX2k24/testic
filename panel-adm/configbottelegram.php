<?php
// panel-adm/configbottelegram.php

// Inicia la sesión
session_start();

// Verifica si el usuario está autenticado, si no, redirige a la página de inicio de sesión
if (!isset($_SESSION["autenticado"]) || $_SESSION["autenticado"] !== true) {
    header("Location: login.php");
    exit;
}

// Incluye la configuración
require_once("obtenerconfig.php");

// Obtiene la configuración actual
$configuracion = obtenerConfiguracion();

// Maneja la actualización de la configuración
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["guardarConfiguracion"])) {
    $token = $_POST["token"];
    $chat_id = $_POST["chat_id"];

    // Guarda la nueva configuración
    guardarConfiguracion($token, $chat_id);

    // Recarga la página para reflejar los cambios
    header("Location: configbottelegram.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <?php include("uiux/header.php"); ?>
    <title>Configuración del BOT Telegram</title>
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <!-- Contenido principal -->
    <h1>Configuración del BOT Telegram</h1>

    <!-- Contenedor del formulario -->
    <div id="formulario-container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="token">Nuevo Token:</label>
            <div class="input-container">
                <input type="password" id="token" name="token"
                    value="<?php echo isset($configuracion['token']) ? $configuracion['token'] : ''; ?>" required>
                <button type="button" onclick="toggleMostrarOcultar('token')">👁️</button>
            </div>

            <label for="chat_id">Nuevo ID del Chat:</label>
            <div class="input-container">
                <input type="password" id="chat_id" name="chat_id"
                    value="<?php echo isset($configuracion['chat_id']) ? $configuracion['chat_id'] : ''; ?>" required>
                <button type="button" onclick="toggleMostrarOcultar('chat_id')">👁️</button>
            </div>

            <button type="submit" name="guardarConfiguracion">Guardar Configuración</button>
        </form>

        <button type="button" onclick="toggleMostrarOcultar('valoresActuales')">Mostrar/Ocultar Valores
            Actuales</button>
        <div id="valoresActuales" style="display: none;">
            <p>Token del bot actual:
                <?php echo isset($configuracion['token']) ? $configuracion['token'] : ''; ?>
            </p>
            <p>ID del chat actual:
                <?php echo isset($configuracion['chat_id']) ? $configuracion['chat_id'] : ''; ?>
            </p>
        </div>
    </div>
    <?php include("uiux/footer.php"); ?>


    <!-- Scripts de JavaScript si es necesario -->
    <script>
        function toggleMostrarOcultar(elementoId) {
            var elemento = document.getElementById(elementoId);
            var estiloActual = elemento.style.display;

            if (estiloActual === "none") {
                elemento.style.display = "block";
                if (elementoId === 'token' || elementoId === 'chat_id') {
                    document.getElementById(elementoId).type = 'text';
                }
            } else {
                elemento.style.display = "none";
                if (elementoId === 'token' || elementoId === 'chat_id') {
                    document.getElementById(elementoId).type = 'password';
                }
            }
        }
    </script>
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