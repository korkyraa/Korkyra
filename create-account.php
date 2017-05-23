<?php
session_start();

include 'classes/DB.php';
include 'classes/User.php';

$status = "";

if (!isset($_SESSION['user_id']))
{
    if (isset($_POST['createaccount']))
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $email = $_POST['email'];

        if (!empty($username) && !empty($password) && !empty($email)) {
            if (!DB::query('SELECT username FROM users WHERE username = :username', array(':username' => $username))) {
                if (strlen($username) >= 3 && strlen($username) <= 16) {
                    if (preg_match('/[a-z_]+/', $username)) {
                        if (strlen($password) >= 6) {
                            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                if (!DB::query('SELECT email FROM users WHERE email = :email', array(':email' => $email))) {
                                    DB::query('INSERT INTO users VALUES (\'\', :email, :username, :password)', array(':email' => $email, ':username' => $username, ':password' => password_hash($password, PASSWORD_BCRYPT),));
                                    header('location: login.php');
                                    exit();
                                } else {
                                    header('location: create-account.php?msg=Email in use!');
                                    exit();
                                }
                            } else {
                                header('location: create-account.php?msg=Invalid email!');
                                exit();
                            }
                        } else {
                            header('location: create-account.php?msg=Invalid password!');
                            exit();
                        }
                    } else {
                        header('location: create-account.php?msg=Invalid username!');
                        exit();
                    }
                } else {
                    header('location: create-account.php?msg=Invalid username!');
                    exit();
                }
            } else {
                header('location: create-account.php?msg=Username already exists!');
                exit();
            }
        } else {
            header('location: create-account.php?msg=All fields required!');
            exit();
        }
    }
}
else
{
    header('location: index.php');
    exit();
}

if (isset($_GET['msg']))
{

    $status = $_GET['msg'];
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create an Account</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="style/login-register.css">
</head>
<body>
<div class="login-clean">
    <form action="" method="post">
        <h2 class="text-center">Create an Account</h2>
        <div class="illustration"><i class="icon ion-lock-combination"></i></div>
        <div class="form-group">
            <input class="form-control" type="email" id="email" name="email" autocomplete="off" placeholder="Email">
        </div>
        <div class="form-group" data-toggle="tooltip" title="3 to 16 characters [a-z_]">
            <input class="form-control" type="text" id="username" name="username" autocomplete="off" placeholder="Username">
        </div>
        <div class="form-group" data-toggle="tooltip" title="Minimum 6 characters">
            <input class="form-control" type="password" id="password" name="password" placeholder="Password">
        </div>
        <div id="status">
            <?php echo $status; ?>
        </div>
        <div class="form-group">
            <button class="btn btn-success btn-block" name="createaccount" id="createaccount" type="submit">Create Account</button>
        </div>
        <a href="login.php" class="forgot">Already have an account? Log In</a>
    </form>
</div>
<script src="assets/js/jquery.min.js"></script>
<script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script src="assets/js/bs-animation.js"></script>
<script>

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip({placement: "right"});
    });

</script>
</body>
</html>
