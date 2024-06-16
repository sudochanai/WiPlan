<?php
session_start();
include 'conn.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the current user's privileges and ID
$privileges = $_SESSION['privileges'];
$user_id = $_SESSION['user_id'];

// Fetch all events created by the organizer
$query_organizer_events = "SELECT * FROM events WHERE organizer_id = $1";
$result_organizer_events = pg_query_params($conn, $query_organizer_events, array($user_id));

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

</head>

<body>
    <div class="grid-container">

        <!-- Header -->
        <header class="header">
            <div class="menu-icon" onclick="openSidebar()">
                <span class="material-icons-outlined">menu</span>
            </div>
            <div class="header-left">
                <p><?php echo $_SESSION['username']; ?>, Welcome to your WiPlan</p>
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
            </ul>
        </aside>
        <!-- End Sidebar -->

        <!-- Main -->
        <main class="main-container">
            ddddddddddddddddddddddddddddddddddd
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
                <h1>Upcoming Events</h1>
                <div class='card-deck mb-3 text-center'>
                    <?php
                    $query_events = "SELECT * FROM events WHERE time > NOW() ORDER BY event_id DESC LIMIT 3";
                    $result_events = pg_query($conn, $query_events);
                    while ($row_event = pg_fetch_assoc($result_events)) {
                        echo "<div class='card mb-4 shadow-sm' id='new-cards'>";
                        echo "<div class='card-header'>";
                        echo "<h4 class='my-0 font-weight-normal'>{$row_event['mode']}</h4>";
                        echo "</div>";
                        echo "<div class='image-divs'>";
                        if ($row_event['image_url']) {
                            echo "<img src='" . htmlspecialchars($row_event['image_url']) . "' alt='" . htmlspecialchars($row_event['name']) . "' style='max-width: 100%; height: auto;'>";
                        }
                        echo "</div>";
                        echo "<div class='card-body'>";
                        echo "<p class='card-title pricing-card-title'>Event Name: " . htmlspecialchars($row_event['name']) . "</p>";
                        echo "<ul class='list-unstyled mt-3 mb-4'>";
                        echo "<li><p>Time: " . htmlspecialchars($row_event['time']) . "</p></li>";
                        echo "<li><p>Venue: " . htmlspecialchars($row_event['venue']) . "</p></li>";
                        echo "</ul>";
                        echo "<p><a href='registerToEvent.php?event_id=" . htmlspecialchars($row_event['event_id']) . "' class='btn btn-lg btn-block btn-outline-primary'>View Details</a></p>";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                    <a href="upcoming_events.php" class="btn btn-lg btn-block btn-outline-warning" style="color:black; border:1px solid;">View More Events</a>
                </div>
            </div>

            <!-- Display Registered Events -->
            <div class="container">
                <h2>Your Registered Events</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Venue</th>
                            <th>Time</th>
                            <th>Barcode</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Ensure the user is logged in
                        if (isset($_SESSION['user_id'])) {
                            $user_id = $_SESSION['user_id'];

                            // Fetch registered events for the user
                            $query_registered = "
                                SELECT events.event_id, events.name, events.venue, events.time, registrations.barcode 
                                FROM events 
                                JOIN registrations ON events.event_id = registrations.event_id 
                                WHERE registrations.user_id = $1
                            ";
                            $result_registered = pg_query_params($conn, $query_registered, array($user_id));
                            while ($row = pg_fetch_assoc($result_registered)) {
                                echo "<tr>
                                    <td>" . htmlspecialchars($row['name']) . "</td>
                                    <td>" . htmlspecialchars($row['venue']) . "</td>
                                    <td>" . htmlspecialchars($row['time']) . "</td>
                                    <td>" . htmlspecialchars($row['barcode']) . "</td>
                                    <td>
                                        <form action='dashboard.php' method='post' onsubmit='return confirm(\"Are you sure you want to delete this registration?\");'>
                                            <input type='hidden' name='event_id' value='" . htmlspecialchars($row['event_id']) . "'>
                                            <input type='submit' name='delete_event' value='Delete' class='btn btn-danger'>
                                        </form>
                                    </td>
                                </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5'>Please log in to see your registered events.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </main>
        <!-- End Main -->
    </div>
    <?php pg_close($conn); ?>
    <!-- Custom JS -->
    <script src="../js/script.js"></script>
</body>

</html>
