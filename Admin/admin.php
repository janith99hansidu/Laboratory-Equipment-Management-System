<?php
// Check if regNo is set in the URL query string
if (isset($_GET['empId'])) {
    $empId = $_GET['empId'];

    // Use the $regNo here for your functionalities in user.php
    // Example: Display the retrieved regNo
    echo "Welcome Admin with Employee number: " . $empId;

    // You can perform other operations using the retrieved regNo
    // like fetching student data from the database based on regNo
} else {
    // Handle the case where regNo is not present in the URL
    echo "No registration number found.";
}
