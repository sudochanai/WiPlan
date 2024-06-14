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

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $venue = $_POST['venue'];
    $time = $_POST['time'];
    $duration = $_POST['duration'];
    $mode = $_POST['mode'];

    // Update event in the database
    $query = "UPDATE events SET name = '$name', description = '$description', venue = '$venue', time = '$time', duration = '$duration', mode = '$mode' WHERE event_id = $event_id";
    $result = pg_query($conn, $query);

    if ($result) {
        header("Location: manage_events.php");
        exit();
    } else {
        echo "Error: Failed to update event.";
    }
}

// Fetch event details
$query = "SELECT * FROM events WHERE event_id = $event_id";
$result = pg_query($conn, $query);
$event = pg_fetch_assoc($result);

pg_close($conn);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Event</title>
    <link rel="stylesheet" href="./css/bootstrap.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>

<body>


    <form method="post" class="login-form">
        <h2>Edit Event</h2>
        Name: <input type="text" name="name" value="<?php echo $event['name']; ?>"><br>
        Description: <textarea name="description"><?php echo $event['description']; ?></textarea><br>
        Venue: <input type="text" name="venue" value="<?php echo $event['venue']; ?>"><br>
        Time: <input type="datetime-local" name="time" value="<?php echo date('Y-m-d\TH:i', strtotime($event['time'])); ?>"><br>
        Duration: <input type="text" name="duration" value="<?php echo $event['duration']; ?>"><br>
        Mode: <select name="mode" class="form-control">
            <option value="free" <?php if ($event['mode'] == 'free') echo 'selected'; ?>>Free</option>
            <option value="paid" <?php if ($event['mode'] == 'paid') echo 'selected'; ?>>Paid</option>
        </select><br>
        <input type="submit" value="Update" class="btn btn-primary">
        <br>
        <a href="manage_events.php" class="btn btn-danger">Cancel</a>
    </form>
</body>

</html>