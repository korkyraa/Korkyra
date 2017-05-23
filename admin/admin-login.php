<?php
session_start();

include '../classes/DB.php';
include '../classes/Admin.php';

$status = "";

if (!isset($_SESSION['admin_id']))
{
    if (isset($_POST['adminlogin']))
    {
        $adminEmail = $_POST['adminemail'];
        $adminPass = $_POST['adminpassword'];

        if (Admin::logIn($adminEmail, $adminPass))
        {
            header('location: admin-panel.php');
            exit();
        }
        else
        {
            header('location: admin-login.php?msg=fail');
            exit();
        }
    }
}
else
{
    header('location: admin-panel.php');
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
    <title>Admin - Log In</title>
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="../assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link rel="stylesheet" href="../style/login-register.css">
</head>
<body>
<div class="login-clean">
    <form action="" method="post">
        <h2 class="text-center">Admin Login</h2>
        <div class="illustration"><i class="icon ion-lock-combination"></i></div>
        <div class="form-group">
            <input class="form-control" type="email" id="email" name="adminemail" autocomplete="off" placeholder="Email">
        </div>
        <div class="form-group">
            <input class="form-control" type="password" id="password" name="adminpassword" placeholder="Password">
        </div>
        <div id="status">
            <?php echo $status; ?>
        </div>
        <div class="form-group">
            <input class="btn btn-primary btn-block" name="adminlogin" id="login" type="submit" value="Log In">
        </div>
    </form>
</div>
</body>
</html>
