<?php
session_start();
require 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $amount = $_POST['amount'];
    $status = 'Paid';

    $stmt = $conn->prepare("INSERT INTO transactions (user_id, amount, status) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $user_id, $amount, $status);
    $stmt->execute();
    $transaction_id = $stmt->insert_id; 
    $stmt->close();

    $action = "User made a purchase (Transaction ID: $transaction_id)";
    $stmt_log = $conn->prepare("INSERT INTO audit_log (user_id, action, timestamp) VALUES (?, ?, NOW())");
    $stmt_log->bind_param("is", $user_id, $action);
    $stmt_log->execute();

    $stmt_log->close();
    $conn->close();

    header("Location: download.php?transaction_id=$transaction_id");
    exit();
} else {
    header("Location: checkout.php");
    exit();
}

