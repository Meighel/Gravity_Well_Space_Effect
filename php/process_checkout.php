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

    // Insert into transactions
    $stmt = $conn->prepare("INSERT INTO transactions (user_id, amount, status) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $user_id, $amount, $status);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    // After inserting, go to download page
    header("Location: download.php");
    exit();
} else {
    header("Location: checkout.php");
    exit();
}
?>
