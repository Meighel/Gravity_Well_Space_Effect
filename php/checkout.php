<?php
session_start();
require 'db.php'; // adjust if needed

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit;
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$amount = 99.99; // product price
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Gravity Well Space Plugin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="php/client_dashboard.php">Gravity Well Space FX</a>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="text-center mb-4">Confirm Your Purchase</h1>
    
    <div class="card mx-auto" style="max-width: 500px;">
        <div class="card-body">
            <h5 class="card-title">Order Summary</h5>
            <p class="card-text"><strong>Product:</strong> Gravity Well Space Plugin</p>
            <p class="card-text"><strong>Price:</strong> $<?php echo number_format($amount, 2); ?></p>
            <p class="card-text"><strong>Buyer:</strong> <?php echo htmlspecialchars($user_name); ?></p>

            <form action="process_checkout.php" method="POST">
                <input type="hidden" name="amount" value="<?php echo $amount; ?>">
                <button type="submit" class="btn btn-success w-100">Confirm and Pay</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
