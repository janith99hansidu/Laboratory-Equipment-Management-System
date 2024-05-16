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
			$query = "UPDATE request SET status = \"borrowed\" , date_time = NOW() WHERE request_no = $request_no";
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
	status = 'approved'";
	$users = mysqli_query($connection, $query);

	if ($users) {
		while ($user = mysqli_fetch_assoc($users)) {
			$user_list .= "<tr>";
			$user_list .= "<td>{$user['student_reg']}</td>";
			$user_list .= "<td>{$user['name']}</td>"; 
			$user_list .= "<td>{$user['date_time']}</td>";
			$user_list .= "<td>";
			$user_list .="<a href='accepted-reservation.php?action=1&request_no={$user['request_no']}&equip_id={$user['euip_id']}'>Borrow</a>";
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
</head>
<body>
	<header>
		<div class="appname">User Management System</div>
		
	</header>

	<main>
		<h1>Accepted Reservation</h1>

		<table class="masterlist">
			<tr>
				<th>Reg No</th>
				<th>Item</th>
				<th>accepted date&time</th>
				<th>Action</th>
	
			</tr>

			<?php echo $user_list; ?>

		</table>
		
		
	</main>
</body>
</html>