<?php
// Assuming you have already included/configured the database connection
// If not, add the appropriate configurations to connect to your database
session_start();
// DB Configuration
require_once 'config.php';

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the booking ID and other payment details from the form
    $booking_id = $_POST['booking_id'];
    $amount = $_POST['amount_input'];
    $currency = $_POST['currency_input'];
    $payment_method = "Your Payment Gateway"; // Replace this with the actual payment gateway used
    $transaction_id = "Your Payment Gateway Transaction ID"; // Replace this with the actual transaction ID from the payment gateway


    if (empty($amount)) {
    // Redirect to payment failure page with an error message
    header("Location: payment_failure.php?booking_id=" . urlencode($booking_id) . "&error=Amount cannot be empty");
    exit();
}
    // Add your payment gateway integration code here
    // Perform the payment transaction using the user's payment information
    // Ensure to handle any errors or exceptions that may occur during payment processing
    // Set the $payment_success variable to true or false based on payment status

    // Simulate payment success (replace this with actual payment gateway response handling)
    $payment_success = true;

    if ($payment_success) {
        // Payment is successful, update the payment details in the database
        $payment_status = "Completed"; // Set the payment status to "Completed"

        // Get the current date for the payment_date field
        $payment_date = date("Y-m-d");

        // Prepare the SQL statement to insert the payment details into the database
        $sql = "INSERT INTO payment (booking_id, payment_amount, payment_date, payment_status, payment_method, transaction_id)
                VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'idsiss', $booking_id, $amount, $payment_date, $payment_status, $payment_method, $transaction_id);
        mysqli_stmt_execute($stmt);

        // Check if the payment details were inserted successfully
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            // Payment details inserted successfully, redirect to payment success page
            header("Location: payment_success.php?booking_id=" . urlencode($booking_id));
            exit();
        } else {
            // Payment details insertion failed, redirect to payment failure page
            header("Location: payment_failure.php?booking_id=" . urlencode($booking_id));
            exit();
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        // Payment failed, redirect to payment failure page
        header("Location: payment_failure.php?booking_id=" . urlencode($booking_id));
        exit();
    }
} else {
    // If the form is not submitted directly, redirect back to the payment.php page
    header("Location: payment.php");
    exit();
}
?>
