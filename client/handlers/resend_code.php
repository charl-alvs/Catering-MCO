<?php
session_start();
require_once "../../config/conDB.php";
require_once "../../config/mailer.php";

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['order_id']) || !isset($_SESSION['user_email'])) {
        throw new Exception("Session expired. Please try ordering again.");
    }

    // Generate new verification code
    $newVerificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $_SESSION['verification_code'] = $newVerificationCode;
    
    // Send new verification code
    if (sendCode($_SESSION['user_email'], $newVerificationCode)) {
        echo json_encode(['success' => true, 'message' => 'New verification code sent successfully']);
    } else {
        throw new Exception("Failed to send verification code. Please try again.");
    }
} catch (Exception $e) {
    error_log("Resend Code Error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
