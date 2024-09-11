<?php
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: index.html");
    exit;
}

require 'phpmailer/PHPMailer.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;

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

// Envios
$nombre = htmlspecialchars(trim($_POST['nombre']));
$email = htmlspecialchars(trim($_POST['email']));
$mensaje = htmlspecialchars(trim($_POST['mensaje']));

if (empty(trim($nombre))) $nombre = 'Anónimo';

// Cuerpo del mensaje
$body = <<<HTML
    <h2>Contacto desde la web</h2>
    <p>De: $nombre <br> Email: $email</p>
    <h3>Mensaje</h3>
    <p>$mensaje</p>
HTML;

// Configuración de PHPMailer
$mailer = new PHPMailer();
$mailer->setFrom($email, $nombre);
$mailer->addAddress('ignaciosoraka@gmail.com', 'Sitio web');
$mailer->Subject = "Mensaje de $nombre desde la web";
$mailer->msgHTML($body);
$mailer->AltBody = strip_tags($body);
$mailer->CharSet = 'UTF-8';

// Envía el correo
$rta = $mailer->send();

if ($rta) {
    header("Location: gracias.html");
} else {
    echo "Hubo un problema al enviar el mensaje. Por favor, intenta de nuevo.";
}
?>
