<?php
session_start();

// DB Configuration
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Retrieve the user ID from the session
$user_id = $_SESSION['user_id'];

// Retrieve booking information from the 'booking' table for the specific user
$query = "SELECT * FROM booking WHERE User_ID = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, 'i', $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Check if there are bookings for the user
$bookings = array();
if (mysqli_num_rows($result) > 0) {
    // Fetch booking details and store them in an array
    while ($row = mysqli_fetch_assoc($result)) {
        $bookings[] = $row;
    }
}

// Close the prepared statement
mysqli_stmt_close($stmt);

?>

<!DOCTYPE html>
<html>
<head>
    <title>User Bookings</title>
    <link rel="stylesheet" type="text/css" href="include/mybooking.css">
</head>
<body>
    <h1>User Bookings</h1>
        <div class="button-container">
        <a href="javascript:history.go(-1)">Go Back</a>
        <a href="logout.php">Logout</a>
    </div>

    <?php if (empty($bookings)) : ?>
        <p>No bookings found for this user.</p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>User ID</th>
                    <th>Workshop ID</th>
                    <th>Booking Date</th>
                    <th>Booking Time</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bookings as $booking) : ?>
                    <tr>
                        <td><?php echo $booking['Booking_ID']; ?></td>
                        <td><?php echo $booking['User_ID']; ?></td>
                        <td><?php echo $booking['Workshop_ID']; ?></td>
                        <td><?php echo $booking['Booking_Date']; ?></td>
                        <td><?php echo $booking['Booking_Time']; ?></td>
                        <td><?php echo $booking['Created_At']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <a href="logout.php">Logout</a>

    <!-- Include the footer -->
    <?php include 'include/footer.php'; ?>
</body>
</html>
