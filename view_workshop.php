<?php
session_start();
// DB Configuration
require_once 'config.php';

// Initialize $error_message with a default value
$error_message = null;

// Retrieve workshop information from the 'workshop' table
$query = "SELECT * FROM workshop";
$result = mysqli_query($conn, $query);

    // Check if the form is submitted for deleting a workshop
    if (isset($_POST['delete_workshop'])) {
        $workshop_id = filter_input(INPUT_POST, 'workshop_id', FILTER_SANITIZE_NUMBER_INT);

        // Use prepared statements to delete the workshop from the 'workshop' table
        $delete_query = "DELETE FROM workshop WHERE Workshop_ID = ?";
        $stmt = mysqli_prepare($conn, $delete_query);
        mysqli_stmt_bind_param($stmt, 'i', $workshop_id);
        $delete_result = mysqli_stmt_execute($stmt);

        if ($delete_result) {
            // Workshop deleted successfully, refresh the page to see the updated list
            header("Location: view_workshop.php");
            exit();
        } else {
            // Handle any errors that occurred during the deletion process
            $error_message = "Failed to delete the workshop. Please try again.";
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    }

// Check if the form is submitted for editing a workshop
if (isset($_POST['edit_workshop'])) {
    $workshop_id = filter_input(INPUT_POST, 'workshop_id', FILTER_SANITIZE_NUMBER_INT);
    $workshop_name = filter_input(INPUT_POST, 'workshop_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $contact_info = filter_input(INPUT_POST, 'contact_info', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Use prepared statements to update the workshop details in the 'workshop' table
    $update_query = "UPDATE workshop SET Workshop_Name = ?, Location = ?, Contact_Info = ?, Description = ? WHERE Workshop_ID = ?";
    $stmt = mysqli_prepare($conn, $update_query);
    mysqli_stmt_bind_param($stmt, 'ssssi', $workshop_name, $location, $contact_info, $description, $workshop_id);
    $update_result = mysqli_stmt_execute($stmt);

    if ($update_result) {
        // Workshop details updated successfully, refresh the page to see the updated list
        header("Location: view_workshop.php");
        exit();
    } else {
        // Handle any errors that occurred during the update process
        $error_message = "Failed to update the workshop details. Please try again.";
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Workshop Management</title>
    <link rel="stylesheet" type="text/css" href="/include/search.css">
    <script>
        // Function to show the confirmation popup for update
        function showUpdateConfirmation() {
            return confirm("Are you sure you want to update this workshop?");
        }

        // Function to show the confirmation popup for delete
        function showDeleteConfirmation() {
            return confirm("Are you sure you want to delete this workshop?");
        }
    </script>
</head>
<body>
    <a href="logout.php"><button type="button" style="background-color: darkgrey;">LOGOUT</button></a>
    <table>
        <tr>
            <th colspan="6"><h2>WORKSHOP RECORD</h2></th>
        </tr>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Location</th>
                <th>Contact Info</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
<tbody>
    <?php
    while ($rows = mysqli_fetch_assoc($result)) {
        ?>
        <tr>
            <td><?php echo $rows['Workshop_ID']; ?></td>
            <td><?php echo $rows['Workshop_Name']; ?></td>
            <td><?php echo $rows['Location']; ?></td>
            <td><?php echo $rows['Contact_Info']; ?></td>
            <td><?php echo $rows['Description']; ?></td>
            <td>

        <form method="post" onsubmit="return showUpdateConfirmation();">
            <input type="hidden" name="workshop_id" value="<?php echo $rows['Workshop_ID']; ?>">
            <!-- Add additional form fields for updating the workshop information -->
            <input type="text" name="workshop_name" placeholder="New Workshop Name" value="<?php echo $rows['Workshop_Name']; ?>">
            <input type="text" name="location" placeholder="New Location" value="<?php echo $rows['Location']; ?>">
            <input type="text" name="contact_info" placeholder="New Contact Info" value="<?php echo $rows['Contact_Info']; ?>">
            <textarea name="description" placeholder="New Description"><?php echo $rows['Description']; ?></textarea>
            <button type="submit" name="edit_workshop">Update</button>
            <form method="post" onsubmit="return showDeleteConfirmation();">
            <input type="hidden" name="workshop_id" value="<?php echo $rows['Workshop_ID']; ?>">
            <button type="submit" name="delete_workshop">Delete</button>
        </form>
            </td>
        </tr>

        <?php
    }
    ?>
        </tbody>
    </table>
</body>
</html>