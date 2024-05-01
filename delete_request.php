<?php
include 'connection.php';

if (isset($_POST['deleteBtn']) && isset($_POST['request_no'])) {
    $requestNo = $_POST['request_no'];

    // Start transaction
    $conn->begin_transaction();

    try {
        // Delete from requestequipment table
        $stmt1 = $conn->prepare("DELETE FROM requestequipment WHERE request_no = ?");
        $stmt1->bind_param("s", $requestNo);
        $stmt1->execute();
        $stmt1->close();

        // Delete from request table
        $stmt2 = $conn->prepare("DELETE FROM request WHERE request_no = ?");
        $stmt2->bind_param("s", $requestNo);
        $stmt2->execute();
        $stmt2->close();

        // Commit transaction
        $conn->commit();

        // Redirect or send success message
        header("Location: user.php"); // Adjust the redirection URL as necessary
        exit();
    } catch (Exception $e) {
        // An error occurred, rollback transaction
        $conn->rollback();
        echo "Error deleting record: " . $e->getMessage();
    }
}
