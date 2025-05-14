<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Staff') {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT id, firstname, lastname, email, created_at FROM users WHERE role = 'Client'");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Clients - Staff Dashboard</title>
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
  <h1 class="mb-4">Registered Clients</h1>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Registered On</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody id="clienttable">
      <?php if ($result->num_rows > 0): ?>
        <?php while ($client = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo $client['id']; ?></td>
            <td><?php echo $client['firstname'] . ' ' . $client['lastname']; ?></td>
            <td><?php echo $client['email']; ?></td>
            <td><?php echo date('Y-m-d', strtotime($client['created_at'])); ?></td>
            <td>
              <a href="view_client.php?id=<?php echo $client['id']; ?>" class="btn btn-info btn-sm">View</a>
              <a href="edit_client.php?id=<?php echo $client['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="delete_client.php?id=<?php echo $client['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this client?');">Delete</a>
            </td>
          </tr>
        <?php endwhile; ?>
      <?php else: ?>
        <tr>
          <td colspan="5" class="text-center">No clients found.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>

<script src="../js/pagination.js"></script>
<script>
  paginate('#clienttable', 10);
</script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
