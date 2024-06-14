<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="/css/bootstrap.css">
    <link rel="stylesheet" href="/css/styles.css">
    <link rel="script" href="/js/bootstrap.js">
    <link href="../assets/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="login-body">

    <form action="login_action.php" method="post" class="login-form">
        <h1><center>Login to WiPlan</center></h1>
        <label for="username">Email:</label>
        <input class="form-control" type="email" name="email" required><br>
        <label for="password">Password:</label>
        <input class="form-control" type="password" name="password" required><br>
        <input type="submit" class="btn btn-primary" value="Login">
        <br>
        <a href="register.php" class="btn btn-warning">Register</a>
    </form> 
</body>
</html>
