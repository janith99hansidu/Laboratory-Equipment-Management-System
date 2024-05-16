<?php session_start(); ?>
<?php require_once('inc/connection.php'); ?>
<?php 
	// checking if a user is logged in
	// i deleted something *****


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
	status = 'returned'";
	$users = mysqli_query($connection, $query);1

	if ($users) {
		while ($user = mysqli_fetch_assoc($users)) {
			$user_list .= "<tr>";
			$user_list .= "<td>{$user['student_reg']}</td>";
			$user_list .= "<td>{$user['name']}</td>"; 
			$user_list .= "<td>{$user['date_time']}</td>";
			$user_list .= "<td>{$user['request_date']}</td>";
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
		<h1>Borrowed Equipment</h1>

		<table class="masterlist">
			<tr>
				<th>Reg No</th>
				<th>Item</th>
				<th>Borrowed date&time</th>
				<th>Returned date</th>
	
			</tr>

			<?php echo $user_list; ?>

		</table>
		
		
	</main>
</body>
</html>