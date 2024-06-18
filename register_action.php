<?php
include 'conn.php'; // Include database connection

session_start(); // Start the session

// Start transaction
pg_query($conn, "BEGIN");

// Get form data
$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_BCRYPT);
$email = $_POST['email'];
$age = $_POST['age'];
$privileges = $_POST['privileges'];

// Insert user into the database
$query = "INSERT INTO users (username, password, email, age, privileges) VALUES ('$username', '$password', '$email', $age, '$privileges')";
$result = pg_query($conn, $query);

// Check if user insertion succeeded
if ($result) {
    // Commit transaction if insertion succeeds
    pg_query($conn, "COMMIT");
    $_SESSION['message'] = "Registration successful!";
    $_SESSION['message_type'] = "success";
    header("Location: login.php");
    exit();
} else {
    // Rollback transaction if insertion fails
    pg_query($conn, "ROLLBACK");
    $_SESSION['message'] = "Error: User registration failed.";
    $_SESSION['message_type'] = "danger";
    header("Location: register.php");
    exit();
}

pg_close($conn);
?>
