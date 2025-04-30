<?php
require_once 'db.php';    
require_once 'mailer.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = htmlspecialchars($_POST["firstname"]);
    $middlename = htmlspecialchars($_POST["middlename"]);
    $lastname = htmlspecialchars($_POST["lastname"]);
    $birthday = $_POST["birthday"];
    $email = $_POST["email"];
    $mobile = $_POST["mobile"];

    if ($_POST["password"] !== $_POST["confirmpassword"]) {
        die("Passwords do not match.");
    }

    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(50));

    $stmt = $conn->prepare("INSERT INTO users (firstname, middlename, lastname, birthday, email, mobile, password, verification_token) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $firstname, $middlename, $lastname, $birthday, $email, $mobile, $password, $token);

    // Still not working
    if ($stmt->execute()) {
        $verification_link = "http://localhost/verify.php?token=" . $token;
        $subject = "Gravity Well Space FX - Verify Your Email";
        $message = "Hello $firstname,\n\nPlease click the link below to verify your email:\n$verification_link";

        if (sendVerificationEmail($email, $subject, $message)) {
            header("Location: login.html?registered=1");
            exit();
        } else {
            echo "Registration successful, but failed to send verification email.";
        }
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

