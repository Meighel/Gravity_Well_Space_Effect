<?php
session_start();
require 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Download Gravity Well Space Plugin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
    <div class="container">
        <a class="navbar-brand fw-bold" href="client_dashboard.php">Gravity Well Space FX</a>
    </div>
</nav>

<div class="container mt-5 text-center">
    <h1 class="mb-4">Thank you for your purchase!</h1>
    <p class="lead mb-4">You can now download your Gravity Well Space Plugin below:</p>
    <a href="downloads/GravityWellSpacePlugin.zip" class="btn btn-primary btn-lg">Download Plugin</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
