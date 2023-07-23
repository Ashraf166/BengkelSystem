<?php
session_start();

require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and validate the registration form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $vehicle_type = $_POST['vehicle_type'];
    $license_plate = $_POST['license_plate'];
    $email = $_POST['email'];
    $user_type = $_POST['user_type']; // New field for user_type

    // Handle profile picture upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $profile_picture = $_FILES['profile_picture']['name']; // Get the name of the uploaded file
        $temp_name = $_FILES['profile_picture']['tmp_name']; // Get the temporary location of the uploaded file
        $upload_dir = "uploads/"; // Specify the directory where you want to store the uploaded images
        $profile_picture_path = $upload_dir . $profile_picture; // Create the file path where the image will be stored

        // Check if the uploaded file is an image
        $allowedExtensions = array('jpg', 'jpeg', 'png', 'gif');
        $profilePictureFileType = strtolower(pathinfo($profile_picture_path, PATHINFO_EXTENSION));
        if (!in_array($profilePictureFileType, $allowedExtensions)) {
            $error_message = "Invalid profile picture format. Only JPG, JPEG, PNG, and GIF formats are allowed.";
        } else {
            // Move the uploaded profile picture to the target directory
            if (move_uploaded_file($temp_name, $profile_picture_path)) {
                // Hash the password for security
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                // Insert the new user's information into the 'user' table, including user_type
                $query = "INSERT INTO user (profile_picture, username, password, name, address, phone_number, vehicle_type, license_plate, email, created_at, updated_at, user_type) 
                VALUES ('$profile_picture_path', '$username', '$hashedPassword', '$name', '$address', '$phone_number', '$vehicle_type', '$license_plate', '$email', NOW(), NOW(), '$user_type')";

                $result = mysqli_query($conn, $query);

                if ($result) {
                    // Registration successful, redirect the user to the login page or display a success message
                    echo '<script>alert("Registration successful! You can now log in."); window.location = "index.php";</script>';
                    exit();
                } else {
                    // Handle any errors that occurred during the registration process
                    // Display an error message or redirect the user to an error page
                    $error_message = "Registration failed. Please try again.";
                }
            } else {
                $error_message = "Failed to upload profile picture. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration</title>
<style>
    /* CSS code to center the registration form */
    body {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 126vh;
        margin: 0;
    }

    /* CSS code for the registration form */
    form {
        width: 400px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f9f9f9;
    }

    form label {
        display: block;
        margin-bottom: 5px;
    }

    form input[type="text"],
    form input[type="password"],
    form input[type="email"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    form button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
</style>
</head>
<body>

    <h1>User Registration</h1>

    <?php if (isset($error_message)) : ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label for="profile_picture">Profile Picture:</label>
        <input type="file" name="profile_picture" id="profile_picture" accept=".jpg, .jpeg, .png, .gif" required>
        <br><br>
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>
        <br>
        <label for="address">Address:</label>
        <input type="text" name="address" id="address" required>
        <br>
        <label for="phone_number">Phone Number:</label>
        <input type="text" name="phone_number" id="phone_number" required>
        <br>
        <label for="vehicle_type">Vehicle Type:</label>
        <input type="text" name="vehicle_type" id="vehicle_type" required>
        <br>
        <label for="license_plate">License Plate:</label>
        <input type="text" name="license_plate" id="license_plate" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="user_type">User Type:</label>
        <input type="text" name="user_type" id="user_type" required>
        <br>
        <button type="submit">Register</button>
        <br>
    </form>

    <!-- Include the footer -->
    <?php include 'include/footer.php'; ?>

</body>
</html>
