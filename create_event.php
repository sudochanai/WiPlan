<?php
include 'conn.php';
session_start();
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
        <form action="create_event_action.php" method="post" class="login-form" enctype="multipart/form-data">
    <h1>Create Event</h1>
        <label for="name">Event Name:</label>
        <input type="text" name="name" required><br>
        <label for="description">Description:</label>
        <textarea name="description" required></textarea><br>
        <label for="venue">Venue:</label>
        <input type="text" name="venue" required><br>
        <label for="time">Time:</label>
        <input type="datetime-local" name="time" required><br>
        <label for="duration">Duration (e.g., 8 hours):</label>
        <input type="text" name="duration" required><br>
        <label for="mode">Mode:</label>
        <select name="mode" class="form-control">
            <option value="free">Free</option>
            <option value="paid" >Paid</option>
        </select><br>
        <label for="price">Price (if paid):</label>
        <input type="number" id="price" name="price" step="0.01"><br><br>

        <label for="image">Event Image:</label>
        <input type="file" id="image" name="image"><br><br>
        <input type="submit" value="Create Event" class="btn btn-primary">

        <br>
        <a href="dashboard.php" class="btn btn-danger">Cancel</a>
    </form>
        </main>
        <!-- End Main -->
    </div>

    <!-- Custom JS -->
    <script src="../js/script.js"></script>
</body>

</html>