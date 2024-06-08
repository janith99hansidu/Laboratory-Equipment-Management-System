<?php
// Include the connection.php file to establish a database connection
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_no = $_POST['request_no'];
    $action = $_POST['action'];
    $empId = $_POST['empId'];

    // Determine the new status based on the action
    $new_status = ($action == 'Accept') ? 'approved' : 'rejected';

    // Update the status and to_id in the database
    $sql = "UPDATE request SET status = '$new_status', to_id = '$empId' WHERE request_no = '$request_no'";

    if ($conn->query($sql) === TRUE) {
        echo "Request has been " . $new_status . " successfully.";
    } else {
        echo "Error updating record: " . $conn->error;
    }

    // Redirect back to admin.php with the empId
    header("Location: admin.php?empId=$empId");
    exit;
}

$conn->close();
?>
