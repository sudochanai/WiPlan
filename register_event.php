<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['privileges'] != 'attendee') {
    header("Location: login.php");
    exit();
}

include 'conn.php'; // Include database connection

// Start transaction
pg_query($conn, "BEGIN");

// Get form data
$event_id = $_POST['event_id'];
$user_id = $_SESSION['user_id'];
$barcode = uniqid(); // Generate a unique barcode

// Insert registration into the database
$query = "INSERT INTO registrations (event_id, user_id, barcode) VALUES ($event_id, $user_id, '$barcode')";
$result = pg_query($conn, $query);

// Check if registration insertion succeeded
if ($result) {
    // Commit transaction if insertion succeeds
    pg_query($conn, "COMMIT");
    header("Location: event_details.php?event_id=$event_id");
    exit();
} else {
    // Rollback transaction if insertion fails
    pg_query($conn, "ROLLBACK");
    echo "Error: Event registration failed.";
}

pg_close($conn);
?>
