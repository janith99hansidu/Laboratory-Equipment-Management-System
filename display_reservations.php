<?php
include 'connection.php';

// Retrieve registration number from query parameter
$regId = $_GET['regId'] ?? null;

if (!$regId) {
    echo "<div class='alert alert-danger'>Registration number not found.</div>";
    exit();
}

// SQL query to fetch reservation data
$sql = "SELECT request_no, date_time, request_date, start_time, end_time, status FROM request WHERE student_reg = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $regId);
$stmt->execute();
$result = $stmt->get_result();

// Display user's registration number
echo "<table class='table table-hover'>
        <thead class='thead-dark'>
            <tr>
                <th>Date Time</th>
                <th>Equipment</th>
                <th>Request Date</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>";

// Check if there are any records
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr data-request-no='" . htmlspecialchars($row['request_no']) . "'>
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
                <td>
                    <button class='btn btn-primary btn-sm'>Edit</button>
                    <button class='btn btn-danger btn-sm deleteBtn' data-request-no='" . $row['request_no'] . "'>Delete</button>
                </td>
            </tr>";

        // Close the inner statement
        $stmt2->close();
    }
} else {
    echo "<tr><td colspan='7' class='text-center'>No reservations found</td></tr>";
}

echo "</tbody></table>";

// Close the statement and connection
$stmt->close();
$conn->close();
?>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('.deleteBtn').on('click', function() {
        var requestNo = $(this).data('request-no');
        if (confirm('Are you sure you want to delete this request?')) {
            $.ajax({
                url: 'delete_request.php',
                type: 'POST',
                data: { request_no: requestNo },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status === 'success') {
                        $('tr[data-request-no="' + requestNo + '"]').remove();
                    } else {
                        alert('Error deleting record: ' + result.message);
                    }
                }
            });
        }
    });
});
</script>
