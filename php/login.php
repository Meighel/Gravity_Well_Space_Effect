<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to verify user credentials (simplified for example)
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Password verification (assuming password is hashed)
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];

            // Log the login action
            $action = "User logged in";
            $user_id = $user['id'];
            // Attempt to get the real client IP address
            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                // Check if the IP is from a shared internet connection (proxy)
                $ip_address = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                // Check if the IP is from a proxy server
                $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                // Default to REMOTE_ADDR (this would capture the direct IP address if no proxy is involved)
                $ip_address = $_SERVER['REMOTE_ADDR'];
            }

            // Clean up the IP address to avoid issues with multiple forwarded IPs (common with proxies)
            $ip_address = strtok($ip_address, ','); // In case of multiple IPs in the forwarded header

            // Insert login action along with IP address
            $sql_log = "INSERT INTO audit_log (user_id, action, ip_address, timestamp) VALUES (?, ?, ?, NOW())";
            $stmt_log = $conn->prepare($sql_log);
            $stmt_log->bind_param("iss", $user_id, $action, $ip_address);  // Added the IP address parameter
            $stmt_log->execute();

            // Redirect based on user role
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
