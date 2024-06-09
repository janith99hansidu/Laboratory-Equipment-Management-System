<?php
session_start();
require_once 'connection.php';

$regId = $_GET['regId'] ?? null;

// Display user's registration number
if ($regId) {
    echo "Your registration number is: " . htmlspecialchars($regId);
} else {
    echo "Registration number not found.";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>About Us</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            <img src="Assets/UoJ_logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
            Laboratory Management
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ml-auto">
                <a class="nav-item nav-link" href="user.php?regId=<?= htmlspecialchars($regId) ?>">Home</a>
                <a class="nav-item nav-link" href="equipment.php?regId=<?= htmlspecialchars($regId) ?>">Equipments</a>
                <a class="nav-item nav-link active" href="#">About <span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="contact.php?regId=<?= htmlspecialchars($regId) ?>">Contact</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>About Us</h2>
        <p>Welcome to the Laboratory Management System. Our mission is to provide efficient and reliable laboratory equipment management for students and staff. We are committed to ensuring that all equipment is available and maintained properly.</p>
    </div>
</body>

</html>
