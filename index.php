<?php
session_start();

include 'classes/DB.php';
include 'classes/Poll.php';
include 'classes/User.php';

if (!User::isLoggedIn())
{
    header('location: login.php');
    exit();
}

$fixedHeader = "";
$polls = Poll::getPolls();

if (isset($_GET['msg']))
{
    if ($_GET['msg'] == "vote")
    {
        $msg = "Thanks for voting!";

        $fixedHeader = "
            <article id='fixedMessage'>
                <p>" . $msg . "</p>
            </article>
        ";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Polls</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<header>

    <?php echo $fixedHeader; ?>

    <?php include_once 'includes/pageHeader.php'; ?>

</header>

<main>
    <div class="container">
        <article id="panel">
            <blockquote>
                <h2>Available Polls</h2>
                <p class="text-muted">
                    Lorem ipsumLorem ipsumLorem ipsumLorem
                    Lorem ipsumLorem ipsumLorem ipsumLorem
                    Lorem ipsumLorem ipsumLorem ipsumLorem
                    Lorem ipsumLorem ipsumLorem ipsumLorem
                    Lorem ipsumLorem ipsumLorem ipsumLorem
                </p>
                <button class="btn btn-success">Learn More!</button>
            </blockquote>
        </article>
        <hr>
        <section id="mainPoll">
            <article id="description">
                <h3>Choose Poll</h3>
                <p class="text-muted">
                    Lorem ipsumLorem ipsumLorem ipsumLorem
                    Lorem ipsumLorem ipsumLorem ipsumLorem
                </p>
            </article>
            <article id="polls" class="list-group">
                <?php
                echo $polls;
                ?>
            </article>
        </section>

    </div>
</main>

<footer>

</footer>

</body>
</html>
