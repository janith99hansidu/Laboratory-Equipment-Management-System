<?php
// Include the connection.php file to establish a database connection
include '../connection.php';

// Check if empId is set in the URL query string
if (isset($_GET['empId'])) {
    $empId = $_GET['empId'];
    echo "Welcome Admin with Employee number: " . $empId;
} else {
    echo "No employee ID found.";
    exit;
}

// Query to fetch accepted reservations along with aggregated equipment details
$sql = "
    SELECT r.request_no, r.student_reg, r.date_time, r.status, r.start_time, r.end_time, r.request_date, r.to_id,
    GROUP_CONCAT(CONCAT(re.euip_id, ': ', re.count) SEPARATOR ', ') AS equipment
    FROM request r
    LEFT JOIN requestequipment re 
    ON r.request_no = re.request_no
    WHERE r.status = 'approved'
    GROUP BY r.request_no, r.student_reg, r.date_time, r.status, r.start_time, r.end_time, r.request_date, r.to_id
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin - Accepted Reservations</title>
</head>

<body>
    <h1>Accepted Reservations</h1>
    <a href="admin.php?empId=<?php echo $empId; ?>">View Pending Requests</a>
    <a href="accepted_reservations.php?empId=<?php echo $empId; ?>">View Accepted Reservations</a>
    <a href="borrowed_reservations.php?empId=<?php echo $empId; ?>">View Borrowed Reservations</a>
    <a href="returned_reservations.php?empId=<?php echo $empId; ?>">View Returned Reservations</a>
    <table border="1">
        <tr>
            <th>Request No</th>
            <th>Student Registration</th>
            <th>Date Time</th>
            <th>Status</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Request Date</th>
            <th>To ID</th>
            <th>Equipment (ID: Count)</th>
            <th>Actions</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['request_no'] . "</td>";
                echo "<td>" . $row['student_reg'] . "</td>";
                echo "<td>" . $row['date_time'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>" . $row['start_time'] . "</td>";
                echo "<td>" . $row['end_time'] . "</td>";
                echo "<td>" . $row['request_date'] . "</td>";
                echo "<td>" . $row['to_id'] . "</td>";
                echo "<td>" . $row['equipment'] . "</td>";
                echo "<td>
                        <form method='POST' action='process_accepted_request.php'>
                            <input type='hidden' name='request_no' value='" . $row['request_no'] . "'>
                            <input type='hidden' name='empId' value='" . $empId . "'>
                            <input type='submit' name='action' value='Borrow'>
                            <input type='submit' name='action' value='Delete'>
                        </form>
                    </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='10'>No accepted reservations found.</td></tr>";
        }
        ?>
    </table>
</body>

</html>

<?php
$conn->close();
?>
