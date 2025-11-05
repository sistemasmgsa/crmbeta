<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../libs/PHPMailer/src/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../config/config.php';

function enviar_correo($destinatario, $asunto, $cuerpo) {
    $mail = new PHPMailer(true);

    try {
        //Configuración del servidor
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = SMTP_PORT;

        //Destinatarios
        $mail->setFrom(SMTP_USER, 'Advisor CRM');
        $mail->addAddress($destinatario);

        //Contenido
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body    = $cuerpo;

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
