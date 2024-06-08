<?php
// Include the connection.php file to establish a database connection
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_no = $_POST['request_no'];
    $action = $_POST['action'];
    $empId = $_POST['empId'];

    if ($action == 'Return') {
        // Update the status to returned
        $new_status = 'returned';
        $sql = "UPDATE request SET status = '$new_status' WHERE request_no = '$request_no'";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Request has been returned successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Redirect back to borrowed_reservations.php with the empId
    header("Location: borrowed_reservations.php?empId=$empId");
    exit;
}

$conn->close();
?>
