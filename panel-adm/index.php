<?php
//panel-adm/index.php

// Inicia la sesión
session_start();

// Verifica si el usuario está autenticado, si no, redirige a la página de inicio de sesión
if (!isset($_SESSION["autenticado"]) || $_SESSION["autenticado"] !== true) {
    header("Location: login.php");
    exit;
}
// Obtener información de la VPS
$ipVPS = $_SERVER['SERVER_ADDR'];
$sistemaOperativo = php_uname('s');
$horaLocal = date('Y-m-d H:i:s');

// Obtener información adicional
$ramUsada = round((memory_get_usage(true) / 1024) / 1024, 2); // En megabytes
$ramDisponible = round((memory_get_peak_usage(true) / 1024) / 1024, 2); // En megabytes
$espacioUsado = round(disk_total_space('/') - disk_free_space('/'), 2); // En gigabytes
$espacioDisponible = round(disk_free_space('/') / (1024 * 1024 * 1024), 2); // En gigabytes


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <?php include("uiux/header.php"); ?>
    <title>PANEL DE ADMINISTRACION</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        body {
            /* imagen matrix */
            background-image: url('https://c4.wallpaperflare.com/wallpaper/103/807/711/abstract-the-matrix-wallpaper-preview.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .vps-info-container {
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 5px;
            width: 100%;
        }

        /* Estilo para el fondo GIF */
        .gif-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        .vps-info h2 {
            color: #3498db;
        }

        .vps-info-container2 {
            background-color: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            border-radius: 5px;
            width: 100%;
        }

        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 60px;
            padding-left: 10px;
            justify-content: center;
        }

        footer {
            background-color: #f8f9fa;
            text-align: center;
            padding: 20px;
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>

<body>
    <h1>Panel de Administración [v1.0]</h1>

    <!-- Bloque de información de la VPS -->
    <div class="grid-container">
        <div class="vps-info-container">
            <h2>Información de la VPS</h2>
            <p>IP:
                <?php echo $ipVPS; ?>
            </p>
            <p>Sistema Operativo:
                <?php echo $sistemaOperativo; ?>
            </p>
            <p>Horario Local:
                <?php echo $horaLocal; ?>
            </p>
        </div>
        <!-- Bloque de información 2 de la VPS -->
        <div class="vps-info-container2">
            <h2>Información Adicional</h2>
            <p>RAM Usada / Disponible:
                <?php echo $ramUsada; ?> MB /
                <?php echo $ramDisponible; ?> MB
            </p>
            <p>Espacio Usado / Disponible:
                <?php echo $espacioUsado; ?> GB /
                <?php echo $espacioDisponible; ?> GB
            </p>
        </div>
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