<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService {

    private function env(string $key, string $default = ''): string
    {
        $value = $_ENV[$key] ?? getenv($key);
        if ($value === false || $value === null) {
            return $default;
        }
        return trim((string)$value);
    }

    public function sendEmail(
        string $email, string $otp
    ): bool {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = $this->env('MAIL_HOST');

            if (empty($mail->Host)) {
                error_log('MailService: missing MAIL_HOST; aborting sendEmail');
                return false;
            }

            $username = $this->env('MAIL_USERNAME');
            $password = $this->env('MAIL_PASSWORD');
            $mail->SMTPAuth = !empty($username);
            if ($mail->SMTPAuth) {
                $mail->Username = $username;
                $mail->Password = $password;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            }

            $mail->Port = (int)$this->env('MAIL_PORT', '587');

            
            $from =  $this->env('MAIL_FROM');
            $fromName =  $this->env('MAIL_FROM_NAME');

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
