<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $submittedCode = $_POST['code'] ?? '';
    $storedCode = $_SESSION['verification_code'] ?? '';
    $storedTime = $_SESSION['verification_time'] ?? 0;
    
    // Check if code has expired (10 minutes validity)
    if (time() - $storedTime > 600) {
        echo json_encode(['success' => false, 'message' => 'Verification code has expired']);
        exit;
    }
    
    if ($submittedCode === $storedCode) {
        // Clear verification data
        unset($_SESSION['verification_code']);
        unset($_SESSION['verification_time']);
        
        echo json_encode(['success' => true, 'message' => 'Code verified successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid verification code']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
