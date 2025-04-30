<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];

            $action = "User logged in";
            $user_id = $user['id'];
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $ip_address = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $ip_address = $_SERVER['REMOTE_ADDR'];
            }

            $ip_address = strtok($ip_address, ','); 

            $sql_log = "INSERT INTO audit_log (user_id, action, ip_address, timestamp) VALUES (?, ?, ?, NOW())";
            $stmt_log = $conn->prepare($sql_log);
            $stmt_log->bind_param("iss", $user_id, $action, $ip_address);  
            $stmt_log->execute();

            if ($user['role'] == 'Admin') {
                header("Location: admin_dashboard.php");
            } elseif ($user['role'] == 'Staff') {
                header("Location: staff_dashboard.php");
            } else {
                header("Location: client_dashboard.php");
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email.";
    }
}
?>
