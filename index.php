<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            background: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            width: 100%;
            max-width: 400px;
            margin: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-body {
            padding: 2rem;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .form-label {
            font-weight: bold;
        }

        .alert {
            margin-top: 1rem;
        }

        .login-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 400px;
            margin: auto;
        }

        .login-card img {
            display: block;
            margin: 0px auto;
            width: 150px; /* Set the desired width */
            height: 150px;
        }

        .login-card .card-body {
            padding: 2rem;
        }

        .login-card h2 {
            margin-bottom: 1.5rem;
            font-weight: bold;
        }

        .login-card .form-control {
            margin-bottom: 1rem;
        }

        .login-card .btn {
            width: 100%;
            padding: 0.75rem;
            font-size: 1rem;
        }

        .login-card .btn:hover {
            background-color: #0056b3;
        }

        .login-card .register-link {
            text-align: center;
            display: block;
            margin-top: 1rem;
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
        <div class="login-card card">
            <img src="Assets/book1.png" alt="3D Book Image">
            <div class="card-body">
                <h2>Login</h2>
                <form method="post">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <?php if (!empty($errorMsg)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo htmlspecialchars($errorMsg); ?>
                        </div>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-primary">Login</button>
                    <a href="register.php" class="register-link">Don't have an account? Register Here</a>
                </form>
            </div>
        </div>
    <?php else : ?>
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
        <p>Your role: <?php echo htmlspecialchars($_SESSION['role']); ?></p>
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
