<?php
session_start();
include 'conn.php'; // Include database connection

// Check if user is logged in and is an organizer
if (!isset($_SESSION['user_id']) || $_SESSION['privileges'] != 'organizer') {
    header("Location: login.php");
    exit();
}

// Get event ID from URL parameter
if (!isset($_GET['event_id'])) {
    echo "Event ID is required.";
    exit();
}

if (isset($_GET['event_id']) && is_numeric($_GET['event_id'])) {
    $event_id = intval($_GET['event_id']);
} else {
    echo "Invalid event ID.";
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch event details to ensure the event belongs to the organizer
$query_event = "SELECT * FROM events WHERE event_id = $1 AND organizer_id = $2";
$result_event = pg_query_params($conn, $query_event, array($event_id, $user_id));

if (pg_num_rows($result_event) == 0) {
    echo "No such event found or you do not have permission to view this event.";
    exit();
}
$event = pg_fetch_assoc($result_event);

// Fetch registrations for the event
$query_registrations = "
    SELECT registrations.user_id, registrations.barcode, users.username
    FROM registrations
    JOIN users ON registrations.user_id = users.user_id
    WHERE registrations.event_id = $1
";
$result_registrations = pg_query_params($conn, $query_registrations, array($event_id));

?>






<?php
// Check user privileges
$privileges = $_SESSION['privileges'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Dashboard</title>

    <!-- Montserrat Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="./css/bootstrap.css">
    <link rel="stylesheet" href="../css/styles.css">

    <style>
        /* Custom styles for report issue options */
        .container{
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
            <div class="header-left">
                <p><?php echo $_SESSION['username']; ?>, Welcome to WiPlan</p>
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
                <?php if ($privileges == 'organizer'): ?>
                    <a href="create_event.php">
                    <li class="sidebar-list-item">
                    <span class="material-icons-outlined">bolt</span> Create Events
                </li>
                    </a>
                    <a href="manage_events.php">
                <li class="sidebar-list-item" ">
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

                
        </aside>
        <!-- End Sidebar -->

        <!-- Main -->
        <main class="main-container">
             <div class="container">
             <h2><?php echo $event['name'] ;  ?> Registrations</h2>
            <table class="table table-striped">
             <tr>
            <th>Username</th>
            <th>Barcode</th>
             </tr>
            <?php
            while ($row_registration = pg_fetch_assoc($result_registrations)) {
            echo "<tr>";
            echo "<td>{$row_registration['username']}</td>";
            echo "<td>{$row_registration['barcode']}</td>";
            echo "</tr>";
             }
        ?>
    </table>
             </div>      
            
        </main>
        <!-- End Main -->
    </div>

    <!-- Custom JS -->
    <script src="../js/script.js"></script>
</body>

</html>