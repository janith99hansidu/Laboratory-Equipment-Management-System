<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php 
    // checking if a user is logged in
    // i deleted something *****

    if (isset($_GET['action']) && $_GET['action'] == 1) {
        // Handle accept action
        if (isset($_GET['request_no'])) {
            $request_no = $_GET['request_no'];
            $equip_id = $_GET['equip_id'];
            // Execute your database query here to update the status
            // For example:
            $query = "UPDATE request SET status = \"approved\" , date_time = NOW() WHERE request_no = $request_no";
            mysqli_query($connection, $query);
            // Redirect back to the page
            
            
        }
    }
    if (isset($_GET['action']) && $_GET['action'] == 0) {
        // Handle accept action
        if (isset($_GET['request_no'])) {
            $request_no = $_GET['request_no'];
            $equip_id = $_GET['equip_id'];
            // Execute your database query here to update the status
            // For example:
            $query = "UPDATE request SET status = \"rejected\" WHERE request_no = $request_no";
            mysqli_query($connection, $query);
            // Redirect back to the page
            
            
        }
    }


    $user_list = '';

    // getting the list of users
    $query = "SELECT
    re.* , r.* , e.*
    FROM
    requestequipment as re , request as r , equipment as e
    WHERE
    re.euip_id = e.equip_id
    AND
    r.request_no = re.request_no
    AND 
    status = 'pending'";
    $users = mysqli_query($connection, $query);

    if ($users) {
        while ($user = mysqli_fetch_assoc($users)) {
            $user_list .= "<tr>";
            $user_list .= "<td>{$user['student_reg']}</td>";
            $user_list .= "<td>{$user['name']}</td>"; 
            $user_list .= "<td>{$user['date_time']}</td>";
            $user_list .= "<td class='action-links'>";
            $user_list .="<a class='accept' href='pending-request.php?action=1&request_no={$user['request_no']}&equip_id={$user['euip_id']}'>Accept</a>";
            $user_list .="<a class='reject' href='pending-request.php?action=0&request_no={$user['request_no']}&equip_id={$user['euip_id']}''>Reject</a>";
            $user_list .="</td>";
            $user_list .= "</tr>";
        }
    } else {
        echo "Database query failed.";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Users</title>
    <link rel="stylesheet" href="css/main.css">
    <style>
        /* CSS styles for the User Management System */

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 10px;
            text-align: center;
        }

        .appname {
            font-size: 24px;
        }

        main {
            margin: 20px;
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #333;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-links {
            display: flex;
        }

        .action-links a {
            display: inline-block;
            padding: 5px 10px;
            margin-right: 10px;
            text-decoration: none;
            color: #fff;
            border-radius: 5px;
        }

        .action-links a.accept {
            background-color: #5cb85c;
        }

        .action-links a.reject {
            background-color: #d9534f;
        }
    </style>
</head>
<body>
    <header>
        <div class="appname">User Management System</div>
    </header>

    <main>
        <h1>Pending Request </h1>

        <table class="masterlist">
            <tr>
                <th>Reg No</th>
                <th>Item</th>
                <th>requested date&time</th>
                <th>Action</th>
            </tr>

            <?php echo $user_list; ?>

        </table>
    </main>
</body>
</html>