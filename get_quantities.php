<?php
require_once 'connection.php';  // Assumes connection.php includes your MySQLi connection setup

if (isset($_POST['equip_id'])) {
    $equipId = $_POST['equip_id'];

    // Query to get the available quantity of the selected equipment
    $query = $conn->prepare("SELECT available_qty FROM equipment WHERE equip_id = ?");
    $query->bind_param("i", $equipId);
    $query->execute();
    $result = $query->get_result();
    if ($row = $result->fetch_assoc()) {
        $maxQty = $row['available_qty'];
        for ($i = 1; $i <= $maxQty; $i++) {
            echo "<option value='$i'>$i</option>";  // Return each quantity as an option
        }
    } else {
        echo "<option>No quantity available</option>";
    }
    $query->close();
} else {
    echo "<option>Error: Equipment not selected</option>";
}
?>
