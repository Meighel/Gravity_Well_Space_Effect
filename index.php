<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['user_role'])) {
    switch ($_SESSION['user_role']) {
        case 'Admin':
            header("Location: php/admin_dashboard.php");
            exit();
        case 'Staff':
            header("Location: php/staff_dashboard.php");
            exit();
        case 'Client':
        default:
            header("Location: php/client_dashboard.php");
            exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Gravity Well Space Plugin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-dark text-light">

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container">
      <a class="navbar-brand fw-bold" href="index.php">Gravity Well Space FX</a>
      <div class="d-flex">
        <a href="product.html" class="btn text-white me-2">About the Product</a>
        <a href="php/login.php" class="btn btn-outline-light me-2">Login</a>
        <a href="register.html" class="btn btn-primary">Register</a>
      </div>
    </div>
  </nav>
  
  <div class="container py-5">
    <div class="text-center">
      <h1 class="display-4 fw-bold text-info">Gravity Well Space Plugin</h1>
      <p class="lead">Imitate the far and vacuum sound of deep space — in your own audio projects.</p>
      <img src="img/example-plugin.jpeg" class="img-fluid my-4 rounded shadow" alt="Gravity Well Plugin Banner">
      <br>
      <a href="php/login.php" class="btn btn-primary btn-lg">Get the Plugin</a>
    </div>

    <hr class="my-5 border-light">

    <section class="text-center">
      <h2 class="text-warning">Features</h2>
      <ul class="list-unstyled mt-3">
        <li>🎧 Realistic space vacuum reverb</li>
        <li>🎛️ Easy-to-use interface</li>
        <li>🎚️ Compatible with most DAWs</li>
        <li>🛠️ Custom presets for sci-fi and ambient genres</li>
      </ul>
    </section>

    <section id="buy" class="mt-5 text-center">
      <h2 class="text-success">Buy Now</h2>
      <p class="mb-4">Download the VST/AU version for your platform.</p>
      <a href="php/login.php" class="btn btn-outline-light btn-lg me-2">Buy for Windows</a>
      <a href="php/login.php" class="btn btn-outline-light btn-lg">Buy for Mac</a>
    </section>
  </div>

</body>
</html>
