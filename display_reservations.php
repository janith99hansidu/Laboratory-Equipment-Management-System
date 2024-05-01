<?php
include 'connection.php';

// SQL query to fetch reservation data
$sql = "SELECT request_no, date_time, request_date, start_time, end_time, status FROM request WHERE student_reg = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $regId);
$stmt->execute();
$result = $stmt->get_result();

// Check if there are any records
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['date_time']) . "</td>
                <td>";

        // Fetch equipment details for the current request
        $sql2 = "SELECT e.name, re.count FROM requestequipment re JOIN equipment e ON re.euip_id = e.equip_id WHERE re.request_no = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("s", $row['request_no']);
        $stmt2->execute();
        $result2 = $stmt2->get_result();

        if ($result2->num_rows > 0) {
            while ($row2 = $result2->fetch_assoc()) {
                echo htmlspecialchars($row2['name']) . " (Qty: " . htmlspecialchars($row2['count']) . ")<br>";
            }
        } else {
            echo "No equipment details";
        }

        echo "</td>
                <td>" . htmlspecialchars($row['request_date']) . "</td>
                <td>" . htmlspecialchars($row['start_time']) . "</td>
                <td>" . htmlspecialchars($row['end_time']) . "</td>
                <td>" . htmlspecialchars($row['status']) . "</td>
                <td><button>Edit</button>
                <form action='delete_request.php' method='post' onsubmit='return confirm(\"Are you sure you want to delete this request?\");'>
                <input type='hidden' name='request_no' value='" . $row['request_no'] . "'>
                <button type='submit' name='deleteBtn'>Delete</button>
                </form></td>
                </tr>";

        // Close the inner statement
        $stmt2->close();
    }
} else {
    echo "<tr><td colspan='7'>No reservations found</td></tr>";
}

// Close the statement and connection
$stmt->close();
