<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

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
        $host     = $this->env('MAIL_HOST');
        $port     = (int)$this->env('MAIL_PORT', '587');
        $from     = $this->env('MAIL_FROM');
        $fromName = $this->env('MAIL_FROM_NAME', 'Ecom');

        if (empty($host)) {
            error_log('MailService: MAIL_HOST is empty');
            return false;
        }
        if (empty($from) || !filter_var($from, FILTER_VALIDATE_EMAIL)) {
            error_log("MailService: MAIL_FROM invalid: '$from'");
            return false;
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            error_log("MailService: recipient email invalid: '$email'");
            return false;
        }

        error_log("MailService: attempting SMTP host=$host port=$port from=$from to=$email");

        $mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug  = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = $host;
            $mail->Port       = $port;
            $mail->SMTPAuth   = false;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

            $mail->setFrom($from, $fromName);
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Mã OTP khôi phục mật khẩu';
            $mail->Body    = "
                <div style='font-family:sans-serif;max-width:480px;margin:auto;padding:24px;border:1px solid #eee;border-radius:8px'>
                  <h2 style='color:#c0392b'>Mã OTP của bạn</h2>
                  <p>Vui lòng dùng mã sau để đặt lại mật khẩu:</p>
                  <div style='font-size:36px;font-weight:bold;letter-spacing:8px;text-align:center;padding:16px;background:#f8f8f8;border-radius:6px'>$otp</div>
                  <p style='color:#888;font-size:12px;margin-top:16px'>Mã có hiệu lực trong 5 phút. Không chia sẻ mã này cho bất kỳ ai.</p>
                </div>";
            $mail->AltBody = "Mã OTP của bạn là: $otp (hiệu lực 5 phút)";

            $result = $mail->send();
            error_log("MailService: email sent successfully to $email");
            return $result;

        } catch (Exception $e) {
            throw new \RuntimeException("MailService SMTP failed. ErrorInfo={$mail->ErrorInfo} | {$e->getMessage()}");
        }
    }
}
