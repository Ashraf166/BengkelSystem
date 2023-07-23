<?php
session_start();
// DB Configuration
require_once 'config.php';

// Check if the booking_id session variable is set
if (isset($_SESSION['user_id'], $_SESSION['booking_id'])) {
    $user_id = $_SESSION['user_id'];
    $booking_id = $_SESSION['booking_id'];

    
    $query = "SELECT w.Workshop_Name, w.Location, w.Contact_Info, w.Description, b.Booking_Date, b.Booking_Time
              FROM workshop w
              INNER JOIN booking b ON w.Workshop_ID = b.Workshop_ID
              WHERE b.User_ID = ? AND b.Booking_ID = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $user_id, $booking_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Fetch the workshop details from the query result
    if ($row = mysqli_fetch_assoc($result)) {
        $workshop_name = $row['Workshop_Name'];
        $location = $row['Location'];
        $contact_info = $row['Contact_Info'];
        $description = $row['Description'];
        $booking_date = $row['Booking_Date'];
        $booking_time = $row['Booking_Time'];
    } else {
        // If the workshop details are not found, handle the error (optional)
        $error_message = "Workshop details not found.";
    }

    // Close the prepared statement
    mysqli_stmt_close($stmt);
} else {
    // If the booking_id session variable is not set, redirect to the booking page
    header("Location: booking.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirmation - Workshop Booking System</title>
<style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            color: #333;
        }

        h1 {
            text-align: center;
            margin-top: 30px;
        }

        p {
            text-align: center;
            margin-bottom: 15px;
        }

        strong {
            font-weight: bold;
        }

        .confirmation-details, .make-payments {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color:#4CAF50;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            border: none;
            transition: background-color 0.2s ease;
        }

        .back-button:hover {
            background-color: darkgreen;
        }


    </style>
</head>
<body>
    <h1>Booking Confirmation</h1>
    <?php if (isset($error_message)) : ?>
        <p><?php echo $error_message; ?></p>
    <?php else : ?>
        <div class="confirmation-details">
            <p>Thank you for booking the following workshop:</p>
            <p><strong>Booking Id:</strong> <?php echo $booking_id; ?></p>
            <p><strong>Workshop Name:</strong> <?php echo $workshop_name; ?></p>
            <p><strong>Location:</strong> <?php echo $location; ?></p>
            <p><strong>Contact Info:</strong> <?php echo $contact_info; ?></p>
            <p><strong>Description:</strong> <?php echo $description; ?></p>
            <p><strong>Booking Date:</strong> <?php echo $booking_date; ?></p>
            <p><strong>Booking Time:</strong> <?php echo $booking_time; ?></p>
        </div>
    <?php endif; ?>
    <div class="make-payments">
    <p><a href="account.php" class="back-button">Back to My Account</a></p>
    <p><a href="payment.php?booking_id=<?php echo $booking_id; ?>" class="back-button">Make Payment</a></p>    
    </div>
    
</body>
</html>
