<?php
session_start();
require_once '../config/mail_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Generate 6-digit code
        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Store code in session
        $_SESSION['verification_code'] = $verificationCode;
        $_SESSION['verification_email'] = $email;
        $_SESSION['verification_time'] = time();
        
        // Send email
        if (sendVerificationCode($email, $verificationCode)) {
            echo json_encode(['success' => true, 'message' => 'Verification code sent successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send verification code']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
