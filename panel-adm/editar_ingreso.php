<?php
// panel-adm/editar_ingreso.php

// Inicia la sesión
session_start();

// Verifica si el usuario está autenticado, si no, redirige a la página de inicio de sesión
if (!isset($_SESSION["autenticado"]) || $_SESSION["autenticado"] !== true) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
        $nuevoUsuario = $_POST['usuario'];
        $nuevaContrasena = $_POST['contrasena'];

        // Actualiza los datos de ingreso
        guardarDatosIngreso($nuevoUsuario, $nuevaContrasena);

        echo "Datos de ingreso actualizados con éxito.";
    } else {
        echo "Por favor, proporciona un usuario y una contraseña.";
    }
}

// Función para guardar las nuevas credenciales en datosingreso.php
function guardarDatosIngreso($nuevoUsuario, $nuevaContrasena)
{
    // Abre el archivo para escritura
    $archivo = fopen("datosingreso.php", "w");

    // Escribe el contenido del archivo con las nuevas credenciales
    fwrite($archivo, '<?php' . PHP_EOL);
    fwrite($archivo, 'function obtenerDatosIngreso()' . PHP_EOL);
    fwrite($archivo, '{' . PHP_EOL);
    fwrite($archivo, '    return [' . PHP_EOL);
    fwrite($archivo, '        "usuario" => "' . $nuevoUsuario . '",' . PHP_EOL);
    fwrite($archivo, '        "contrasena" => "' . $nuevaContrasena . '",' . PHP_EOL);
    fwrite($archivo, '    ];' . PHP_EOL);
    fwrite($archivo, '}' . PHP_EOL);
    fwrite($archivo, '?>' . PHP_EOL);

    // Cierra el archivo después de escribir
    fclose($archivo);
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <?php include("uiux/header.php"); ?>
    <title>Editar Credenciales</title>
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <h1>Editar Credenciales</h1>

    <?php if (isset($mensajeExito)): ?>
        <p style="color: green;">
            <?php echo $mensajeExito; ?>
        </p>
    <?php endif; ?>

    <!-- Formulario para cambiar las credenciales -->
    <form method="POST">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>

        <input type="submit" value="Actualizar">
    </form>
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