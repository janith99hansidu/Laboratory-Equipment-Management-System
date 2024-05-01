<?php
session_start();
// Include the database connection file
require_once 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

// Retrieve registration number from query parameter
$regId = $_GET['regId'] ?? null;

// Display user's registration number
if ($regId) {
    echo "Your registration number is: $regId";
} else {
    echo "Registration number not found.";
}

// Fetch equipment data
$query = "SELECT equip_id, name FROM equipment";
$result = $conn->query($query);
$equipments = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $equipments[] = $row;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect POST data
    $equipId = $_POST['equip_dropdown'] ?? null;
    $qty = $_POST['qty_dropdown'] ?? null;
    $requestDate = $_POST['request_date'] ?? null;
    $startTime = $_POST['start_time'] ?? null;
    $endTime = $_POST['end_time'] ?? null;
    $dateTime = date('Y-m-d H:i:s'); // Current date and time

    // Prepare SQL Insert Query
    $insertQuery = $conn->prepare("INSERT INTO request (student_reg, date_time, status, start_time, end_time, request_date) VALUES (?, ?, 'pending', ?, ?, ?)");
    $insertQuery->bind_param("sssss", $regId, $dateTime, $startTime, $endTime, $requestDate);
    $insertQuery->execute();

    if ($insertQuery->error) {
        echo "Error inserting data: " . $insertQuery->error;
    } else {
        echo "Request submitted successfully!";
    }

    $requestId = $conn->insert_id;
    //make next query for adding 
    $selectedEquipments = json_decode($_POST['selected_equipments'], true);

    // Insert each selected equipment into the requestequipment table
    foreach ($selectedEquipments as $item) {
        $insertEquipQuery = $conn->prepare("INSERT INTO requestequipment (request_no, euip_id, count) VALUES (?, ?, ?)");
        $insertEquipQuery->bind_param("iii", $requestId, $item['equipId'], $item['quantity']);
        $insertEquipQuery->execute();
        if ($insertEquipQuery->error) {
            echo "Error inserting equipment data: " . $insertEquipQuery->error;
        }
        $insertEquipQuery->close();
    }
    $insertQuery->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laboratory Management</title>
    <!-- Include jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
                <a class="nav-item nav-link active" href="#">Home <span class="sr-only">(current)</span></a>
                <a class="nav-item nav-link" href="equipment.php?regId=<?= htmlspecialchars($regId) ?>">Equipments</a>
                <a class="nav-item nav-link" href="#">About</a>
                <a class="nav-item nav-link" href="#">Contact</a>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <form id="equipmentForm" method="post">
            <div class="form-group">
                <label for="equip_dropdown">Select Equipment:</label>
                <select id="equip_dropdown" name="equip_dropdown" class="form-control">
                    <option value="">-- Select Equipment --</option>
                    <?php foreach ($equipments as $equip) : ?>
                        <option value="<?= $equip['equip_id']; ?>"><?= $equip['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="qty_dropdown">Available Quantity:</label>
                <select id="qty_dropdown" name="qty_dropdown" class="form-control">
                    <!-- Quantities will be filled in by JavaScript -->
                </select>
            </div>

            <button type="button" id="addBtn" class="btn btn-primary mb-2">Add</button>
            <div id="selectedItems"></div>

            <div class="form-group">
                <label for="request_date">Request Date:</label>
                <input type="date" id="request_date" name="request_date" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="appt">Select Start Time:</label>
                <input type="time" id="appt" name="start_time" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="appt">Select End Time:</label>
                <input type="time" id="appt" name="end_time" class="form-control" required>
            </div>

            <input type="submit" value="Submit Request" class="btn btn-success">
            <input type="hidden" id="selected_equipments" name="selected_equipments">
        </form>
    </div>

    <div class="container mt-4">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Reservation Date</th>
                    <th>Items</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>End Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php include 'display_reservations.php'; ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            $('#equip_dropdown').change(function() {
                var equipId = $(this).val();
                if (equipId) {
                    $.ajax({
                        url: 'get_quantities.php', // This PHP file needs to be created
                        type: 'POST',
                        data: {
                            equip_id: equipId
                        },
                        success: function(response) {
                            $('#qty_dropdown').html(response); // Update the quantity dropdown
                        },
                        error: function() {
                            alert('Error fetching quantities');
                        }
                    });
                } else {
                    $('#qty_dropdown').html('<option>Select equipment first</option>');
                }
            });
        });

        var selectedEquipments = [];
        $('#addBtn').click(function() {
            var equipId = $('#equip_dropdown').val();
            var equipName = $('#equip_dropdown option:selected').text();
            var quantity = $('#qty_dropdown').val();
            if (equipId && quantity) {
                var existing = selectedEquipments.find(item => item.equipId === equipId);
                if (!existing) {
                    selectedEquipments.push({
                        equipId: equipId,
                        quantity: quantity
                    });
                    $('#selectedItems').append('<p>' + equipName + ' - Quantity: ' + quantity + '</p>');
                } else {
                    alert('This equipment has already been added. You can remove it if you want to change the quantity.');
                }
            } else {
                alert('Please select both equipment and quantity.');
            }
        });

        $('#equipmentForm').submit(function(e) {
            e.preventDefault();
            $('#selected_equipments').val(JSON.stringify(selectedEquipments));
            this.submit();
        });
    </script>
</body>

</html>