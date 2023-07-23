<?php
session_start();

// Check if the user is not logged in and redirect them to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

require_once 'config.php';

// Retrieve the user's information from the 'user' table based on their User_ID
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM user WHERE User_ID = '$user_id'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 1) {
    // Fetch the user's information as an associative array
    $user_info = mysqli_fetch_assoc($result);
} else {
    // Handle the case where user information is not found (optional)
    // You can redirect to an error page or display an error message
    $error_message = "User information not found.";
}

// Update user information if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update user information
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];

    // Perform validation or use prepared statements to prevent SQL injection

// Handle the profile picture upload
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $profile_picture = $_FILES['profile_picture']['name']; // Get the name of the uploaded file
    $temp_name = $_FILES['profile_picture']['tmp_name']; // Get the temporary location of the uploaded file
    $upload_dir = "uploads/"; // Specify the directory where you want to store the uploaded images
    $profile_picture_path = $upload_dir . $profile_picture; // Create the file path where the image will be stored

    // Move the uploaded file to the desired location on the server
    move_uploaded_file($temp_name, $profile_picture_path);

    // Update the user's information in the 'user' table, including the profile picture path
    $query = "UPDATE user SET name = '$name', address = '$address', phone_number = '$phone_number', email = '$email',
              profile_picture = '$profile_picture_path'
              WHERE User_ID = '$user_id'";


    } else {
        // If no new profile picture was uploaded, update the user's information without changing the profile picture
        $query = "UPDATE user SET name = '$name', address = '$address', phone_number = '$phone_number', email = '$email'
                  WHERE User_ID = '$user_id'";
    }

    $result = mysqli_query($conn, $query);

    if ($result) {
        // Update successful, refresh the page to show the updated information
        header("Location: account.php");
        exit();
    } else {
        // Handle any errors that occurred during the update process
        $error_message = "Failed to update user information. Please try again.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Profile</title>
    <link rel="stylesheet" type="text/css" href="include/css_editprofile.css">
</head>
<body>

    <h1>Edit Profile</h1>

    <?php if (isset($error_message)) : ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="POST" action="" enctype="multipart/form-data">

        <label for="profile_picture">Profile Picture:</label>
        <input type="file" name="profile_picture" id="profile_picture">
   
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" value="<?php echo $user_info['name']; ?>" required>

        <label for="address">Address:</label>
        <input type="text" name="address" id="address" value="<?php echo $user_info['address']; ?>" required>

        <label for="phone_number">Phone Number:</label>
        <input type="tel" name="phone_number" id="phone_number" value="<?php echo $user_info['phone_number']; ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" value="<?php echo $user_info['email']; ?>" required>

        <button type="submit">Update</button>
    </form>

    <!-- Add a "Back" button to go back to the account page -->
    <a href="account.php" class="back-button">Back</a>

    <!-- Include the footer -->
    <?php include 'include/footer.php'; ?>

</body>
</html>