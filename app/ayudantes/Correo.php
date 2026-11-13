<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Correo {

    // Envía un correo HTML usando la configuración SMTP del .env. Devuelve true/false.
    public static function enviar($destino, $nombreDestino, $asunto, $html) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = $_ENV['MAIL_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['MAIL_USERNAME'];
            $mail->Password   = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
            $mail->Port       = (int) $_ENV['MAIL_PORT'];
            $mail->CharSet    = 'UTF-8';

            $mail->setFrom($_ENV['MAIL_USERNAME'], $_ENV['MAIL_FROM_NAME'] ?? 'GAMASTORE');
            $mail->addAddress($destino, $nombreDestino);

            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = $html;
            $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '</p>'], "\n", $html));

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('Error al enviar correo: ' . $mail->ErrorInfo);
            return false;
        }
    }
}