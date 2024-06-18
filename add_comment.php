<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['privileges'] != 'attendee') {
    header("Location: login.php");
    exit();
}

// Connect to the database
include("conn.php");

// Get form data
$event_id = $_POST['event_id'];
$user_id = $_SESSION['user_id'];
$content = $_POST['content'];
$timestamp = date('Y-m-d H:i:s');

// Insert comment into the database
$query = "INSERT INTO comments (event_id, user_id, content, timestamp) VALUES ($event_id, $user_id, '$content', '$timestamp')";
pg_query($conn, $query);

pg_close($conn);

// Redirect to event details
header("Location: event_details.php?event_id=$event_id");
?>
