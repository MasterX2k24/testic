<?php
// editarenvio.php

// Inicia la sesión
session_start();

// Verifica si el usuario está autenticado, si no, redirige a la página de inicio de sesión
if (!isset($_SESSION["autenticado"]) || $_SESSION["autenticado"] !== true) {
    header("Location: login.php");
    exit;
}

// Maneja la actualización del mensaje personalizado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["guardarMensaje"])) {
        $mensajePersonalizado = $_POST["mensaje_personalizado"];
        $nombreApp = $_POST["nombre_app"];
        $firma = $_POST["firma"];

        // Guarda el nuevo mensaje personalizado
        guardarMensajePersonalizado($mensajePersonalizado, $nombreApp, $firma);

        // Establece el mensaje de éxito
        $mensajeExito = "Nuevo mensaje guardado con éxito! ✅";
    }
}

// Función para guardar el mensaje personalizado
function guardarMensajePersonalizado($mensajePersonalizado, $nombreApp, $firma)
{
    $archivoMensaje = __DIR__ . '/mensaje.json';

    // Lee el contenido actual del archivo
    $contenido = file_get_contents($archivoMensaje);

    // Decodifica el contenido JSON
    $datos = json_decode($contenido, true);

    // Actualiza el mensaje personalizado
    $datos['mensajePersonalizado'] = $mensajePersonalizado;

    // Agrega o actualiza NombreDeAPP y FIRMA
    $datos['nombreApp'] = $nombreApp;
    $datos['firma'] = $firma;

    // Codifica el contenido de nuevo
    $nuevoContenido = json_encode($datos, JSON_PRETTY_PRINT);

    // Guarda el contenido actualizado en el archivo
    file_put_contents($archivoMensaje, $nuevoContenido);
}

// Obtiene el mensaje personalizado actual y los nuevos campos
$mensajePersonalizadoActual = json_decode(file_get_contents('mensaje.json'), true)['mensajePersonalizado'];
$nombreAppActual = isset($datos['nombreApp']) ? $datos['nombreApp'] : '';
$firmaActual = isset($datos['firma']) ? $datos['firma'] : '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <?php include("uiux/header.php"); ?>
    <title>Configuración del SENDER</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }

        h1 {
            text-align: center;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        textarea,
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            box-sizing: border-box;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #messagePreview {
            max-width: 600px;
            margin: 20px auto;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f5f5f5;
            text-align: center;
        }

        #messagePreview strong {
            display: block;
            color: #0088cc;
        }

        #messagePreview p {
            margin: 10px 0;
        }

        #messagePreview p:last-child {
            margin-bottom: 0;
        }
    </style>
</head>

<body>
    <!-- Contenido principal -->
    <h1>Configuración del SENDER</h1>

    <?php
    // Obtiene el mensaje personalizado actual y los nuevos campos
    $datos = json_decode(file_get_contents('mensaje.json'), true);
    $mensajePersonalizadoActual = $datos['mensajePersonalizado'];
    $nombreAppActual = isset($datos['nombreApp']) ? $datos['nombreApp'] : '';
    $firmaActual = isset($datos['firma']) ? $datos['firma'] : '';
    ?>

    <!-- Formulario para editar el mensaje personalizado -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="mensaje_personalizado">Mensaje Personalizado:</label>
        <textarea id="mensaje_personalizado" name="mensaje_personalizado" rows="4"
            required><?php echo $mensajePersonalizadoActual; ?></textarea>

        <!-- Nuevo campo para NombreDeAPP -->
        <label for="nombre_app">Nombre de la APP:</label>
        <input type="text" id="nombre_app" name="nombre_app" value="<?php echo $nombreAppActual; ?>" required>

        <!-- Nuevo campo para FIRMA -->
        <label for="firma">FIRMA:</label>
        <input type="text" id="firma" name="firma" value="<?php echo $firmaActual; ?>" required>

        <button type="submit" name="guardarMensaje">Guardar Mensaje Personalizado</button>
    </form>
    <div id="messagePreview">
        <strong>
            <?php echo $nombreAppActual; ?>
        </strong>
        <p>
            <?php echo nl2br($mensajePersonalizadoActual); ?>
        </p>
        <p><em>
                <?php echo $firmaActual; ?>
            </em></p>
    </div>
    <?php include("uiux/footer.php"); ?>

    <!-- Script para mostrar el mensaje de éxito -->
    <script>
        <?php
        if (!empty($mensajeExito)) {
            echo "alert('$mensajeExito');";
        }
        ?>
    </script>
</body>

</html>