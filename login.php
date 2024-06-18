<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Connect to the database
    include 'conn.php';

    // Get form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verify user
    $query = "SELECT * FROM users WHERE email = $1";
    $result = pg_query_params($conn, $query, array($email));
    $user = pg_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['privileges'] = $user['privileges'];
        $_SESSION['message'] = "Login successful!";
        $_SESSION['message_type'] = "success";
        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['message'] = "Invalid username or password.";
        $_SESSION['message_type'] = "danger";
        header("Location: login.php");
        exit();
    }

    pg_close($conn);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/styles.css">
    <link href="assets/dist/css/bootstrap.min.css" rel="stylesheet">
    
</head>
<body class="login-body">

    <form action="login.php" method="post" class="login-form">
        <h1><center>Login to WiPlan</center></h1>

        <label for="username">Email:</label>
        <input class="form-control" type="email" name="email" required><br>
        <label for="password">Password:</label>
        <input class="form-control" type="password" name="password" required><br>
        <input type="submit" class="btn btn-primary" value="Login">
        <br>
        <a href="register.php" class="btn btn-warning">Register</a>
    </form>

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

<script src="js/script.js"></script>
</body>
</html>
