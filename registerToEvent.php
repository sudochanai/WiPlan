<?php
session_start();
include 'conn.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if event ID is set in the URL
if (!isset($_GET['event_id'])) {
    echo "Error: No event selected.";
    exit();
}

// Check session for privileges
$privileges = $_SESSION['privileges'];

// Get the event ID from the URL
$event_id = $_GET['event_id'];

// Fetch event details based on event ID
$query_event = "SELECT * FROM events WHERE event_id = $1";
$result_event = pg_prepare($conn, "fetch_event", $query_event);
$result_event = pg_execute($conn, "fetch_event", array($event_id));

if (!$result_event || pg_num_rows($result_event) == 0) {
    echo "Error: Event not found.";
    exit();
}

// Fetch event details
$event = pg_fetch_assoc($result_event);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Event Registration</title>

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
            <div class="container">
                <div class="">
                    <?php
                    if ($event['image_url']) {
                        echo "<img class='event-image-divs' src='" . htmlspecialchars($event['image_url']) . "' alt='" . htmlspecialchars($event['image_url']) . "' >";
                    }
                    ?>
                </div>
                <h2><?php echo $event['name']; ?></h2>
                <p><?php echo $event['description']; ?></p>
                <!-- Form to register for the event -->
                <form action="registerToEvent_action.php" method="post">
                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                    <button type="submit" class="btn btn-primary">Register</button>
                </form>
            </div>
        </main>
        <!-- End Main -->
    </div>

    <!-- Toast notification -->
    <?php
    if (isset($_SESSION['message'])) {
        echo '<div id="toast" class="toast ' . $_SESSION['message_type'] . ' show">'
            . $_SESSION['message'] .
            '</div>';
        unset($_SESSION['message']);
        unset($_SESSION['message_type']);
    }
    ?>

    <!-- Custom JS -->
    <script src="../js/script.js"></script>
</body>

</html>
