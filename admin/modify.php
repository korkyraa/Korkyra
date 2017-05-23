<?php
session_start();

include '../classes/DB.php';
include '../classes/Poll.php';
include '../classes/Admin.php';

$pollHTML = "<form action=\"adminHandler.php\" method=\"post\">";
$pollHTML = "<div class=\"answer-wrapper\">";
$pollHTML = "
        <div class=\"form-group\">
            <label for=\"question\">Question:</label>
            <input type=\"text\" name=\"question\" class=\"form-control\" id=\"question\" autocomplete=\"off\">
        </div>";

if (!Admin::isLoggedIn())
{
    header('location: admin-login.php');
    exit();
}

if (isset($_GET['poll']) && !empty($_GET['poll']))
{
    $pollId = $_GET['poll'];

    if (Poll::getPoll($pollId))
    {
        $poll = Poll::getPoll($pollId);
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

$pollHTML = "<form action=\"adminHandler.php\" method=\"post\">";
$pollHTML .= "<div class=\"answer-wrapper\">";
$pollHTML .= "
        <div class=\"form-group\">
            <label for=\"question\">Question:</label>
            <input type=\"text\" name=\"question\" class=\"form-control\" id=\"question\" value='" . $poll['poll']['subject'] . "' autocomplete=\"off\">
        </div>";

foreach ($poll['options'] as $option)
{
    $pollHTML .=
        "<div class=\"form-group\">
            <label for='an". $option['id'] ."'>Answer:</label>
            <input type=\"text\" name=\"answers[]\" class=\"form-control\" id='an". $option['id'] ."' value='" . $option['name'] . "' autocomplete=\"off\">
        </div>";
}

$pollHTML .= "</div>";
$pollHTML .= "<button type=\"submit\" name=\"modifyquestion\" class=\"btn btn-success\">Modify</button>";
$pollHTML .= "</form>";

?>
<!DOCTYPE html>
<html>
<head>
    <title>Modify</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../style/main.css">
    <link rel="stylesheet" href="../style/admin.css">
    <link rel="stylesheet" href="../assets/fonts/ionicons.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>

<header>

    <?php include_once '../includes/adminHeader.php'; ?>

</header>

<main>
    <div class="container">
        <div class="cnq">

            <?php echo $pollHTML; ?>

        </div>
    </div>
</main>

<script>

    $(document).ready(function() {

        var maxFields = 10; //maximum input boxes allowed
        var wrapper = $(".answer-wrapper"); //Fields wrapper
        var addButton = $("#addMore"); //Add button ID
        var x = 2; //initlal text box count

        $(addButton).click(function(e) { //on add input button click
            e.preventDefault();

            if (x < maxFields) { //max input box allowed
                x++; //text box increment
                console.log(wrapper);

                $(wrapper).append('<div class="form-group">' +
                    '<label for="an' + x + '">Answer:</label>' +
                    '<input type="text" name="answers[]" class="form-control" id="an' + x + '" autocomplete="off">' +
                    '<a href="#" id="removeField">Remove</a>' +
                    '</div>'); //add input box
            }
        });

        $(wrapper).on("click", "#removeField", function(e) { //user click on remove text
            e.preventDefault();
            $(this).parent('div').remove();
            x--;
        })
    });

</script>
</body>
</html>