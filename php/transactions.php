<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_role']) || ($_SESSION['user_role'] !== 'Staff' && $_SESSION['user_role'] !== 'Admin')) {
    header("Location: login.html");
    exit;
}

$sql = "SELECT t.id, u.firstname, u.lastname, t.amount, t.status, t.created_at 
        FROM transactions t
        JOIN users u ON t.user_id = u.id
        ORDER BY t.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Transactions - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="staff_dashboard.php">Dashboard</a>
    <a href="clients.php" class="btn text-white me-2">Client</a>
    <a href="transactions.php" class="btn text-white me-2">Transactions</a>
    <a href="audit_logs.php" class="btn text-white me-2">Log</a>
    <div class="ms-auto">
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h1 class="mb-4">Transactions</h1>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th>Client</th>
        <th>Amount</th>
        <th>Status</th>
        <th>Date</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>" . $row['id'] . "</td>
                  <td>" . $row['firstname'] . " " . $row['lastname'] . "</td>
                  <td>$" . number_format($row['amount'], 2) . "</td>
                  <td>" . $row['status'] . "</td>
                  <td>" . $row['created_at'] . "</td>
                </tr>";
        }
      } else {
        echo "<tr><td colspan='5' class='text-center'>No transactions found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
