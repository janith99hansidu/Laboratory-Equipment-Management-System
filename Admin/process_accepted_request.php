<?php
// Include the connection.php file to establish a database connection
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_no = $_POST['request_no'];
    $action = $_POST['action'];
    $empId = $_POST['empId'];

    if ($action == 'Borrow') {
        // Update the status to borrowed
        $new_status = 'borrowed';
        $sql = "UPDATE request SET status = '$new_status' WHERE request_no = '$request_no'";
    } elseif ($action == 'Delete') {
        // Delete the request from the database
        $sql = "DELETE FROM request WHERE request_no = '$request_no'";
    }

    if ($conn->query($sql) === TRUE) {
        if ($action == 'Borrow') {
            echo "Request has been borrowed successfully.";
        } elseif ($action == 'Delete') {
            echo "Request has been deleted successfully.";
        }
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Redirect back to accepted_reservations.php with the empId
    header("Location: accepted_reservations.php?empId=$empId");
    exit;
}

$conn->close();
?>
