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
    <title>Equipment List</title>
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
                <a class="nav-item nav-link active" href="#">Equipments <span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="about.php?regId=<?= htmlspecialchars($regId) ?>">About</a>
                <a class="nav-item nav-link" href="contact.php?regId=<?= htmlspecialchars($regId) ?>">Contact</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Equipment Table</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Available Quantity</th>
                    <th>Left Quantity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // SQL query
                $sql = "SELECT name, brand, available_qty, left_qty FROM equipment";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['brand']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['available_qty']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['left_qty']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No equipment found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>

</html>