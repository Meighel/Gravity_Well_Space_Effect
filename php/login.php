<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Look up the user by email
    $stmt = $conn->prepare("SELECT id, firstname, middlename, lastname, email, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user["password"])) {
            // Login successful
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["firstname"] . ' ' . $user["lastname"];  // combine first and last name
            $_SESSION["user_role"] = $user["role"];

            // Redirect based on role
            if ($user["role"] == "Admin") {
                header("Location: admin_dashboard.php");
                exit();
            } elseif ($user["role"] == "Staff") {
                header("Location: staff_dashboard.php");
                exit();
            } elseif ($user["role"] == "Client") {
                header("Location: client_dashboard.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "Incorrect password.";
            header("Location: login.html");
            exit();
        }
    } else {
        $_SESSION['error'] = "Email not Found";
        header("Location: login.html");
        exit();
    }

    $stmt->close();
}
?>
