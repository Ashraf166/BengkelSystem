<?php
session_start();

// Check if the user is an admin
if (isset($_SESSION['user_id'])) {
    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == 'admin') {
        // Redirect the admin to a page with appropriate access control message
        header("Location: access_denied.php");
        exit();
    }
}

require_once 'config.php';

// Initialize variables to store form data
$workshop_name = '';
$location = '';
$contact_info = '';
$description = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize the form data
    $workshop_name = mysqli_real_escape_string($conn, $_POST['workshop_name']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $contact_info = mysqli_real_escape_string($conn, $_POST['contact_info']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    // Validate the form data (you can add more validation as needed)
    if (empty($workshop_name) || empty($location) || empty($contact_info) || empty($description)) {
        $error_message = "Please fill in all the fields.";
    } else {
        // Insert the new workshop into the 'workshop' table
        $query = "INSERT INTO workshop (workshop_name, location, contact_info, Description) 
                  VALUES ('$workshop_name', '$location', '$contact_info', '$description')";
        $result = mysqli_query($conn, $query);

        if ($result) {
            // Workshop registration successful, redirect to the workshop management page
            header("Location: workshop_manage.php");
            exit();
        } else {
            // Handle any errors that occurred during workshop registration
            // Display an error message or redirect the admin to an error page
            $error_message = "Workshop registration failed. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Workshop</title>
    <link rel="stylesheet" type="text/css" href="include/Workshop.css">

</head>
<body>

    <?php include 'include/header2.php'; ?>

    <h1>Register New Workshop</h1>

    <?php if (isset($error_message)) : ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>

    <form method="POST" action="" onsubmit="return showConfirmation()">
        <label for="workshop_name">Workshop Name:</label>
        <input type="text" name="workshop_name" id="workshop_name" value="<?php echo $workshop_name; ?>" required>

        <label for="location">Location:</label>
        <input type="text" name="location" id="location" value="<?php echo $location; ?>" required>

        <label for="contact_info">Contact Info:</label>
        <input type="text" name="contact_info" id="contact_info" value="<?php echo $contact_info; ?>" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" required><?php echo $description; ?></textarea>

        <button type="submit">REGISTER WORKSHOP</button>
        <br>
        <a href="logout.php"><button type="button">LOGOUT</button></a>
        
    </form>

    <script>
        function showConfirmation() {
            // Show the confirmation popup
            var isConfirmed = confirm("Are you sure you want to register this workshop?");

            // If the admin confirms, proceed with form submission; otherwise, cancel it
            return isConfirmed;
        }
    </script>

    <!-- Include the footer -->
    <?php include 'include/footer.php'; ?>

</body>
</html>
