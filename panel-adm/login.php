<?php
// panel-adm/login.php

// Incluye la función para obtener las credenciales
require_once("./obtenerconfig.php");

// Inicia la sesión
session_start();

// Verifica si el usuario ya está autenticado
if (isset($_SESSION["autenticado"]) && $_SESSION["autenticado"] === true) {
    header("Location: index.php");
    exit;
}

// Verifica si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtiene las credenciales almacenadas
    $credenciales = obtenerCredenciales();

    // Obtiene las credenciales ingresadas por el usuario
    $usuario_ingresado = $_POST["usuario"];
    $contrasena_ingresada = $_POST["contrasena"];

    // Verifica si las credenciales son correctas
    if ($usuario_ingresado == $credenciales["usuario"] && $contrasena_ingresada == $credenciales["contrasena"]) {
        // Autenticación exitosa
        $_SESSION["autenticado"] = true;
        header("Location: index.php");
        exit;
    } else {
        // Credenciales incorrectas
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/main.css">
</head>

<body>
    <h1>Iniciar Sesión</h1>

    <!-- Formulario de inicio de sesión -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="usuario">Usuario:</label>
        <input type="text" id="usuario" name="usuario" required>

        <label for="contrasena">Contraseña:</label>
        <input type="password" id="contrasena" name="contrasena" required>

        <button type="submit">Iniciar Sesión</button>
        <?php if (isset($error)): ?>
            <p style="color: red;">
                <?php echo $error; ?>
            </p>
        <?php endif; ?>

    </form>
</body>

</html>