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

if (isset($_GET['poll']) && !empty($_GET['poll']))
{
    $pollId = $_GET['poll'];

    if (Poll::getPoll($pollId))
    {
        $poll = Poll::getPoll($pollId);

        if (Poll::hasVoted($pollId))
        {
            $answer = DB::query('SELECT name FROM poll_options
                                 JOIN user_voted ON poll_options.id = user_voted.poll_option_id
                                 WHERE poll_options.poll_id = :poll_id
                                 AND user_voted.user_id = :user_id', array(':poll_id' => $pollId, ':user_id' => $_SESSION['user_id']))[0]['name'];
        }

    }
    else
    {
        die("That poll does not exist or is not activated!");
    }
}
else
{
    die("Poll not valid!");
}

if (isset($_POST['votesubmit']) && !empty($_POST['voteoption']))
{

    $voteData = array(
        'poll_id' => $_POST['pollid'],
        'poll_option_id' => $_POST['voteoption']
    );

    $voteSubmit = Poll::vote($voteData);


    if ($voteSubmit)
    {
        header('location: index.php?msg=vote');
        exit();
    }
    else
    {
        die("Something went wrong");
    }

}

?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $poll['poll']['subject']; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="style/main.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style>
        label {display: block; margin-bottom: 0; cursor: pointer;}
    </style>
</head>
<body>

<header>

    <?php include_once 'includes/pageHeader.php'; ?>

</header>

<main>

    <?php if (!Poll::hasVoted($pollId)) { ?>

    <div id="pollContent">
        <blockquote>
        <form action="" method="post" name="pollform">
            <h3><?php echo $poll['poll']['subject']; ?></h3>
            <ul class="list-group">
                <?php
                foreach ($poll['options'] as $option)
                {
                    echo "<label><li class='list-group-item'><input type='radio' name='voteoption' value='" . $option['id'] . "' required>&nbsp;" . $option['name'] . "</li></label>";
                }
                ?>
            </ul>
            <input type="hidden" name="pollid" value="<?php echo $poll['poll']['id']; ?>">
            <input type="submit" name="votesubmit" class="btn btn-success" value="Vote">
        </form>
        </blockquote>
    </div>

    <?php } else { ?>

        <div id="pollContent">
            <blockquote>

                <h3><?php echo $poll['poll']['subject']; ?></h3>
                <ul class="list-group">
                    <?php
                    foreach ($poll['options'] as $option)
                    {
                        echo "<label><li class='list-group-item'><input type='radio' disabled>&nbsp;" . $option['name'] . "</li></label>";
                    }
                    ?>
                </ul>

            </blockquote>
            <div class="alert alert-success">
                <p>Your answer: <strong><?php echo $answer; ?></strong></p>
            </div>
        </div>

    <?php } ?>

</main>

</body>
</html>
