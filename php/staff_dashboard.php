<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Staff') {
    header("Location: ../login.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Staff Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="staff_dashboard.php">Staff Dashboard</a>
    <a href="clients.php" class="btn text-white me-2">Client</a>
    <a href="transactions.php" class="btn text-white me-2">Transactions</a>
    <a href="audit_logs.php" class="btn text-white me-2">Log</a>
    <div class="ms-auto">
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h1 class="mb-4">Welcome, <?php echo $_SESSION['user_name']; ?>!</h1>

  <div class="row g-4">
    <!-- View Transactions -->
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Transactions</h5>
          <p class="card-text">View all customer transactions and approve pending payments.</p>
          <a href="transactions.php" class="btn btn-primary">View Transactions</a>
        </div>
      </div>
    </div>

    <!-- View Audit Logs -->
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Audit Logs</h5>
          <p class="card-text">Monitor activities like purchases, logins, and updates.</p>
          <a href="audit_logs.php" class="btn btn-primary">View Audit Logs</a>
        </div>
      </div>
    </div>

    <!-- View Registered Clients -->
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Registered Clients</h5>
          <p class="card-text">View all customers who registered on the platform.</p>
          <a href="clients.php" class="btn btn-primary">View Clients</a>
        </div>
      </div>
    </div>

    <!-- Support Requests Placeholder -->
    <div class="col-md-6">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h5 class="card-title">Support Requests</h5>
          <p class="card-text">Manage customer support (Coming Soon).</p>
          <a href="#" class="btn btn-secondary disabled">Coming Soon</a>
        </div>
      </div>
    </div>
  </div>

</div>

</body>
</html>
