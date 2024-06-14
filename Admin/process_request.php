<?php
// Include the connection.php file to establish a database connection
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $request_no = $_POST['request_no'];
    $action = $_POST['action'];
    $empId = $_POST['empId'];

    // Determine the new status based on the action
    $new_status = ($action == 'Accept') ? 'approved' : 'rejected';

    // Begin transaction
    $conn->begin_transaction();

    try {
        // Update the status and to_id in the database
        $sql = "UPDATE request SET status = ?, to_id = ? WHERE request_no = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ssi', $new_status, $empId, $request_no);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            if ($action == 'Accept') {
                // Fetch equipment details for the accepted request
                $fetchEquipmentSql = "SELECT euip_id, count FROM requestequipment WHERE request_no = ?";
                $stmtFetch = $conn->prepare($fetchEquipmentSql);
                $stmtFetch->bind_param('i', $request_no);
                $stmtFetch->execute();
                $result = $stmtFetch->get_result();

                // Loop through each equipment item and update the quantities
                while ($row = $result->fetch_assoc()) {
                    $equip_id = $row['euip_id'];
                    $count = $row['count'];

                    // Update the equipment table
                    $updateEquipmentSql = "
                        UPDATE equipment 
                        SET available_qty = available_qty - ?, 
                            left_qty = left_qty + ?
                        WHERE equip_id = ?";
                    $stmtUpdate = $conn->prepare($updateEquipmentSql);
                    $stmtUpdate->bind_param('iii', $count, $count, $equip_id);
                    $stmtUpdate->execute();
                    
                    // Check if update was successful
                    if ($stmtUpdate->affected_rows == 0) {
                        throw new Exception("Failed to update equipment with ID: $equip_id");
                    }
                }
            }

            // Commit transaction
            $conn->commit();
            echo "Request has been " . $new_status . " successfully.";
        } else {
            throw new Exception("Error updating request status.");
        }
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "Error: " . $e->getMessage();
    }

    // Redirect back to admin.php with the empId
    header("Location: admin.php?empId=$empId");
    exit;
} else {
    echo "Invalid request.";
}
?>
