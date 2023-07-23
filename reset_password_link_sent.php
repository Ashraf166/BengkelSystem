<?php
session_start();

// DB Configuration (Replace with your database credentials
require_once 'config.php';

// Check if the reset token is provided in the URL
if (isset($_GET['token'])) {
    $reset_token = $_GET['token'];

    // Check if the reset token exists in the database
    try {
        $conn = new mysqli($servername, $username, $password, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT email FROM password_reset WHERE reset_token = ?");
        $stmt->bind_param("s", $reset_token);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 0) {
            // Reset token not found in the database, handle the error (optional)
            die("Invalid reset token.");
        }

        // Get the email associated with the reset token
        $stmt->bind_result($email);
        $stmt->fetch();
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        // Handle the database error (optional)
        die("Error: " . $e->getMessage());
    }
} else {
    // Reset token not provided in the URL, redirect the user to the forgot password page
    header("Location: forget.php");
    exit();
}

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the new password entered by the user
    $new_password = $_POST['new_password'];

    // Validate and sanitize the new password (add your own password validation rules)
    if (empty($new_password)) {
        die("Password cannot be empty.");
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

    // Update the user's password in the database
    try {
        $conn = new mysqli($servername, $username, $password, $database);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("UPDATE user SET password = ? WHERE email = ?");
        $stmt->bind_param("ss", $hashed_password, $email);
        $stmt->execute();

        // Check if the password was updated successfully
        if (mysqli_affected_rows($conn) > 0) {
            // Password updated successfully, show the success popup message
            echo "<script>showPopup();</script>";
        } else {
            // Password update failed, handle the error (optional)
            die("Password update failed.");
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
    <title>Reset Password</title>
</head>
<body>
    <h1>Reset Password</h1>
    <p>Please enter your new password:</p>
    <form method="post" action="" id="resetForm">
        <input type="password" name="new_password" required>
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
            window.location.href = 'login.php'; // Redirect to login page after closing popup
        }

        // Check if the password reset was successful and show the popup
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') : ?>
            <?php if (isset($email) && $stmt->affected_rows > 0) : ?>
                showPopup();
            <?php endif; ?>
        <?php endif; ?>
    </script>
</body>
</html>

