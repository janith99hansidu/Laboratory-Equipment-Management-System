<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Custom styles */
        .card {
            margin: 20px;
        }
    </style>
</head>

<body>
    <?php
    // Include the database connection (assuming connection.php uses PDO)
    include 'connection.php';

    // Initialize variables
    $username = '';
    $password = '';
    $errorMsg = '';
    $isLoggedIn = false;
    $regNo = '';

    // Check if form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Validate username and password (replace with your validation logic)
        if (empty($username) || empty($password)) {
            $errorMsg = 'Please enter username and password.';
        } else {
            // Check credentials against database (use prepared statements)
            $stmt = $conn->prepare("SELECT * FROM login WHERE user_name = ? AND acc_status = 'active'");
            $stmt->execute([$username]);
            $result = $stmt->get_result();

            if ($result->num_rows === 0) {
                $errorMsg = 'Invalid username, password, or inactive account.';
            } else {
                $user = $result->fetch_assoc();
                if ($password == $user['password']) {
                    // Login successful
                    $isLoggedIn = true;
                    // Store user information in session (consider secure methods)
                    session_start();
                    $_SESSION['user_name'] = $user['user_name'];
                    $_SESSION['role'] = $user['role'];

                    // redirect to the user.php if he is an student user
                    if ($user['role'] === 'student') {
                        $studentStmt = $conn->prepare("SELECT reg_id FROM Student WHERE email = ?");
                        $studentStmt->bind_param("s", $username); // 's' specifies the variable type => 'string'
                        $studentStmt->execute();
                        $studentStmt->bind_result($regId);
                        $studentStmt->fetch();

                        if ($regId) {
                            header("Location: user.php?regId=" . $regId);
                            exit();
                        } else {
                            // Handle case where user exists in login table but not in Student table
                            $errorMsg = 'User exists but student data not found.';
                        }
                    }

                    // redirect to the admin.php if he is an admin user
                    if ($user['role'] === 'admin') {
                        $studentStmt = $conn->prepare("SELECT emp_id FROM Emp WHERE email = ?");
                        $studentStmt->bind_param("s", $username); // 's' specifies the variable type => 'string'
                        $studentStmt->execute();
                        $studentStmt->bind_result($empId);
                        $studentStmt->fetch();

                        if ($empId) {
                            header("Location: Admin/admin.php?empId=" . $empId);
                            exit();
                        } else {
                            // Handle case where user exists in login table but not in Student table
                            $errorMsg = 'User exists but student data not found.';
                        }
                    }
                } else {
                    $errorMsg = 'Invalid username, password, or inactive account.';
                }
            }
        }
    }

    // Redirect to user.php with regNo if applicable
    if ($isLoggedIn && $regNo) {
        header("Location: user.php?regNo=" . $regNo);
        exit();
    }
    ?>


    <?php if (!$isLoggedIn) : ?>
        <div class="card" style="max-width: 400px; margin: auto; top: 50%; position: relative; transform: translateY(50%);">
            <div class="m-4">
                <h2>Login</h2>
                <form method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <?php if (!empty($errorMsg)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $errorMsg; ?>
                        </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary">Login</button>

                    <p class="mt-3">
                        Don't have an account? <a href="register.php">Register Here</a>
                    </p>
                </form>
            </div>
        </div>


    <?php else : ?>
        <h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
        <p>Your role: <?php echo $_SESSION['role']; ?></p>
        <p>Account status: Active</p>
        <?php
        // Replace this with your desired content or redirection based on user role
        echo '<p>This is content accessible after successful login.</p>';
        ?>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>