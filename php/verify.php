<?php
require_once 'db.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $conn->prepare("SELECT id FROM users WHERE verification_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $update = $conn->prepare("UPDATE users SET email_verified = 1, verification_token = '' WHERE verification_token = ?");
        $update->bind_param("s", $token);
        if ($update->execute()) {
            echo "Email verified successfully! You can now <a href='login.html'>login</a>.";
        } else {
            echo "Verification failed.";
        }
    } else {
        echo "Invalid or expired token.";
    }
} else {
    echo "No token provided.";
}
?>
