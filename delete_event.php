<?php
session_start();
include 'conn.php'; // Include database connection

// Check if user is logged in and is an organizer
if (!isset($_SESSION['user_id']) || $_SESSION['privileges'] != 'organizer') {
    header("Location: login.php");
    exit();
}

// Get event ID from URL parameter
$event_id = $_GET['event_id'];

// Delete event from the database
$query = "DELETE FROM events WHERE event_id = $event_id";
$result = pg_query($conn, $query);

if ($result) {
    header("Location: manage_events.php");
    exit();
} else {
    echo "Error: Failed to delete event.";
}

pg_close($conn);
