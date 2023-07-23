<?php
session_start();

require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    header("Location: account.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize user inputs
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
    $password = $_POST['password']; // No need to sanitize hashed password

    // Perform further validation, e.g., check for empty fields
    if (empty($username) || empty($password)) {
        $error_message = "Please fill in all the fields.";
    } else {
        // Use prepared statement to prevent SQL injection
        $query = "SELECT * FROM user WHERE username = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // Check if a row is returned
        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $storedPassword = $row['password'];

            // Verify the password using password_verify()
            if (password_verify($password, $storedPassword)) {
                // Password is correct, log in the user
                $_SESSION['user_id'] = $row['User_ID'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['name'] = $row['name'];

                // Redirect the user to the desired page based on user type
                if ($row['user_type'] == 'admin') {
                    header("Location: admin_dash.php");
                } else {
                    header("Location: account.php");
                }
                exit();
            } else {
                // Password is incorrect
                $error_message = "Invalid Username or Password. Please Try Again.";
            }
        } else {
            // User does not exist
            $error_message = "Invalid Username or Password. Please Try Again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>E-FOREMEN</title>
    <link rel="stylesheet" type="text/css" href="include/login.css">
</head>
<body>
    <?php include 'include/header3.php'; ?>

    <h1>E-FOREMEN SYSTEM</h1>

    <?php if (isset($error_message)) : ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="username">USERNAME:</label>
        <input type="text" name="username" id="username" required>

        <label for="password">PASSWORD:</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">LOGIN</button>

     <p>Don't have an account? <a href="register.php">Register here</a>.</p>
    <p>Forget your password? <a href="forget.php">Click here</a>.</p>
    </form>


    <!-- Include the footer -->
    <?php include 'include/footer.php'; ?>
    

</body>
</html>
