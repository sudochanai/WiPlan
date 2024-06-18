<?php
include 'conn.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $event_id = $_POST['event_id'];

    // Begin transaction
    pg_query($conn, "BEGIN");

    try {
        // Delete the registration
        $query_delete_registration = "DELETE FROM registrations WHERE event_id = $event_id AND user_id = $user_id";
        $result_delete_registration = pg_query($conn, $query_delete_registration);

        if ($result_delete_registration) {
            // Commit transaction
            pg_query($conn, "COMMIT");
            echo "Registration deleted successfully.";
        } else {
            // Rollback transaction in case of error
            pg_query($conn, "ROLLBACK");
            echo "Error: Failed to delete registration.";
        }
    } catch (Exception $e) {
        // Rollback transaction in case of exception
        pg_query($conn, "ROLLBACK");
        echo "Error: " . $e->getMessage();
    }

    // Redirect back to the dashboard
    header("Location: dashboard.php");
    exit();
}

pg_close($conn);
?>
