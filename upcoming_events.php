<?php
session_start();
include 'conn.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get the current user's privileges
$privileges = $_SESSION['privileges'];
$user_id = $_SESSION['user_id'];

// Fetch all events that have not started yet
$query_events = "SELECT * FROM events WHERE time > NOW()";
$result_events = pg_query($conn, $query_events);



?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="./css/bootstrap.css">
    <link rel="stylesheet" href="./css/styles.css">
</head>
<body>
   
</body>
</html>
<?php
include 'conn.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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
                <span class="material-icons-outlined" >close</span>
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
                <li class="sidebar-list-item" >
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
            </ul>
        </aside>
        <!-- End Sidebar -->

        <!-- Main -->
        <main class="main-container">
        <div class="container">
        <h2>Upcoming Events</h2>
    <table class="table table-striped">
        <tr>
            <th>Event Name</th>
            <th>Venue</th>
            <th>Time</th>
            <th>Action</th>
        </tr>
        <?php
        $user_view = $_SESSION['user_id'];
        while ($row_event = pg_fetch_assoc($result_events)) {
            echo "<tr>";
            echo "<td>{$row_event['name']}</td>";
            echo "<td>{$row_event['venue']}</td>";
            echo "<td>{$row_event['time']}</td>";
            echo "<td>";
            echo "<a href='registerToEvent.php?event_id={$row_event['event_id']}' class='btn btn-primary'>View Event</a>";
            echo "</td>";
            echo "</tr>";
        }

        ?>
    </table>
        </div>


        <?php if ($privileges == 'organizer' && isset($_GET['view_registrations'])): ?>
    <div class="container">
        <?php
        $event_id = $_GET['event_id'];
        $query_registrations = "SELECT * FROM registrations WHERE event_id = $event_id";
        $result_registrations = pg_query($conn, $query_registrations);
        $query_event = "SELECT name FROM events WHERE event_id = $event_id";
        $result_event = pg_query($conn, $query_event);
        $event = pg_fetch_assoc($result_event);
        ?>
        <h2>Registrations for Event: <?php echo $event['name']; ?></h2>
        <table class="table table-striped">
            <tr>
                <th>User ID</th>
                <th>Barcode</th>
            </tr>
            <?php
            while ($row_registration = pg_fetch_assoc($result_registrations)) {
                echo "<tr>";
                echo "<td>{$row_registration['user_id']}</td>";
                echo "<td>{$row_registration['barcode']}</td>";
                echo "</tr>";
            }
            ?>
        </table>
    
    </div>
    <?php endif; ?>
        


    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="message">
            <?php 
            echo $_SESSION['message']; 
            unset($_SESSION['message']);
            ?>
        </div>
    <?php endif; ?>
        </main>
        <!-- End Main -->
    </div>

    <!-- Custom JS -->
    <script src="../js/script.js"></script>
</body>

</html>