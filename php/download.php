<?php
session_start();
require 'db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}

if (isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $transaction_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $transaction = $result->fetch_assoc();
        $status = $transaction['status'];
        
        if ($status == 'Paid') {
            $download_message = "
                <div class='container mt-5 text-center'>
                    <h1 class='mb-4'>Thank you for your purchase!</h1>
                    <p class='lead mb-4'>You can now download your Gravity Well Space Plugin below:</p>
                    <a href='downloads/GravityWellSpacePlugin.zip' class='btn btn-primary btn-lg'>Download Plugin</a>
                </div>
            ";
        } else {
            $download_message = "
                <div class='container mt-5'>
                    <h1 class='text-center text-danger'>Error</h1>
                    <p class='text-center'>Your transaction is not completed yet. Please contact support.</p>
                </div>
            ";
        }
    } else {
        $download_message = "
            <div class='container mt-5'>
                <h1 class='text-center text-danger'>Error</h1>
                <p class='text-center'>Transaction not found or invalid. Please try again.</p>
            </div>
        ";
    }
} else {
    $download_message = "
        <div class='container mt-5'>
            <h1 class='text-center text-danger'>Error</h1>
            <p class='text-center'>No valid transaction found. Please go back and try purchasing again.</p>
        </div>
    ";
}

$conn->close();
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

<!-- Display the message dynamically -->
<?php echo $download_message; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
