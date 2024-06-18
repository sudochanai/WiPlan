<?php
include 'conn.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check user privileges
$privileges = $_SESSION['privileges'];


// Handle event deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_event'])) {
    $event_id_to_delete = $_POST['event_id'];
    $query_delete_registration = "DELETE FROM registrations WHERE event_id = $1 AND user_id = $2";
    pg_query_params($conn, $query_delete_registration, array($event_id_to_delete, $user_id));
    $_SESSION['message'] = "Event deleted successfully.";
    $_SESSION['message_type'] = "success";
    header("Location: dashboard.php");
    exit();
}

// Undo deletion (if needed)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['undo_delete'])) {
    // Implement undo delete functionality as needed
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Montserrat Font -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/bootstrap.css">
    <link rel="stylesheet" href="../css/styles.css">

    <style>
        /* Custom styles for report issue options */
        .container {
            width: 90%;
            padding: 30px;
            margin: 30px auto;
            border: 1px solid rgba(0, 0, 0, 0.5);
            border-radius: 5px;
        }
    </style>

</head>

<body>
    <div class="grid-container">

        <!-- Header -->
        <header class="header">
            <div class="menu-icon" onclick="openSidebar()">
                <span class="material-icons-outlined">menu</span>
            </div>
            <div class="header-left">
                <p>Welcome, <?php echo $_SESSION['username']; ?> Welcome to your WiPlan</p>
            </div>
            <div class="header-right">
                <span class="material-icons-outlined">notifications</span>
                <span class="material-icons-outlined">email</span>
                <span class="material-icons-outlined">account_circle</span>
                <span class="material-icons-outlined"><a href="./logout.php">logout</a></span>
            </div>
        </header>
        <!-- End Header -->

        <!-- Sidebar -->
        <aside id="sidebar">
            <div class="sidebar-title">
                <div class="sidebar-brand">
                    <span class="material-icons-outlined">edit_calendar</span> WiPlan
                    <!-- I have to replace this with udom label -->
                </div>
                <span class="material-icons-outlined">close</span>
            </div>

            <ul class="sidebar-list">
                <a href="dashboard.php">
                    <li class="sidebar-list-item">
                        <span class="material-icons-outlined">dashboard</span> Dashboard
                    </li>
                </a>
                <?php if ($privileges == 'organizer') : ?>
                    <a href="create_event.php">
                        <li class="sidebar-list-item">
                            <span class="material-icons-outlined">bolt</span> Create Events
                        </li>
                    </a>
                    <a href="manage_events.php">
                        <li class="sidebar-list-item">
                            <span class="material-icons-outlined">swap_horiz</span> Manage Event
                        </li>
                    </a>
                <?php endif; ?>
                <a href="upcoming_events.php">
                    <li class="sidebar-list-item">
                        <span class="material-icons-outlined">keyboard_double_arrow_up</span>Upcoming Events
                    </li>
                </a>


                <li class="sidebar-list-item">
                    <span class="material-icons-outlined">settings</span> Settings
                </li>

                <!-- Report Issue Options -->
                <a href="dashStudentChangePass.php">
                    <li class="sidebar-list-item dash_Student_Setting" style="display: none;">
                        Change Password
                    </li>
                </a>

                <a href="dashStudentChangeATtoT.php">
                    <li class="sidebar-list-item dash_Student_Setting" style="display: none;">
                        Change AT to T
                    </li>
                </a>
            </ul>
        </aside>
        <!-- End Sidebar -->

        <!-- Main -->
        <main class="main-container">

            <!-- this is in place incase tukitaka kuongeza a button for a person to view events they have registered to -->
            <!-- <p><a href="view_registered_events.php">View Events</a></p> -->
            <div class="container">
                    <!-- Toast notification -->
                <?php
                if (isset($_SESSION['message'])) {
                    echo '<div id="toast" class="toast ' . $_SESSION['message_type'] . '">'
                        . $_SESSION['message'] .
                        '</div>';
                    unset($_SESSION['message']);
                    unset($_SESSION['message_type']);
                }
                ?>

                <?php         // Fetch events
                $event_user = $_SESSION['user_id'];
                $query = "SELECT * FROM events WHERE organizer_id = $event_user ";
                $result = pg_query($conn, $query);

                // Display events
                echo "<h2>Events</h2>";
                echo "<table class='table table-striped'";
                echo "<tr><th>Name</th><th>Venue</th><th>Time</th><th>Mode</th><th>Actions</th></tr>";
                while ($row = pg_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>{$row['name']}</td>";
                    echo "<td>{$row['venue']}</td>";
                    echo "<td>{$row['time']}</td>";
                    echo "<td>{$row['mode']}</td>";
                    echo "<td>";
                    if ($_SESSION['privileges'] == 'organizer') {
                        echo "<a href='edit_event.php?event_id={$row['event_id']}' class='btn btn-warning' id='manage-btn'>Edit</a>";
                        echo "<a href='delete_event.php?event_id={$row['event_id']}' class='btn btn-danger' id='manage-btn'>Delete</a>";
                        echo "<a href='view_event_registrations.php?event_id={$row['event_id']}' class='btn btn-primary' id='manage-btn'>View Registrations</a> ";
                    } elseif ($_SESSION['privileges'] == 'attendee' && $row['mode'] == 'paid') {
                        echo "<a href='signup_event.php?event_id={$row['event_id']}' class='btn btn-primary'>Sign Up ({$row['amount']} USD)</a>";
                    } else {
                        echo "<a href='signup_event.php?event_id={$row['event_id']}' class='btn btn-primary'>Sign Up</a>";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</table>";

                pg_close($conn); ?>
            </div>

        </main>
        <!-- End Main -->
    </div>

    <!-- Custom JS -->
    <script src="../js/script.js"></script>
</body>

</html>