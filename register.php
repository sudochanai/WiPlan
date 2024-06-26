<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="script" href="/js/bootstrap.js">
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
   
    <form action="register_action.php" method="post" class="login-form">
        <h1>Register</h1>
        <label for="username">Username:</label>
        <input type="text" name="username" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>
        <label for="age">Age:</label>
        <input type="number" name="age" required><br>
        <label for="privileges">Privileges:</label>
        <select name="privileges" class="form-control">
            <option value="attendee">Attendee</option>
            <option value="organizer">Organizer</option>
        </select><br>
        <input type="submit" value="Register" class="btn btn-primary">
        <br>
        <a href="login.php" class="btn btn-warning">Login</a>
    </form>
</body>
</html>
