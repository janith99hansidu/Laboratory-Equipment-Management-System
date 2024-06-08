<?php
include 'connection.php';

// Retrieve registration number from query parameter
$regId = $_GET['regId'] ?? null;

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

        // Redirect to user.php with regId
        if ($regId) {
            header("Location: user.php?regId=$regId");
        } else {
            header("Location: user.php"); // Adjust the redirection URL as necessary
        }
        exit();
    } catch (Exception $e) {
        // An error occurred, rollback transaction
        $conn->rollback();
        echo "Error deleting record: " . $e->getMessage();
    }
}

// Display user's registration number
if ($regId) {
    echo "Your registration number is: $regId";
} else {
    echo "Registration number not found.";
}
?>
