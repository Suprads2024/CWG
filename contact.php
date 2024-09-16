<?php
$destinatario = 'valen.soraka@icloud.com';

// Clave secreta de reCAPTCHA
$secretkey = "6Ld_1CcqAAAAALO__drQlTnNzyVmo5kr7qZ3PUer";

// Captura la respuesta del captcha y la IP del usuario
$captcha = $_POST['g-recaptcha-response'];
$ip = $_SERVER['REMOTE_ADDR'];

// Validación de Captcha
$respuesta = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretkey&response=$captcha&remoteip=$ip");
$atributos = json_decode($respuesta, true);

// Comprueba si la respuesta del captcha es válida
if (!$atributos['success']) {
    echo "Verificación de CAPTCHA fallida. Por favor, intenta de nuevo.";
    exit;
}

// Capturamos los datos enviados desde el formulario
$nombre = $_POST['nombre'];
$email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
$mensaje = $_POST['mensaje'];

// Construimos el mensaje del correo
$mensajeCorreo = "Mensaje de contacto:\n\n";
$mensajeCorreo .= "Nombre: " . $nombre . "\n";
$mensajeCorreo .= "Correo Electrónico: " . $email . "\n";
$mensajeCorreo .= "Mensaje: " . $mensaje . "\n";

// Asunto del correo
$asuntoCorreo = "Consulta de " . $nombre;

// Cabeceras del correo
$from = "valen.soraka@icloud.com"; // Usa un correo real asociado a tu dominio
$header = "From: Crypto Wolf Group<" . $from . ">\r\n";
$header .= "Reply-To: " . $email . "\r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-Type: text/plain; charset=UTF-8\r\n";
$header .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$header .= "X-Priority: 1\r\n"; // Opcional

// Enviamos el correo y manejamos la respuesta
if(mail($destinatario, $asuntoCorreo, $mensajeCorreo, $header)){
    // Redirecciona inmediatamente a la página de agradecimiento
    header("Location: gracias/");
    exit;
} else {
    ?>
    <h3 class="error">Ocurrió un error, vuelve a intentarlo</h3>
    <?php
}
?>
