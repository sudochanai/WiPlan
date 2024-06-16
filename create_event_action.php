<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['privileges'] != 'organizer') {
    header("Location: login.php");
    exit();
}

include 'conn.php'; // Include database connection

// Get form data
$name = $_POST['name'];
$description = $_POST['description'];
$venue = $_POST['venue'];
$time = $_POST['time'];
$duration = $_POST['duration'];
$mode = $_POST['mode'];
$organizer_id = $_SESSION['user_id'];
$image_url = null;

// Check and process image upload
if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_url = $target_file;
    } else {
        $_SESSION['message'] = "Error: Failed to upload image.";
        $_SESSION['message_type'] = "danger";
        header("Location: create_event.php");
        exit();
    }
}

// Insert event into the database
$query = "INSERT INTO events (name, description, venue, time, duration, mode, status, organizer_id, image_url) 
          VALUES ('$name', '$description', '$venue', '$time', '$duration', '$mode', 'before', $organizer_id,'$image_url')";
$result = pg_query($conn, $query);

// Check if event insertion succeeded
if ($result) {
    // Set success message
    $_SESSION['message'] = "Event created successfully.";
    $_SESSION['message_type'] = "success";
    // Commit transaction if insertion succeeds
    pg_query($conn, "COMMIT");
    header("Location: dashboard.php");
    exit();
} else {
    // Rollback transaction if insertion fails
    pg_query($conn, "ROLLBACK");
    $_SESSION['message'] = "Error: Event creation failed.";
    $_SESSION['message_type'] = "danger";
    header("Location: create_event.php");
    exit();
}

pg_close($conn);
?>
