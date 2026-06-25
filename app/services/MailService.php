<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService {


    public function sendEmail(
        string $email, string $otp
    ): bool {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $_ENV['MAIL_HOST'];

            $username = $_ENV['MAIL_USERNAME'];
            $password = $_ENV['MAIL_PASSWORD'];
            $mail->SMTPAuth = !empty($username);
            if ($mail->SMTPAuth) {
                $mail->Username = $username;
                $mail->Password = $password;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->Port = (int)($_ENV['MAIL_PORT'] ?: 587);

            // From address fallback and validation
            $from =  $_ENV['MAIL_FROM'];
            $fromName =  $_ENV['MAIL_FROM_NAME'];

            if (empty($from) || !filter_var($from, FILTER_VALIDATE_EMAIL)) {
                error_log('MailService: missing or invalid MAIL_FROM; aborting sendEmail');
                return false;
            }

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                error_log("MailService: invalid recipient email: $email");
                return false;
            }
            
            
            $mail->setFrom($from, $fromName);
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code';
            $mail->Body    = "Your OTP code is: <b>$otp</b>";

            return $mail->send();
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}
