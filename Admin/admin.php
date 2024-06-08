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

// Query to fetch pending requests along with aggregated equipment details
$sql = "
    SELECT r.request_no, r.student_reg, r.date_time, r.status, r.start_time, r.end_time, r.request_date,
    GROUP_CONCAT(CONCAT(re.euip_id, ': ', re.count) SEPARATOR ', ') AS equipment
    FROM request r
    LEFT JOIN requestequipment re 
    ON r.request_no = re.request_no
    WHERE r.status = 'pending'
    GROUP BY r.request_no, r.student_reg, r.date_time, r.status, r.start_time, r.end_time, r.request_date
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - Pending Requests</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            <img src="http://localhost/02Test/Assets/UoJ_logo.png" width="30" height="30" class="d-inline-block align-top" alt="">
            Laboratory Management
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav ml-auto">
                <a class="nav-item nav-link active" href="admin.php?empId=<?php echo $empId; ?>">Admin - Pending Requests</a>
                <a class="nav-item nav-link" href="accepted_reservations.php?empId=<?php echo $empId; ?>">View Accepted Reservations</a>
                <a class="nav-item nav-link" href="borrowed_reservations.php?empId=<?php echo $empId; ?>">View Borrowed Reservations</a>
                <a class="nav-item nav-link" href="returned_reservations.php?empId=<?php echo $empId; ?>">View Returned Reservations</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Pending Requests</h2>
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>Request No</th>
                    <th>Student Registration</th>
                    <th>Date Time</th>
                    <th>Status</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Request Date</th>
                    <th>Equipment (ID: Count)</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
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
                        echo "<td>" . $row['equipment'] . "</td>";
                        echo "<td>
                            <form method='POST' action='process_request.php'>
                                <input type='hidden' name='request_no' value='" . $row['request_no'] . "'>
                                <input type='hidden' name='empId' value='" . $empId . "'>
                                <div class='btn-group' role='group'>
                                    <input type='submit' name='action' value='Accept' class='btn btn-primary'>
                                    <input type='submit' name='action' value='Reject' class='btn btn-danger'>
                                </div>
                            </form>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No pending requests found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>

<?php
$conn->close();
?>