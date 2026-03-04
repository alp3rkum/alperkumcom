<?php
session_start();
require '../functions/phpmailer.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'csrf_error', 'message' => 'Geçersiz istek.']);
        exit;
    }

    $name    = $_POST['name'] ?? '';
    $email   = $_POST['email'] ?? '';
    $phone   = $_POST['phone'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $message = $_POST['message'] ?? '';

    $toEmail = 'alperkum.cs@gmail.com';

    if (empty($email) || empty($message)) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'validation_error', 'message' => 'Lütfen e-posta adresinizi ve mesajınızı girin.']);
        exit;
    }

    $htmlBody = $message;

    $sendResult = sendEmail(
        $email,
        $name,
        $toEmail,
        $subject,
        $htmlBody
    );

    echo json_encode($sendResult);
}
?>