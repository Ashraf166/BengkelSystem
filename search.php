<?php
session_start();
// DB Configuration
require_once 'config.php';

// Initialize $error_message with a default value
$error_message = null;

// Retrieve workshop information from the 'workshop' table
$query = "SELECT * FROM workshop";
$result = mysqli_query($conn, $query);

// Check if the form is submitted for booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $workshop_id = filter_input(INPUT_POST, 'workshop_id', FILTER_SANITIZE_NUMBER_INT);
    $booking_date = filter_input(INPUT_POST, 'booking_date', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $booking_time = filter_input(INPUT_POST, 'booking_time', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Check for duplicate bookings
    $duplicate_query = "SELECT * FROM booking WHERE Workshop_ID = ? AND Booking_Date = ? AND Booking_Time = ?";
    $duplicate_stmt = mysqli_prepare($conn, $duplicate_query);
    mysqli_stmt_bind_param($duplicate_stmt, 'iss', $workshop_id, $booking_date, $booking_time);
    mysqli_stmt_execute($duplicate_stmt);
    $duplicate_result = mysqli_stmt_get_result($duplicate_stmt);

    // If a booking already exists, display an error message and prevent the booking
    if (mysqli_num_rows($duplicate_result) > 0) {

        $error_message = "A booking for this workshop at the selected date and time already exists. Please choose a different date and time.";
    } else {
        // Use prepared statements to insert the booking information into the 'booking' table
        $insert_query = "INSERT INTO booking (User_ID, Workshop_ID, Booking_Date, Booking_Time, Created_At)
                         VALUES (?, ?, ?, ?, NOW())";
        $stmt = mysqli_prepare($conn, $insert_query);
        mysqli_stmt_bind_param($stmt, 'iiss', $user_id, $workshop_id, $booking_date, $booking_time);
        $insert_result = mysqli_stmt_execute($stmt);

        if ($insert_result) {
            // Booking successful, redirect to the confirmation.php page
            $booking_id = mysqli_insert_id($conn); // Get the last inserted booking ID
            $_SESSION['booking_id'] = $booking_id; // Store the booking ID in the session
            header("Location: confirmation.php");
            exit();
        } else {
            // Handle any errors that occurred during the booking process
            $error_message = "Failed to book the workshop. Please try again.";
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    }

    // Close the duplicate booking check prepared statement
    mysqli_stmt_close($duplicate_stmt);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Workshop</title>
    <link rel="stylesheet" type="text/css" href="/include/search.css">
</head>
<body>
    <table>
        <tr>
            <th colspan="5"><h2>WORKSHOP RECORD</h2></th>
        </tr>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Location</th>
                <th>Contact Info</th>
                <th>Description</th>
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
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>

    <!-- User Book Workshop -->
    <h2>Select Your Workshop</h2>
    <?php if (isset($error_message)) : ?>
        <p><?php echo $error_message; ?></p>
    <?php endif; ?>

    <?php if (isset($confirmation_message)) : ?>
        <p><?php echo $confirmation_message; ?></p>
    <?php else : ?>
        <form id="bookingForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="workshop_id">Select Workshop:</label>
            <select name="workshop_id" id="workshop_id" required>
                <?php
                // Display the workshops in the dropdown
                mysqli_data_seek($result, 0); // Reset the pointer to the beginning
                while ($rows = mysqli_fetch_assoc($result)) {
                    echo '<option value="' . $rows['Workshop_ID'] . '">' . $rows['Workshop_Name'] . '</option>';
                }
                ?>
            </select>

            <label for="booking_date">Booking Date:</label>
            <input type="date" name="booking_date" id="booking_date" required>

            <label for="booking_time">Booking Time:</label>
            <input type="time" name="booking_time" id="booking_time" required>

            <!-- Move the button inside the form -->
            <button type="button" onclick="showConfirmation()">Book</button>
        </form>
    <?php endif; ?>

    <script>
    // Function to show the popup confirmation
    function showConfirmation() {
        // Display the popup dialog with the specified message
        var confirmation = confirm("Are you sure you want to book this workshop?");

        // Check if the user clicked "OK"
        if (confirmation) {
            // If yes, submit the form
            document.getElementById("bookingForm").submit();
        } else {
            // If no, do nothing or provide any desired action
            // For example, you can redirect the user to another page or show a message
            alert("Booking canceled.");
        }
    }
    </script>
</body>
</html>
