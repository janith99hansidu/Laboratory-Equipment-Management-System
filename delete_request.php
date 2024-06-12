<?php
include 'connection.php';

if (isset($_POST['request_no'])) {
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

        echo json_encode(["status" => "success"]);
    } catch (Exception $e) {
        // An error occurred, rollback transaction
        $conn->rollback();
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>
