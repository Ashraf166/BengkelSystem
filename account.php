<?php
session_start();

// Check if the user is not logged in and redirect them to the login page

if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') {
        header("Location: admin_dash.php");
        exit();
    }
} else {
    header("Location: index.php"); // Redirect to login page if not logged in
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

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Account</title>
    <link rel="stylesheet" type="text/css" href="include/account.css">


</head>
<body>

    <?php include 'include/header.php'; ?>

    <h1>Welcome, <?php echo $user_info['name']; ?>!</h1>

<div class="user-details">
    <!-- Display user information -->
    <?php if (!empty($user_info['profile_picture'])) : ?>
        <img class="profile-picture" src="<?php echo $user_info['profile_picture']; ?>" alt="Profile Picture">
    <?php endif; ?>

    <p><strong>User ID:</strong> <?php echo $user_info['User_ID']; ?></p>
    <p><strong>Username:</strong> <?php echo $user_info['username']; ?></p>
    <p><strong>Name:</strong> <?php echo $user_info['name']; ?></p>
    <p><strong>Address:</strong> <?php echo $user_info['address']; ?></p>
    <p><strong>Phone Number:</strong> <?php echo $user_info['phone_number']; ?></p>
    <p><strong>Vehicle Type:</strong> <?php echo $user_info['vehicle_type']; ?></p>
    <p><strong>License Plate:</strong> <?php echo $user_info['license_plate']; ?></p>
    <p><strong>Email:</strong> <?php echo $user_info['email']; ?></p>
    <p><strong>User Type:</strong> <?php echo $user_info['user_type']; ?></p>
        <!-- Add an "Edit" button to allow the user to edit their profile -->
    <div class="button-container">
        <a href="editprofile.php" class="edit-button">EDIT PROFILE</a>
        <a href="logout.php" class="edit-button">LOGOUT</a>
    </div>
</div>
<br>



    <!-- Include the footer -->
    <?php include 'include/footer.php'; ?>

</body>
</html>
