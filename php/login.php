<?php
session_start();
if (isset($_SESSION['user_id'])) {
    // Redirect based on role if already logged in
    if ($_SESSION['user_role'] === 'Admin') {
        header("Location: admin_dashboard.php");
        exit();
    } elseif ($_SESSION['user_role'] === 'Staff') {
        header("Location: staff_dashboard.php");
        exit();
    } else {
        header("Location: client_dashboard.php");
        exit();
    }
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_role'] = $user['role'];
            $_SESSION['user_name'] = $user['firstname'] . ' ' . $user['lastname'];

            $action = "User logged in";
            $user_id = $user['id'];

            $ip_address = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
            $ip_address = strtok($ip_address, ',');

            $sql_log = "INSERT INTO audit_log (user_id, action, ip_address, timestamp) VALUES (?, ?, ?, NOW())";
            $stmt_log = $conn->prepare($sql_log);
            $stmt_log->bind_param("iss", $user_id, $action, $ip_address);  
            $stmt_log->execute();

            if ($user['role'] == 'Admin') {
                header("Location: admin_dashboard.php");
            } elseif ($user['role'] == 'Staff') {
                header("Location: staff_dashboard.php");
            } else {
                header("Location: client_dashboard.php");
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No user found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - Gravity Well Plugin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="../css/login.css" />
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
  <div class="container">
    <a class="navbar-brand fw-bold" href="../index.php">Gravity Well Space FX</a>
  </div>
</nav>

<div class="login-container">
  <h2>Login</h2>

  <?php if (isset($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <form action="login.php" method="POST">
    <div class="form-group">
      <label for="email">Email address</label>
      <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
    </div>
    <div class="form-group mt-3">
      <button type="submit" class="btn btn-primary">Login</button>
    </div>
    <div class="form-group mt-2">
      <p>Don't have an account? <a href="../register.html">Register here</a></p>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
