<?php
session_start();

include 'classes/DB.php';
include 'classes/User.php';

$status = "";

if (!isset($_SESSION['user_id']))
{
    if (isset($_POST['login']))
    {
        $userEmail = $_POST['email'];
        $userPass = $_POST['password'];

        if (User::logIn($userEmail, $userPass))
        {
            header('location: index.php');
            exit();
        }
        else
        {
            header('location: login.php?msg=fail');
            exit();
        }
    }
}
else
{
    header('location: index.php');
    exit();
}

if (isset($_GET['msg']) && $_GET['msg'] == "fail")
{
    $status = "Username or password is incorrect!";
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="style/login-register.css">
</head>
<body>
<div class="login-clean">
    <form action="" method="post">
        <h2 class="text-center">User Login</h2>
        <div class="illustration"><i class="icon ion-lock-combination"></i></div>
        <div class="form-group">
            <input class="form-control" type="email" id="email" name="email" autocomplete="off" placeholder="Email">
        </div>
        <div class="form-group">
            <input class="form-control" type="password" id="password" name="password" placeholder="Password">
        </div>
        <div id="status">
            <?php echo $status; ?>
        </div>
        <div class="form-group">
            <button class="btn btn-success btn-block" name="login" id="login" type="submit">Log In</button>
        </div>
        <a href="create-account.php" class="forgot">Don't have an account? Create one here</a>
    </form>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/bs-animation.js"></script>
</body>
</html>
