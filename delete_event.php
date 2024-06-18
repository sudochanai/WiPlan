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
$query = "DELETE FROM events WHERE event_id = $1";
$result = pg_query_params($conn, $query, array($event_id));

if ($result) {
    $_SESSION['message'] = "Event deleted successfully.";
    $_SESSION['message_type'] = "success";
    header("Location: manage_events.php");
    exit();
} else {
    $_SESSION['message'] = "Failed to delete event.";
    $_SESSION['message_type'] = "danger";
    header("Location: manage_events.php");
    exit();
}

pg_close($conn);
?>
