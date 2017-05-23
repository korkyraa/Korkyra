<?php
session_start();

include '../classes/DB.php';
include '../classes/Poll.php';
include '../classes/Admin.php';

if (!Admin::isLoggedIn())
{
    header('location: admin-login.php');
    exit();
}

if (isset($_GET['poll']) && !empty($_GET['poll']))
{
    $pollId = $_GET['poll'];

    if (Poll::getResults($pollId))
    {
        $pollVotes = Poll::getResults($pollId);
    }
    else
    {
        die("That poll does not exist!");
    }
}
else
{
    die("Poll not valid!");
}

if (!$pollVotes['total_votes'])
{
    die("This poll does not have any votes yet!");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Results - <?php echo $pollVotes['poll']; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/main.css">
    <link rel="stylesheet" href="../style/admin.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>

        /* COLORS */
        .azure   { background: #38B1CC; }
        .emerald { background: #2CB299; }
        .violet  { background: #8E5D9F; }
        .yellow  { background: #EFC32F; }
        .red     { background: #E44C41; }

        .progress-container {
            width: 100%; /* Full width */
            background-color: #ddd; /* Grey background */
        }

        .bar {
            text-align: right; /* Right-align text */
            padding-right: 20px; /* Add some right padding */
            line-height: 40px; /* Set the line-height to center the text inside the skill bar, and to expand the height of the container */
            color: white; /* White text color */
        }

    </style>
</head>
<body>

<header>

    <?php include_once '../includes/adminHeader.php'; ?>

</header>

<main>
    <div class="container">
        <h3><?php echo $pollVotes['poll']; ?></h3>
        <p><b>Total Votes:</b><span class="badge bg-success"><?php echo $pollVotes['total_votes']; ?></span></p>

        <?php

        if (!empty($pollVotes['options']))
        {
            $i = 0;

            $barColorArr = array('azure', 'emerald', 'violet', 'yellow', 'red');

            foreach($pollVotes['options'] as $opt => $vote)
            {

                $votePercent = round(($vote / $pollVotes['total_votes']) * 100);
                $votePercent = !empty($votePercent) ? $votePercent . '%' : '0%';

                if (!array_key_exists($i, $barColorArr))
                {
                    $i = 0;
                }

                $barColor = $barColorArr[$i];
        ?>

        <p class="text-muted"><?php echo $opt; ?></p>
        <div class="progress-container">
            <div class="bar <?php echo $barColor; ?>" style="width: <?php echo $votePercent; ?>"><?php echo $votePercent; ?></div>
        </div>

        <?php $i++; } } ?>
    </div>
</main>
</body>
</html>
