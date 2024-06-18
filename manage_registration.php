<?php
session_start();
include 'conn.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch registrations for the event
$event_id = $_GET['event_id']; // Get event ID from URL parameter
$query = "SELECT * FROM registrations WHERE event_id = $event_id";
$result = pg_query($conn, $query);

// Display registrations
echo "<h2>Registrations</h2>";
echo "<table border='1'>";
echo "<tr><th>User ID</th><th>Barcode</th><th>Actions</th></tr>";
while ($row = pg_fetch_assoc($result)) {
    echo "<tr>";
    echo "<td>{$row['user_id']}</td>";
    echo "<td>{$row['barcode']}</td>";
    echo "<td>";
    echo "<a href='edit_registration.php?registration_id={$row['registration_id']}'>Edit</a> | ";
    echo "<a href='delete_registration.php?registration_id={$row['registration_id']}'>Delete</a>";
    echo "</td>";
    echo "</tr>";
}
echo "</table>";

pg_close($conn);
