<?php
session_start();
include 'conn.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if event ID is set in the POST data
if (!isset($_POST['event_id'])) {
    $_SESSION['message'] = "Error: No event selected.";
    $_SESSION['message_type'] = "danger";
    header("Location: registerToEvent.php?event_id=" . $_POST['event_id']);
    exit();
}

// Get the event ID from the POST data
$event_id = $_POST['event_id'];
$user_id = $_SESSION['user_id'];

// Begin transaction
pg_query($conn, "BEGIN");

try {
    // Check if the user is already registered for the event
    $query_check_registration = "SELECT * FROM registrations WHERE event_id = $1 AND user_id = $2";
    $result_check_registration = pg_prepare($conn, "check_registration", $query_check_registration);
    $result_check_registration = pg_execute($conn, "check_registration", array($event_id, $user_id));

    if (pg_num_rows($result_check_registration) > 0) {
        $_SESSION['message'] = "You have already registered for this event.";
        $_SESSION['message_type'] = "warning";
        header("Location: registerToEvent.php?event_id=" . $event_id);
        exit();
    }

    // Insert registration into the database
    $barcode = uniqid(); // Generate a unique barcode

    $query_insert_registration = "INSERT INTO registrations (event_id, user_id, barcode) VALUES ($1, $2, $3)";
    $result_insert_registration = pg_prepare($conn, "insert_registration", $query_insert_registration);
    $result_insert_registration = pg_execute($conn, "insert_registration", array($event_id, $user_id, $barcode));

    if ($result_insert_registration) {
        // Commit transaction
        pg_query($conn, "COMMIT");
        $_SESSION['message'] = "Successfully registered for the event.";
        $_SESSION['message_type'] = "success";
    } else {
        // Rollback transaction in case of error
        pg_query($conn, "ROLLBACK");
        $_SESSION['message'] = "Error: Failed to register for the event.";
        $_SESSION['message_type'] = "danger";
    }
} catch (Exception $e) {
    // Rollback transaction in case of exception
    pg_query($conn, "ROLLBACK");
    $_SESSION['message'] = "Error: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
}

header("Location: registerToEvent.php?event_id=" . $event_id);
exit();
?>
