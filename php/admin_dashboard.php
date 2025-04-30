<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Admin') {
    header("Location: ../login.html");
    exit;
}

$transactions = [];
$trans_stmt = $conn->prepare("
    SELECT t.id, u.firstname, u.lastname, t.amount, t.status, t.created_at 
    FROM transactions t
    JOIN users u ON t.user_id = u.id
    ORDER BY t.created_at DESC
");
$trans_stmt->execute();
$trans_result = $trans_stmt->get_result();
while ($row = $trans_result->fetch_assoc()) {
    $transactions[] = $row;
}

$audit_logs = [];
$audit_stmt = $conn->prepare("
    SELECT a.id, u.firstname, u.lastname, a.action, a.timestamp 
    FROM audit_log a
    JOIN users u ON a.user_id = u.id
    ORDER BY a.timestamp DESC
");
$audit_stmt->execute();
$audit_result = $audit_stmt->get_result();
while ($row = $audit_result->fetch_assoc()) {
    $audit_logs[] = $row;
}

$report = [
    'total_transactions' => 0,
    'total_amount' => 0.00,
    'pending' => 0,
    'completed' => 0,
    'failed' => 0
];

$summary_stmt = $conn->prepare("
    SELECT status, COUNT(*) as count, SUM(amount) as total
    FROM transactions
    GROUP BY status
");
$summary_stmt->execute();
$summary_result = $summary_stmt->get_result();

while ($row = $summary_result->fetch_assoc()) {
    $report['total_transactions'] += $row['count'];
    $report['total_amount'] += $row['total'];

    if (strtolower($row['status']) === 'pending') {
        $report['pending'] = $row['count'];
    } elseif (strtolower($row['status']) === 'completed') {
        $report['completed'] = $row['count'];
    } elseif (strtolower($row['status']) === 'failed') {
        $report['failed'] = $row['count'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard - Gravity Well FX</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="admin_dashboard.php">Admin Dashboard</a>
    <div class="ms-auto">
      <span class="text-light me-3">Welcome, <?php echo $_SESSION['user_name']; ?></span>
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h1 class="mb-4">Admin Panel</h1>

  <!-- Reports Section -->
  <div class="card mb-5 shadow">
    <div class="card-header bg-info text-white">
      Reports Summary
    </div>
    <div class="card-body">
      <table class="table table-bordered">
        <tr>
          <th>Total Transactions</th>
          <td><?php echo htmlspecialchars($report['total_transactions']); ?></td>
        </tr>
        <tr>
          <th>Total Amount</th>
          <td>$<?php echo number_format($report['total_amount'], 2); ?></td>
        </tr>
        <tr>
          <th>Pending Transactions</th>
          <td><?php echo htmlspecialchars($report['pending']); ?></td>
        </tr>
        <tr>
          <th>Completed Transactions</th>
          <td><?php echo htmlspecialchars($report['completed']); ?></td>
        </tr>
        <tr>
          <th>Failed Transactions</th>
          <td><?php echo htmlspecialchars($report['failed']); ?></td>
        </tr>
      </table>
    </div>
  </div>

  <!-- Transactions Section -->
  <div class="card mb-5 shadow">
    <div class="card-header bg-primary text-white">
      Transactions
    </div>
    <div class="card-body">
      <?php if (count($transactions) > 0): ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>ID</th>
            <th>Client Name</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Created At</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($transactions as $txn): ?>
          <tr>
            <td><?php echo htmlspecialchars($txn['id']); ?></td>
            <td><?php echo htmlspecialchars($txn['firstname'] . ' ' . $txn['lastname']); ?></td>
            <td>$<?php echo htmlspecialchars($txn['amount']); ?></td>
            <td><?php echo htmlspecialchars($txn['status']); ?></td>
            <td><?php echo htmlspecialchars($txn['created_at']); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p>No transactions found.</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Audit Logs Section -->
  <div class="card shadow">
    <div class="card-header bg-success text-white">
      Audit Logs
    </div>
    <div class="card-body">
      <?php if (count($audit_logs) > 0): ?>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Log ID</th>
            <th>User</th>
            <th>Action</th>
            <th>Timestamp</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($audit_logs as $log): ?>
          <tr>
            <td><?php echo htmlspecialchars($log['id']); ?></td>
            <td><?php echo htmlspecialchars($log['firstname'] . ' ' . $log['lastname']); ?></td>
            <td><?php echo htmlspecialchars($log['action']); ?></td>
            <td><?php echo htmlspecialchars($log['timestamp']); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p>No audit logs available.</p>
      <?php endif; ?>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
