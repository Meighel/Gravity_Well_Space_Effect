<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Client') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM transactions WHERE user_id = ? AND status = 'Paid' LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$has_paid = $result->num_rows > 0;

$license_key = "GWSP-" . strtoupper(substr(md5($user_id), 0, 10));

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Client Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="client_dashboard.php">Client Dashboard</a>
    <a href="../product_dash.html" class="btn text-white me-2">Product</a>
    <div class="ms-auto">
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h1>Welcome back, <?php echo $_SESSION['user_name']; ?>!</h1>
  <p class="lead">Your gateway to the cosmos of sound.</p>

  <div class="card mt-4 shadow-sm">
    <div class="card-body">
      <?php if ($has_paid): ?>
        <h5 class="card-title">Your Plugin is Ready!</h5>
        <p class="card-text">Thank you for your purchase. Download your plugin below:</p>
        <a href="download.php" class="btn btn-success">Download Gravity Well Space Plugin</a>

        <div class="mt-3">
          <h6>Your License Key:</h6>
          <div class="alert alert-info"><?php echo $license_key; ?></div>
        </div>
      <?php else: ?>
        <h5 class="card-title">You haven't purchased yet!</h5>
        <p class="card-text">Get the Gravity Well Space Plugin now and explore new sound dimensions.</p>
        <a href="../product_dash.html" class="btn btn-primary">Buy Now</a>
      <?php endif; ?>
    </div>
  </div>

  <div class="mt-5">
    <h4>Need Help?</h4>
    <p>Contact our support team at <strong>support@gravitywellfx.com</strong> and we'll assist you!</p>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
