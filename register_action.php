<?php
include 'conn.php'; // Include database connection

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
    header("Location: login.php");
    exit();
} else {
    // Rollback transaction if insertion fails
    pg_query($conn, "ROLLBACK");
    echo "Error: User registration failed.";
}

pg_close($conn);
?>

