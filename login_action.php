<?php
session_start();

// Connect to the database
include 'conn.php';

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

// Verify user
$query = "SELECT * FROM users WHERE email = '$email'";
$result = pg_query($conn, $query);
$user = pg_fetch_assoc($result);

if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['email'] = $user['email'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['privileges'] = $user['privileges'];
    $_SESSION['event_id']= $event_id ['event_id'];
    header("Location: dashboard.php");
} else {
    echo "Invalid username or password.";
}

pg_close($conn);
?>
