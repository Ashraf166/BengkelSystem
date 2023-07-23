<?php
session_start();
// DB Configuration
require_once 'config.php';

// Check if the booking_id is passed as a query parameter
if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    $query = "SELECT * FROM booking WHERE Booking_ID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $booking_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Fetch the booking details from the query result
    if ($row = mysqli_fetch_assoc($result)) {
        // Retrieve other details based on the booking
        $workshop_id = $row['Workshop_ID'];
        // ... (Retrieve other details based on your database schema)
    } else {
        // If the booking details are not found, handle the error (optional)
        $error_message = "Booking details not found.";
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
} else {
    // If the booking_id is not passed, redirect to the booking page
    header("Location: booking.php");
    exit();
}

// Process the payment form submission here (if applicable)

?>

<!DOCTYPE html>
<html>
<head>
    <title>Payment</title>
    <link rel="stylesheet" type="text/css" href="include/payment.css">

</head>
<body>
    <h1>Payment Details</h1>
    <?php if (isset($error_message)) : ?>
        <p><?php echo $error_message; ?></p>
    <?php else : ?>
        <div class="payment-form">
            <form action="process_payment.php" method="post"> <!-- Replace 'process_payment.php' with the actual processing script -->
                <!-- Include payment form fields here -->
                <p><strong>Booking ID:</strong> <?php echo $booking_id; ?></p>
                <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                <!-- Add payment form fields as needed, e.g., card details, payment amount, etc. -->
                <input type="text" name="amount_input" placeholder="Enter amount" required>
                <input type="text" name="currency_input" placeholder="Enter currency">


                <!-- Submit button -->
                <input type="submit" value="Make Payment" class="submit-button">
            </form>
        </div>
    <?php endif; ?>

    <p><a href="account.php" class="back-button">Back to My Account</a></p>
</body>
</html>
