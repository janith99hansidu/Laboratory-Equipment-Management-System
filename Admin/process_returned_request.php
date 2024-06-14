<?php
// Include the connection.php file to establish a database connection
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_no = $_POST['request_no'];
    $action = $_POST['action'];
    $empId = $_POST['empId'];

    if ($action == 'Delete') {
        // Delete the request from the request table
        $sql1 = "DELETE FROM request WHERE request_no = '$request_no'";
        // Delete the request from the requestequipment table
        $sql2 = "DELETE FROM requestequipment WHERE request_no = '$request_no'";

        if ($conn->query($sql1) === TRUE && $conn->query($sql2) === TRUE) {
            echo "Request and associated equipment have been deleted successfully.";
        } else {
            echo "Error deleting record: " . $conn->error;
        }
    }

    // Redirect back to returned_reservations.php with the empId
    header("Location: returned_reservations.php?empId=$empId");
    exit;
}

$conn->close();
?>
