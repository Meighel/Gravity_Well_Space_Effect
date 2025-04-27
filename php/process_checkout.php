<?php
session_start();
require 'db.php'; // adjust if needed

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $amount = $_POST['amount'];
    $status = 'Paid';

    // Insert into transactions table
    $stmt = $conn->prepare("INSERT INTO transactions (user_id, amount, status) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $user_id, $amount, $status);
    $stmt->execute();
    $transaction_id = $stmt->insert_id; // Get the auto-generated ID of the transaction
    $stmt->close();

    // Log this purchase in the audit_log table
    $action = "User made a purchase (Transaction ID: $transaction_id)";
    $stmt_log = $conn->prepare("INSERT INTO audit_log (user_id, action, timestamp) VALUES (?, ?, NOW())");
    $stmt_log->bind_param("is", $user_id, $action);
    $stmt_log->execute();

    // Close the log statement and connection
    $stmt_log->close();
    $conn->close();

    // Redirect to the download page after successful transaction
    header("Location: download.php?transaction_id=$transaction_id");
    exit();
} else {
    header("Location: checkout.php");
    exit();
}

