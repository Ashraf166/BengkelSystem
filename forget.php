<?php
session_start();
require_once 'config2.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the username and new password entered by the user
    $username = $_POST['username'];
    $new_password = $_POST['new_password'];

    // Validate and sanitize the inputs (add your own validation rules)

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Check if the username exists in the database
    try {
        $conn = new mysqli($servername, $db_username, $db_password, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the username exists in the database
        if ($result->num_rows > 0) {
            // Update the user's password in the database
            $update_stmt = $conn->prepare("UPDATE user SET password = ? WHERE username = ?");
            $update_stmt->bind_param("ss", $hashed_password, $username);
            $update_stmt->execute();
            $update_stmt->close();

            // Password updated successfully, redirect the user to a success page or show a success message
        $_SESSION['success_message'] = "Password updated successfully!";
            header("Location: index.php");  
            exit();
        } else {
            // Username not found in the database, redirect the user to index.php
            header("Location: register.php");
            exit();
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        // Handle the database error (optional)
        die("Error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" type="text/css" href="include/forget.css">
</head>
<body>
    <h1>Forgot Password</h1>
    <p>Please enter your username and new password:</p>
    <form method="post" action="">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="new_password" placeholder="New Password" required><br>
        <button type="submit">Reset Password</button>
    </form>

    <!-- Popup message -->
    <div id="popup" style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background-color: #fff; padding: 20px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
        <p>Password successfully changed!</p>
        <button onclick="hidePopup()">Close</button>
    </div>

    <!-- JavaScript to show and hide the popup -->
    <script>
        function showPopup() {
            document.getElementById('popup').style.display = 'block';
        }

        function hidePopup() {
            document.getElementById('popup').style.display = 'none';
            window.location.href = 'index.php'; // Redirect to login page after closing popup
        }

        // Check if the password reset was successful and show the popup
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
            <?php if ($result->num_rows > 0) : ?>
                showPopup();
            <?php endif; ?>
        <?php endif; ?>
    </script>
</body>
</html>

