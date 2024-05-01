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
    <title>Select Equipment</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div>
        <form id="equipmentForm" method="post">
            <label for="equip_dropdown">Select Equipment:</label>
            <select id="equip_dropdown" name="equip_dropdown">
                <option value="">-- Select Equipment --</option>
                <?php foreach ($equipments as $equip) : ?>
                    <option value="<?= $equip['equip_id']; ?>"><?= $equip['name']; ?></option>
                <?php endforeach; ?>
            </select>

            <br>
            <label for="qty_dropdown">Available Quantity:</label>
            <select id="qty_dropdown" name="qty_dropdown">
                <!-- Quantities will be filled in by JavaScript -->
            </select>
            <input type="hidden" id="selected_equipments" name="selected_equipments">
            <br>
            <button type="button" id="addBtn">Add</button>
            <div id="selectedItems"></div>
            <br>
            <label for="request_date">Request Date:</label>
            <input type="date" id="request_date" name="request_date" required>
            <br>
            <label for="appt">Select Start Time:</label>
            <input type="time" id="appt" name="start_time" required>
            <br>
            <label for="appt">Select End Time:</label>
            <input type="time" id="appt" name="end_time" required>
            <br>
            <input type="submit" value="Submit Request">
        </form>
    </div>
    <div class="half">
        <!-- Second half of the page where the table will be -->
        <table>
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
                ?>
            </tbody>
        </table>
    </div>

    <!--JavaScript -->
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


        var selectedEquipments = []; // Array to store equipment and quantities
        $('#addBtn').click(function() {
            var equipId = $('#equip_dropdown').val(); // Get selected equipment ID
            var equipName = $('#equip_dropdown option:selected').text(); // Get equipment name for display
            var quantity = $('#qty_dropdown').val(); // Get selected quantity
            if (equipId && quantity) {
                // Check if the equipment ID already exists in the array
                var existing = selectedEquipments.find(item => item.equipId === equipId);
                if (!existing) {
                    // Add new item to the array
                    selectedEquipments.push({
                        equipId: equipId,
                        quantity: quantity
                    });
                    // Append a new line to the display div
                    $('#selectedItems').append('<p>' + equipName + ' - Quantity: ' + quantity + '</p>');
                } else {
                    alert('This equipment has already been added. You can remove it if you want to change the quantity.');
                }
            } else {
                alert('Please select both equipment and quantity.');
            }
        });


        $('#equipmentForm').submit(function(e) {
            // Prevent the default form submission
            e.preventDefault();

            // Update the hidden input with the JSON string of selectedEquipments
            $('#selected_equipments').val(JSON.stringify(selectedEquipments));

            // Now submit the form
            this.submit(); // or $(this).unbind('submit').submit() if submit event is bound
        });
    </script>
</body>

</html>