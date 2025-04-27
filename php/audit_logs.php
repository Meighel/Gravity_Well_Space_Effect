<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_role']) || ($_SESSION['user_role'] !== 'Admin' && $_SESSION['user_role'] !== 'Staff')) {
    header("Location: login.html");
    exit;
}

// Fetch the audit logs (login and purchase events)
$sql = "SELECT a.id, u.firstname, u.lastname, a.action, a.timestamp, a.ip_address
        FROM audit_log a
        JOIN users u ON a.user_id = u.id
        ORDER BY a.timestamp DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Audit Logs - Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand fw-bold" href="admin_dashboard.php">Dashboard</a>
    <div class="ms-auto">
      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
  <h1 class="mb-4">Audit Logs</h1>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th>User</th>
        <th>Action</th>
        <th>IP Address</th>
        <th>Timestamp</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
          echo "<tr>
                  <td>" . $row['id'] . "</td>
                  <td>" . $row['firstname'] . " " . $row['lastname'] . "</td>
                  <td>" . $row['action'] . "</td>
                  <td>" . $row['ip_address'] . "</td>
                  <td>" . $row['timestamp'] . "</td>
                </tr>";
        }
      } else {
        echo "<tr><td colspan='5' class='text-center'>No audit logs found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
