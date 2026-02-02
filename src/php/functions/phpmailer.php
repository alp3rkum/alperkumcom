<?php
require($_SERVER['DOCUMENT_ROOT'] . '/functions/db.php');
$database = Database::getInstance();
$conn = $database->getConnection();

require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendEmail($fromEmail, $fromName, $toAddresses, $subject, $body) {
    global $database;
    $smtp_vals = $database->getGlobalVars("smtp_host","smtp_email","smtp_pass");
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $smtp_vals['smtp_host'];
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_vals['smtp_email'];
        $mail->Password = $smtp_vals['smtp_pass'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom($fromEmail, $fromName);

        if (is_array($toAddresses)) {
            foreach ($toAddresses as $address) {
                $mail->addAddress($address);
            }
        } else {
            $mail->addAddress($toAddresses);
        }

        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return [
            'success' => true,
            'message' => 'Mesaj başarıyla gönderildi!'
        ];
    } catch (Exception $e) {
        return [
            'success' => false,
            'message' => 'Mesaj gönderilemedi. Hata: ' . $mail->ErrorInfo
        ];
    }
}
?>