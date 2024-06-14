<?php
session_start();
include 'conn.php'; // Include database connection

// Check if user is logged in and is an attendee
if (!isset($_SESSION['user_id']) || $_SESSION['privileges'] != 'attendee') {
    header("Location: login.php");
    exit();
}

// Get event ID from URL parameter
$event_id = $_GET['event_id'];

// Fetch event details
$query = "SELECT * FROM events WHERE event_id = $event_id";
$result = pg_query($conn, $query);
$event = pg_fetch_assoc($result);

// Check if event is paid
if ($event['mode'] == 'paid') {
    // Display payment form (not implemented in this example)
    echo "Payment form goes here";
} else {
    // Insert registration into the database
    $user_id = $_SESSION['user_id'];
    $barcode = uniqid(); // Generate a unique barcode

    $query = "INSERT INTO registrations (event_id, user_id, barcode) VALUES ($event_id, $user_id, '$barcode')";
    $result = pg_query($conn, $query);

    if ($result) {
        header("Location: manage_events.php");
        exit();
    } else {
        echo "Error: Failed to sign up for event.";
    }
}

pg_close($conn);
?>
